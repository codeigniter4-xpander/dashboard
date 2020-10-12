<?php namespace CI4Xpander_Dashboard;

use CI4Xpander_AdminLTE\View\Component\Form\Type;
use CI4Xpander_Dashboard\Helpers\CRUD;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Exceptions\PageNotFoundException;
use Stringy\StaticStringy;

/**
 * @property \CI4Xpander_Dashboard\View $view
 */
class Controller extends \CI4Xpander\Controller
{
    protected $name = '';
    protected $_canonicName = '';

    /**
     * @var \CI4Xpander_Dashboard\Entities\User $user
     */
    protected $user;

    protected $CRUD = [
        'enable' => false,
        'base_url' => '',
        'form' => [
            'input' => []
        ]
    ];

    protected $permissionRuleSet = [
        'index' => 'R',
        'data' => 'R',
        'create' => 'C',
        'update' => 'U',
        'delete' => 'D'
    ];

    protected function _init()
    {
        parent::_init();

        $this->user = (object) \Config\Services::session()->get('user');
        $this->view->data->user->name = $this->user->name;

        $grantedMenu = \CI4Xpander_Dashboard\Models\Menu::create()
            ->select('menu.*')
            ->join('menu_type mt', 'mt.id = menu.type_id')
            ->join('menu_permission mp', 'mp.menu_id = menu.id')
            ->join('permission p', 'p.id = mp.permission_id')
            ->join('role_permission rp', 'rp.permission_id = p.id')
            ->join('role r', 'r.id = rp.role_id')
            ->join('user_role ur', 'ur.role_id = r.id')
            ->where('mt.code', 'dashboard')
            ->where('ur.user_id', $this->user->id)
            ->orderBy('menu.level', 'ASC')
            ->orderBy('menu.sequence_position', 'ASC')
            ->groupBy('menu.id')
            ->findAll();

        $this->view->data->template->menu->items = $this->_buildMenuTree($grantedMenu);

        $this->_canonicName = preg_replace("/[^a-zA-Z0-9]/", "", $this->name);
    }

    protected function _buildMenuTree($items, $parent = null)
    {
        $result = [];

        foreach ($items as $item) {
            $isActive = false;
            if ($item->url == 'dashboard') {
                $isActive = uri_string() == 'dashboard';
            } else {
                $isActive = strpos(uri_string(), $item->url) !== false;
            }

            if (!is_null($parent)) {
                if ($item->parent_id == $parent->id) {
                    $m = \CI4Xpander_AdminLTE\View\Component\Menu\Item\Data::create([
                        'name' => $item->name,
                        'url' => $item->url,
                        'isActive' => $isActive,
                        'icon' => $item->icon,
                        'childs' => []
                    ]);

                    $m->childs = $this->_buildMenuTree($items, $item);

                    $result[] = $m;
                }
            } else {
                if ($item->parent_id == 0) {
                    $m = \CI4Xpander_AdminLTE\View\Component\Menu\Item\Data::create([
                        'name' => $item->name,
                        'url' => $item->url,
                        'isActive' => $isActive,
                        'icon' => $item->icon,
                        'childs' => []
                    ]);

                    $m->childs = $this->_buildMenuTree($items, $item);

                    $result[] = $m;
                }
            }
        }

        return $result;
    }

    public function index()
    {
        $this->_checkCRUD('index');

        return $this->_render(function () {
            $this->view->data->page->title = lang('CI4Xpander_Dashboard.index.page.title', [
                ($this->view->data->page->title ?: $this->name)
            ]);

            if (isset($this->CRUD['index'])) {
                $action = array_merge([
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ], $this->CRUD['action'] ?? []);

                if (isset($this->CRUD['permission'])) {
                    $permissionToCheck = [];
                    if ($action['create']) {
                        $permissionToCheck[] = 'C';
                    }

                    if ($action['update']) {
                        $permissionToCheck[] = 'U';
                    }

                    if ($action['delete']) {
                        $permissionToCheck[] = 'D';
                    }

                    $permission = $this->user->getPermission($this->CRUD['permission'], $permissionToCheck);

                    if ($action['create']) {
                        $action['create'] = $permission->C;
                    }

                    if ($action['update']) {
                        $action['update'] = $permission->U;
                    }

                    if ($action['delete']) {
                        $action['delete'] = $permission->D;
                    }

                }

                $box = \CI4Xpander_AdminLTE\View\Component\Box::create();

                $table = null;
                if (isset($this->CRUD['index']['isDataTable'])) {
                    if ($this->CRUD['index']['isDataTable']) {
                        $ID = "{$this->_canonicName}_DataTable";

                        $actionColumns = [];
                        if ($action['update']) {
                            $actionColumns['update'] = [
                                'searchable' => false,
                                'orderable' => false
                            ];
                        }

                        if ($action['delete']) {
                            $actionColumns['delete'] = [
                                'searchable' => false,
                                'orderable' => false
                            ];
                        }

                        $columns = array_merge(
                            $this->CRUD['index']['columns'],
                            $actionColumns
                        );

                        $table = \CI4Xpander_AdminLTE\View\Component\Table::create();
                        $table->data->isDataTable = true;
                        $table->data->id = $ID;
                        $table->data->columns = $columns;
                        // $table->data->rows = $this->CRUD['index']['query']->get()->getResult();

                        \Config\Services::viewScript()->add(view('CI4Xpander_AdminLTE\Views\Script\DataTable', [
                            'id' => $ID,
                            'isServerSide' => $this->CRUD['index']['isServerSide'],
                            'columns' => $columns
                        ], [
                            'saveData' => false
                        ]));
                    }
                }

                $rowPager = '';
                if (is_null($table)) {
                    $minLimit = 10;
                    $maxLimit = 100;

                    $search = preg_replace('/\s+/', '%', $this->request->getGet('search')) ?? '';

                    $page = $this->request->getGet('page') ?? 1;
                    if (is_numeric($page)) {
                        $page = intval($page);
                        if ($page < 1) {
                            $page = 1;
                        }
                    } else {
                        $page = 1;
                    }

                    $limit = $this->request->getGet('limit') ?? 10;
                    if (is_numeric($limit)) {
                        $limit = intval($limit);
                        if ($limit < $minLimit) {
                            $limit = $minLimit;
                        } elseif ($limit > $maxLimit) {
                            $limit = $maxLimit;
                        }
                    } else {
                        $limit = 10;
                    }

                    $offset = $page * $limit - $limit;

                    /**
                     * @var \CodeIgniter\Database\BaseBuilder
                     */
                    $query = $this->CRUD['index']['query'];

                    $query->select('\'' . anchor("{$this->CRUD['base_url']}/detail/{id}", lang('CI4Xpander_Dashboard.general.detail')) . '\' AS detail', false);
                    $query->select('\'' . anchor("{$this->CRUD['base_url']}/update/{id}", lang('CI4Xpander_Dashboard.general.update')) . '\' AS update', false);
                    $query->select('\'' . anchor("{$this->CRUD['base_url']}/delete/{id}", lang('CI4Xpander_Dashboard.general.delete')) . '\' AS delete', false);

                    if (!empty($search)) {
                        $i = 0;
                        foreach ($this->CRUD['index']['columns'] as $name => $title) {
                            if ($i == 0) {
                                $query->like($name, $search, 'both', null, true);
                            } else {
                                $query->orLike($name, $search, 'both', null, true);
                            }
                            $i++;
                        }
                    }

                    $total = $query->countAllResults(false);

                    $query->limit($limit, $offset);

                    $table = \CI4Xpander_AdminLTE\View\Component\Table::create();

                    $tableAction = [
                        'detail' => ''
                    ];

                    if ($action['update']) {
                        $tableAction['update'] = '';
                    }

                    if ($action['delete']) {
                        $tableAction['delete'] = '';
                    }

                    $table->data->columns = array_merge(
                        $this->CRUD['index']['columns'],
                        $tableAction
                    );

                    $table->data->rows = $query->get()->getResult();

                    $colPager = \CI4Xpander_AdminLTE\View\Component\Column::create();
                    $colPager->data->content = \Config\Services::pager()->makeLinks($page, $limit, $total, 'CI4Xpander_AdminLTE_full');
                    $rowPager = \CI4Xpander_AdminLTE\View\Component\Row::create();
                    $rowPager->data->content = $colPager;
                }

                $colTable = \CI4Xpander_AdminLTE\View\Component\Column::create();
                $colTable->data->content = $table;
                $rowTable = \CI4Xpander_AdminLTE\View\Component\Row::create();
                $rowTable->data->content = $colTable;

                $box->data->body = $rowTable . $rowPager;

                if ($action['create']) {
                    $addButton = \CI4Xpander_AdminLTE\View\Component\Button::create(\CI4Xpander_AdminLTE\View\Component\Button\Data::create([
                        'text' => lang('CI4Xpander_Dashboard.general.create'),
                        'isBlock' => true,
                        'type' => 'primary',
                        'isLink' => true,
                        'url' => $this->CRUD['base_url'] . '/create'
                    ]));

                    $box->data->head->tool = $addButton;
                }

                $message = \Config\Services::dashboardMessage()->render();

                $this->view->data->template->content = $message . $box->render();
            }

            $this->view->data->crud->baseUrl = $this->CRUD['base_url'];

            \Config\Services::viewScript()->add(view('CI4Xpander_AdminLTE\Views\Script\ModalDeleteConfirmation'));

            return $this->view->render();
        });
    }

    public function data()
    {
        if (!$this->request->isAJAX()) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $this->_checkCRUD('data');

        $error = isset($this->CRUD['index']) ? (
            isset($this->CRUD['index']['isDataTable']) ? (
                $this->CRUD['index']['isDataTable'] ? (
                    $this->CRUD['index']['isServerSide'] ? false : true
                ) : true
            ) : true
        ) : true;

        if ($error) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forMethodNotFound("{$this->_reflectionClass->getName()}::data");
        }

        $action = array_merge([
            'update' => true,
            'delete' => true,
        ], $this->CRUD['action'] ?? []);

        unset($action['insert']);

        if (isset($this->CRUD['permission'])) {
            $permissionToCheck = [];
            if ($action['update']) {
                $permissionToCheck[] = 'U';
            }

            if ($action['delete']) {
                $permissionToCheck[] = 'D';
            }

            $permission = $this->user->getPermission($this->CRUD['permission'], $permissionToCheck);

            if ($action['update']) {
                $action['update'] = $permission->U;
            }

            if ($action['delete']) {
                $action['delete'] = $permission->D;
            }
        }

        $table = null;

        /** @var \CI4Xpander\Model */
        $model = $this->CRUD['model'] ?? null;

        if (!is_null($model)) {
            if (!is_a($model, \CI4Xpander\Model::class)) {
                $model = $model::create();
            }
            $table = $model->getTable();
        }

        $columns = $this->CRUD['index']['columns'];

        $query = null;
        if (isset($this->CRUD['index']['query'])) {
            $query = $this->CRUD['index']['query'];

            if (is_callable($query)) {
                $query = $query(\Config\Database::connect(), $model);
            }
        } else {
            if (!is_null($model)) {
                $query = $model->builder();
            }
        }

        $draw = $this->request->getGet('draw');
        $columnsGet = $this->request->getGet('columns');
        $order = $this->request->getGet('order');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = preg_replace('/\s+/', '%', $this->request->getGet('search')) ?? '';

        /** @var \CodeIgniter\Database\BaseBuilder */
        $data = \Config\Database::connect()->table('ci4x_dashboard_data_temporary_table');

        /** @var \CodeIgniter\Database\BaseBuilder */
        $recordsFiltered = \Config\Database::connect()->table('ci4x_dashboard_data_temporary_table');

        if (!is_string($query)) {
            $compiledQuery = $query->getCompiledSelect();
        } else {
            $compiledQuery = $query;
        }

        $data->from("({$compiledQuery}) ci4x_dashboard_data_temporary_table", true);
        $recordsFiltered->from("({$compiledQuery}) ci4x_dashboard_data_temporary_table", true);

        $data->select("*, '' AS action", false);
        $recordsTotal = \Config\Database::connect()->table('ci4x_dashboard_data_temporary_table')->from("({$compiledQuery}) ci4x_dashboard_data_temporary_table", true);

        if (isset($search)) {
            if (isset($search['value'])) {
                if (!empty($search['value'])) {
                    if (isset($columnsGet)) {
                        if (is_array($columnsGet)) {
                            $data->groupStart();
                            $recordsFiltered->groupStart();
                            $i = 0;
                            foreach ($columnsGet as $column) {
                                if ($column['searchable'] == 'true') {
                                    $c = $columns[$column['data']];

                                    $searchValue = trim(preg_replace('/\s+/', '%', $search['value']));

                                    if (is_array($c)) {
                                        if (isset($c['value'])) {
                                            if (is_array($c['value'])) {
                                                if ($i == 0) {
                                                    $data->groupStart();
                                                    $recordsFiltered->groupStart();
                                                } else {
                                                    $data->orGroupStart();
                                                    $recordsFiltered->orGroupStart();
                                                }
                                                $j = 0;
                                                foreach ($c['value'] as $cKey => $cValue) {
                                                    if (is_numeric($cKey)) {
                                                        $fToS = $cValue;
                                                    } else {
                                                        $fToS = $cKey;
                                                    }

                                                    if ($j == 0) {
                                                        $data->like("{$fToS}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                        $recordsFiltered->like("{$fToS}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                    } else {
                                                        $data->orLike("{$fToS}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                        $recordsFiltered->orLike("{$fToS}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                    }

                                                    $j++;
                                                }
                                                $data->groupEnd();
                                                $recordsFiltered->groupEnd();
                                            } else {
                                                if ($i == 0) {
                                                    $data->like("{$c['value']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                    $recordsFiltered->like("{$c['value']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                } else {
                                                    $data->orLike("{$c['value']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                    $recordsFiltered->orLike("{$c['value']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                }
                                            }
                                        } else {
                                            if ($i == 0) {
                                                $data->like("{$column['data']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                $recordsFiltered->like("{$column['data']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                            } else {
                                                $data->orLike("{$column['data']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                $recordsFiltered->orLike("{$column['data']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                            }
                                        }
                                    } else {
                                        if ($i == 0) {
                                            $data->like("{$column['data']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                            $recordsFiltered->like("{$column['data']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                        } else {
                                            $data->orLike("{$column['data']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                            $recordsFiltered->orLike("{$column['data']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                        }
                                    }

                                    $i++;
                                }
                            }

                            $data->groupEnd();
                            $recordsFiltered->groupEnd();
                        }
                    }
                }
            }
        }

        if (isset($columnsGet)) {
            if (is_array($columnsGet)) {
                foreach ($columnsGet as $column) {
                    if ($column['searchable'] == 'true') {
                        if (!empty($column['search']['value'])) {
                            $c = $columns[$column['data']];

                            $searchValue = trim(preg_replace('/\s+/', '%', $column['search']['value']));

                            if (is_array($c)) {
                                if (isset($c['value'])) {
                                    if (is_array($c['value'])) {
                                        $data->groupStart();
                                        $recordsFiltered->groupStart();
                                        $i = 0;
                                        foreach ($c['value'] as $cKey => $cValue) {
                                            if (is_numeric($cKey)) {
                                                $fToS = $cValue;
                                            } else {
                                                $fToS = $cKey;
                                            }

                                            if ($i == 0) {
                                                $data->like("{$fToS}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                $recordsFiltered->like("{$fToS}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                            } else {
                                                $data->orLike("{$fToS}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                                $recordsFiltered->orLike("{$fToS}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                            }

                                            $i++;
                                        }
                                        $data->groupEnd();
                                        $recordsFiltered->groupEnd();
                                    } elseif (is_callable($c['value'])) {
                                        $data->like("{$column['data']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                        $recordsFiltered->like("{$column['data']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                    } else {
                                        $data->like("{$c['value']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                        $recordsFiltered->like("{$c['value']}::TEXT", \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                    }
                                } else {
                                    $data->like($column['data'] . '::TEXT', \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                    $recordsFiltered->like($column['data'] . '::TEXT', \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                }
                            } else {
                                $data->like($column['data'] . '::TEXT', \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                                $recordsFiltered->like($column['data'] . '::TEXT', \Config\Database::connect()->escape("%{$searchValue}%"), 'none', false, true);
                            }
                        }
                    }
                }
            }
        }

        if (isset($order)) {
            if (is_array($order)) {
                foreach ($order as $columnOrder) {
                    if (isset($columnsGet)) {
                        if ($columnsGet[intval($columnOrder['column'])]['orderable'] == 'true') {
                            $colKey = $columnsGet[intval($columnOrder['column'])]['data'];
                            if (is_array($columns[$colKey])) {
                                if (isset($columns[$colKey]['value'])) {
                                    foreach ($columns[$colKey]['value'] as $field => $name) {
                                        $data->orderBy(is_string($field) ? $field : $name, $columnOrder['dir']);
                                    }
                                }
                            } else {
                                $data->orderBy($columnsGet[intval($columnOrder['column'])]['data'], $columnOrder['dir']);
                            }
                        }
                    }
                }
            }
        }

        if (isset($start)) {
            $data->offset(intval($start));
        }

        if (isset($length)) {
            $data->limit(intval($length));
        }

        $result = $data->get()->getResult();
        if ($this->CRUD['index']['isMapResultServerSide'] ?? false) {
            $tempResult = $result;
            $result = [];
            foreach ($tempResult as $value) {
                $row = new \stdClass;
                foreach ($columns as $field => $column) {
                    $row->{$field} = CRUD::renderField($value, $field, $column);
                }

                if ($action['update']) {
                    $row->update = \CI4Xpander_AdminLTE\View\Component\Button::create(\CI4Xpander_AdminLTE\View\Component\Button\Data::create([
                        'text' => 'Update',
                        'isBlock' => false,
                        'type' => 'warning',
                        'style' => 'warning',
                        'isLink' => true,
                        'url' => "{$this->CRUD['base_url']}/update/{$value->id}"
                    ]))->render();
                }

                if ($action['delete']) {
                    $row->delete = \CI4Xpander_AdminLTE\View\Component\Button::create(\CI4Xpander_AdminLTE\View\Component\Button\Data::create([
                        'text' => 'Delete',
                        'isBlock' => false,
                        'type' => 'button',
                        'style' => 'danger',
                        'isLink' => false,
                        'attributes' => [
                            'data-toggle' => 'modal',
                            'data-target' => '#modalDelete',
                            'data-id' => $value->id
                        ]
                        // 'url' => $this->CRUD['base_url']
                    ]))->render();
                }

                $result[] = $row;
            }
        }

        return $this->response->setJSON([
            'draw' => isset($draw) ? intval($draw) : 0,
            'recordsTotal' => $recordsTotal->countAllResults(),
            'recordsFiltered' => $recordsFiltered->countAllResults(),
            'data' => $result
        ]);
    }

    public function update($id = 0)
    {
        $this->_checkCRUD('update');

        return $this->_render(function () use ($id) {
            $table = null;

            /** @var \CI4Xpander\Model */
            $model = $this->CRUD['model'] ?? null;

            if (!is_null($model)) {
                if (!is_a($model, \CI4Xpander\Model::class)) {
                    $model = $model::create();
                }
                $table = $model->getTable();
            }

            $query = null;
            if (isset($this->CRUD['index']['query'])) {
                $query = $this->CRUD['index']['query'];

                if (is_callable($query)) {
                    $query = $query(\Config\Database::connect(), $model);
                }
            } else {
                if (!is_null($model)) {
                    $query = $model->builder();
                }
            }

            $query->where("{$table}.id", $id);

            $item = $query->get()->getRow();

            if (is_null($item)) {
                throw new PageNotFoundException();
            }

            $_action = $this->_action($item);
            if (!is_null($_action)) {
                return $_action;
            }

            $this->view->data->page->title = lang('CI4Xpander_Dashboard.update.page.title', [
                ($this->view->data->page->title ?: $this->name)
            ]);

            if (isset($this->CRUD['form'])) {
                if (isset($this->CRUD['update']['input'])) {
                    foreach ($this->CRUD['form']['input'] as $inputName => $input) {
                        if (!in_array($inputName, $this->CRUD['update']['input'])) {
                            unset($this->CRUD['form']['input'][$inputName]);
                        }
                    }
                }

                foreach ($this->CRUD['form']['input'] as $inputName => $input) {
                    if (StaticStringy::endsWith($inputName, '[]')) {
                        $inputName = StaticStringy::removeRight($inputName, '[]');
                    }

                    if (isset($item->{$inputName})) {
                        $valueName = 'value';
                        if (in_array($input['type'], [
                            Type::SELECT, Type::DROPDOWN_AUTOCOMPLETE, Type::DROPDOWN
                        ])) {
                            $valueName = 'selected';
                        } elseif (in_array($input['type'], [
                            Type::CHECKBOX, Type::CHECKBOX_SINGLE, Type::RADIO
                        ])) {
                            $valueName = 'checked';
                        }

                        $dataTypeFromDatabase = $input['dataTypeFromDatabase'] ?? 'raw';

                        if (in_array($input['type'], [
                            Type::SELECT, Type::DROPDOWN_AUTOCOMPLETE, Type::DROPDOWN
                        ])) {
                            $isAjax = isset($input['ajax']);
                            $isMultipleValue = $input['multipleValue'] ?? false;
                            if ($isMultipleValue) {
                                $options = [];
                                $optionsSelected = [];

                                if ($dataTypeFromDatabase == 'json') {
                                    $decodedData = json_decode($item->{$inputName});
                                    foreach ($decodedData as $j) {
                                        $options[is_object($j) ? $j->id : $j['id']] = is_object($j) ? ($j->label ?? $j->name) : ($j['label'] ?? $j['name']);
                                        $optionsSelected[] = is_object($j) ? $j->id : $j['id'];
                                    }
                                } elseif ($dataTypeFromDatabase == 'column_id_pair') {
                                    $n = $inputName . '_id';
                                    $i = 0;
                                    $decodedName = json_decode($item->{$inputName}, true);
                                    $decodedId = json_decode($item->{$n}, true);
                                    foreach ($decodedName as $j) {
                                        $options[$decodedId[$i]] = $j;
                                        $optionsSelected[] = $decodedId[$i];
                                        $i++;
                                    }
                                } else {
                                    $options = [
                                        $item->{$inputName} => $item->{$inputName}
                                    ];
                                    $optionsSelected[] = $item->{$inputName};
                                }

                                $this->CRUD['form']['input']["{$inputName}[]"]['options'] = $options;
                                $this->CRUD['form']['input']["{$inputName}[]"][$valueName] = $optionsSelected;
                            } else {
                                $options = [];
                                if (isset($input['options'])) {
                                    $op = $input['options'];
                                    if (is_callable($op)) {
                                        $op = $op();
                                    }

                                    $options = $op;
                                }

                                if ($dataTypeFromDatabase == 'json') {
                                    $decodedData = json_decode($item->{$inputName});

                                    if (!array_key_exists(is_object($decodedData) ? $decodedData->id : $decodedData['id'], $options)) {
                                        $options[is_object($decodedData) ? $decodedData->id : $decodedData['id']] = is_object($decodedData) ? ($decodedData->label ?? $decodedData->name) : ($decodedData['label'] ?? $decodedData['name']);
                                    }
                                } elseif ($dataTypeFromDatabase == 'column_id_pair') {
                                    $n = $inputName . '_id';

                                    if (!array_key_exists($item->{$n}, $options)) {
                                        $options[$item->{$n}] = $item->{$inputName};
                                    }
                                } else {
                                    if (!array_key_exists($item->{$inputName}, $options)) {
                                        $options[$item->{$inputName}] = $item->{$inputName};
                                    }
                                }

                                $this->CRUD['form']['input'][$inputName]['options'] = $options;
                                $this->CRUD['form']['input'][$inputName][$valueName] = $item->{$inputName};
                            }
                        } else {
                            $this->CRUD['form']['input'][$inputName][$valueName] = $item->{$inputName};
                        }
                    }
                }

                $form = \CI4Xpander_AdminLTE\View\Component\Form::create();
                $form->action = $this->CRUD['base_url'] . "/update/{$item->id}";
                $form->method = 'PUT';
                $form->hidden = [
                    '_action' => 'update'
                ];
                $form->input = $this->CRUD['form']['input'] ?? [];

                if (isset($this->CRUD['form']['script'])) {
                    $this->CRUD['form']['script']['data']['ci4x']['crud']['enable'] = true;
                    $this->CRUD['form']['script']['data']['ci4x']['crud']['page'] = 'update';
                    $this->CRUD['form']['script']['data']['ci4x']['crud']['item'] = $item;
                    $form->script = $this->CRUD['form']['script'];
                }

                $form->request = $this->request;
                $form->validator = $this->validator;

                $formBox = \CI4Xpander_AdminLTE\View\Component\Box::create(\CI4Xpander_AdminLTE\View\Component\Box\Data::create([
                    'body' => $form->render()
                ]));

                $addButton = \CI4Xpander_AdminLTE\View\Component\Button::create(\CI4Xpander_AdminLTE\View\Component\Button\Data::create([
                    'text' => lang('CI4Xpander_Dashboard.general.cancel'),
                    'isBlock' => true,
                    'type' => 'danger',
                    'isLink' => true,
                    'url' => $this->CRUD['base_url']
                ]));

                $formBox->data->head->tool = $addButton->render();

                $this->view->data->template->content = \Config\Services::dashboardMessage()->render() . $formBox->render();
            }

            return $this->view->render();

        });
    }

    public function delete()
    {
        $this->_checkCRUD('delete');
        
        if ($this->validate([
            'id' => 'required|is_natural_no_zero'
        ])) {
            $data = \CI4Xpander\Helpers\Input::filter($this->request->getPost());

            $id = $data['id'];

            $table = null;

            /** @var \CI4Xpander\Model */
            $model = $this->CRUD['model'] ?? null;

            if (!is_null($model)) {
                if (!is_a($model, \CI4Xpander\Model::class)) {
                    $model = $model::create();
                }
                $table = $model->getTable();
            }

            /**
             * @var \CodeIgniter\Database\BaseBuilder
             */
            $query = null;
            if (isset($this->CRUD['index']['query'])) {
                $query = $this->CRUD['index']['query'];

                if (is_callable($query)) {
                    $query = $query(\Config\Database::connect(), $model);
                }
            } else {
                if (!is_null($model)) {
                    $query = $model->builder();
                }
            }

            $query->where("{$table}.id", $id);

            $item = $query->get()->getRow();

            if (!is_null($item)) {
                $_action = $this->_action($item);
                if (!is_null($_action)) {
                    return $_action;
                }

                return redirect()->to($this->CRUD['base_url']);
            } else {
                \Config\Services::dashboardMessage()->setType(\CI4Xpander_Dashboard\Helpers\Message::DANGER)->setValue('Item not found');
            }
        } else {
            \Config\Services::dashboardMessage()->setType(\CI4Xpander_Dashboard\Helpers\Message::DANGER)->setValue($this->validation->listErrors());
        }
    }

    public function create()
    {
        $this->_checkCRUD('create');

        return $this->_render(function () {
            $_action = $this->_action();
            if (!is_null($_action)) {
                return $_action;
            }

            $this->view->data->page->title = lang('CI4Xpander_Dashboard.create.page.title', [
                ($this->view->data->page->title ?: $this->name)
            ]);

            if (isset($this->CRUD['form'])) {
                $form = \CI4Xpander_AdminLTE\View\Component\Form::create();
                $form->action = $this->CRUD['base_url'] . '/create';
                $form->hidden = [
                    '_action' => 'create'
                ];
                $form->isMultipart = $this->CRUD['form']['isMultipart'] ?? false;
                $form->input = $this->CRUD['form']['input'] ?? [];
                $form->script = $this->CRUD['form']['script'] ?? null;
                $form->request = $this->request;
                $form->validator = $this->validator;

                $formBox = \CI4Xpander_AdminLTE\View\Component\Box::create(\CI4Xpander_AdminLTE\View\Component\Box\Data::create([
                    'body' => $form->render()
                ]));

                $addButton = \CI4Xpander_AdminLTE\View\Component\Button::create(\CI4Xpander_AdminLTE\View\Component\Button\Data::create([
                    'text' => lang('CI4Xpander_Dashboard.general.cancel'),
                    'isBlock' => true,
                    'type' => 'danger',
                    'isLink' => true,
                    'url' => $this->CRUD['base_url']
                ]));

                $formBox->data->head->tool = $addButton->render();

                $this->view->data->template->content = \Config\Services::dashboardMessage()->render() . $formBox->render();
            }

            return $this->view->render();
        });
    }

    protected function _checkCRUD($method = '')
    {
        if (!isset($this->CRUD['enable'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forMethodNotFound("{$this->_reflectionClass->getName()}::{$method}");
        }

        if (!$this->CRUD['enable']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forMethodNotFound("{$this->_reflectionClass->getName()}::{$method}");
        }
        
        if (!isset($this->CRUD['model'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forMethodNotFound("{$this->_reflectionClass->getName()}::{$method}");
        }

        if (isset($this->CRUD['permission'])) {
            if (!$this->user->hasPermission($this->CRUD['permission'], $this->permissionRuleSet[$method])) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forMethodNotFound("{$this->_reflectionClass->getName()}::{$method}");
            }
        }
    }

    protected function _actionTransaction($function = null, $action = '', $id = 0, $manual = false)
    {
        if (isset($this->CRUD['form'])) {
            if (!is_null($function)) {
                if (is_callable($function)) {
                    $databaseConnection = \Config\Database::connect();

                    if ($manual) {
                        $databaseConnection->transBegin();
                    } else {
                        $databaseConnection->transStart();
                    }

                    $function();

                    if ($manual) {

                    } else {
                        $databaseConnection->transComplete();
                    }

                    if ($databaseConnection->transStatus()) {
                        if ($manual) {
                            $databaseConnection->transCommit();
                        }

                        return redirect()->to($this->CRUD['base_url'] . ($action == 'update' ? "/update/{$id}" : ''))->with('message', \CI4Xpander_Dashboard\Helpers\Message::create(
                                \CI4Xpander_Dashboard\Helpers\Message::SUCCESS,
                                'Success'
                            )->render()
                        );
                    } else {
                        if ($manual) {
                            $databaseConnection->transRollback();
                        }

                        $error = $databaseConnection->error();

                        \Config\Services::dashboardMessage()->setType(\CI4Xpander_Dashboard\Helpers\Message::DANGER)->setValue("Error:<br/>{$error['message']} ({$error['code']})");
                    }
                }
            }
        }

        return null;
    }
}
