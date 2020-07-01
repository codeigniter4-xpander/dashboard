<?php namespace CI4Xpander_Dashboard;

/**
 * @property \CI4Xpander_Dashboard\View $view
 */
class Controller extends \CI4Xpander\Controller
{
    protected $name = '';

    /**
     * @var \CI4Xpander_Dashboard\Entities\User $user
     */
    protected $user;

    protected $CRUD = [
        'enable' => false,
        'base_url' => ''
    ];

    protected function _init()
    {
        parent::_init();

        $this->user = \Config\Services::session()->get('user');
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
            ->findAll();

        $this->view->data->template->menu->items = $this->_buildMenuTree($grantedMenu);
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

                $box = \CI4Xpander_AdminLTE\View\Component\Box::create();

                $table = null;
                if (isset($this->CRUD['index']['isDataTable'])) {
                    if ($this->CRUD['index']['isDataTable']) {
                        $ID = preg_replace("/[^a-zA-Z0-9]/", "", $this->name);

                        $table = \CI4Xpander_AdminLTE\View\Component\Table::create();
                        $table->data->isDataTable = true;
                        $table->data->id = $ID;
                        $table->data->columns = $this->CRUD['index']['columns'];
                        $table->data->rows = $this->CRUD['index']['query']->get()->getResult();

                        \Config\Services::viewScript()->add(view('CI4Xpander_AdminLTE\Views\Script\DataTable', [
                            'id' => $ID,
                            'isServerSide' => $this->CRUD['index']['isServerSide'],
                            'columns' => $this->CRUD['index']['columns']
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

                $this->view->data->template->content = $box;
            }

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

        $columns = $this->CRUD['index']['columns'];
        $query = $this->CRUD['index']['query']->getCompiledSelect();

        // if (!is_string($query)) {
        //     $query = $query();
        //     if (!is_string($query)) {
        //         $query = $query->getCompiledSelect();
        //     }
        // }

        $draw = $this->request->getGet('draw');
        $columnsGet = $this->request->getGet('columns');
        $order = $this->request->getGet('order');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = $this->request->getGet('search');

        /** @var \CodeIgniter\Database\BaseBuilder */
        $data = \Config\Database::connect()->table('q');

        /** @var \CodeIgniter\Database\BaseBuilder */
        $recordsFiltered = \Config\Database::connect()->table('q');

        $data->from("({$query}) q", true);
        $recordsFiltered->from("({$query}) q", true);

        $data->select("*, '' AS action", false);
        $recordsTotal = \Config\Database::connect()->table('q')->from("({$query}) q", true);

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
                                                        $data->like("{$fToS}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                        $recordsFiltered->like("{$fToS}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                    } else {
                                                        $data->orLike("{$fToS}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                        $recordsFiltered->orLike("{$fToS}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                    }

                                                    $j++;
                                                }
                                                $data->groupEnd();
                                                $recordsFiltered->groupEnd();
                                            } else {
                                                if ($i == 0) {
                                                    $data->like("{$c['value']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                    $recordsFiltered->like("{$c['value']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                } else {
                                                    $data->orLike("{$c['value']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                    $recordsFiltered->orLike("{$c['value']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                }
                                            }
                                        } else {
                                            if ($i == 0) {
                                                $data->like("{$column['data']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                $recordsFiltered->like("{$column['data']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                            } else {
                                                $data->orLike("{$column['data']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                $recordsFiltered->orLike("{$column['data']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                            }
                                        }
                                    } else {
                                        if ($i == 0) {
                                            $data->like("{$column['data']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                            $recordsFiltered->like("{$column['data']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                        } else {
                                            $data->orLike("{$column['data']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                            $recordsFiltered->orLike("{$column['data']}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
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
                                                $data->like("{$fToS}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                $recordsFiltered->like("{$fToS}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                            } else {
                                                $data->orLike("{$fToS}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                                $recordsFiltered->orLike("{$fToS}::TEXT", $this->db->escape("%{$search['value']}%"), 'none', false, true);
                                            }

                                            $i++;
                                        }
                                        $data->groupEnd();
                                        $recordsFiltered->groupEnd();
                                    } else {
                                        $data->like("{$c['value']}::TEXT", $this->db->escape("%{$column['search']['value']}%"), 'none', false, true);
                                        $recordsFiltered->like("{$c['value']}::TEXT", $this->db->escape("%{$column['search']['value']}%"), 'none', false, true);
                                    }
                                } else {
                                    $data->like($column['data'] . '::TEXT', $this->db->escape("%{$column['search']['value']}%"), 'none', false, true);
                                    $recordsFiltered->like($column['data'] . '::TEXT', $this->db->escape("%{$column['search']['value']}%"), 'none', false, true);
                                }
                            } else {
                                $data->like($column['data'] . '::TEXT', $this->db->escape("%{$column['search']['value']}%"), 'none', false, true);
                                $recordsFiltered->like($column['data'] . '::TEXT', $this->db->escape("%{$column['search']['value']}%"), 'none', false, true);
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
            // $columns['action'] = [
            //     'label' => '',
            //     'searchable' => false,
            //     'orderable' => false
            // ];

            // $rowActions = [
            //     'detail' => 'detail',
            //     'update' => 'update',
            //     'delete' => 'delete'
            // ];

            // if (isset($this->config['crud']['detail'])) {
            //     if (isset($this->config['crud']['detail']['enable'])) {
            //         if (!$this->config['crud']['detail']['enable']) {
            //             unset($rowActions['detail']);
            //         }
            //     }
            // }

            // if (isset($this->config['crud']['update'])) {
            //     if (isset($this->config['crud']['update']['enable'])) {
            //         if (!$this->config['crud']['update']['enable']) {
            //             unset($rowActions['update']);
            //         }
            //     }
            // }

            // if (isset($this->config['crud']['delete'])) {
            //     if (isset($this->config['crud']['delete']['enable'])) {
            //         if (!$this->config['crud']['delete']['enable']) {
            //             unset($rowActions['delete']);
            //         }
            //     }
            // }

            // if (isset($this->config['crud']['index']['rowActions'])) {
            //     $x = $this->config['crud']['index']['rowActions'];
            //     $rowActions = array_merge($rowActions, (array) $x);
            // }

            $tempResult = $result;
            $result = [];
            foreach ($tempResult as $value) {
                $row = new \stdClass;
                foreach ($columns as $field => $column) {
                    $row->{$field} = $value->{$field};
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

    public function create()
    {
        $this->_checkCRUD('create');

        return $this->_render(function () {
            $this->view->data->page->title = lang('CI4Xpander_Dashboard.create.page.title', [
                ($this->view->data->page->title ?: $this->name)
            ]);

            if (isset($this->CRUD['form'])) {
                $form = \CI4Xpander_AdminLTE\View\Component\Form::create();
                $form->action = $this->CRUD['base_url'] . '/create';
                $form->hidden = [
                    '_action' => 'create'
                ];
                $form->input = $this->CRUD['form']['input'] ?? [];
                $form->request = $this->request;
                $form->validator = $this->validator;

                $formBox = \CI4Xpander_AdminLTE\View\Component\Box::create(\CI4Xpander_AdminLTE\View\Component\Box\Data::create([
                    'body' => $form
                ]));

                $addButton = \CI4Xpander_AdminLTE\View\Component\Button::create(\CI4Xpander_AdminLTE\View\Component\Button\Data::create([
                    'text' => lang('CI4Xpander_Dashboard.general.cancel'),
                    'isBlock' => true,
                    'type' => 'danger',
                    'isLink' => true,
                    'url' => $this->CRUD['base_url']
                ]));

                $formBox->data->head->tool = $addButton;

                $this->view->data->template->content = $formBox;
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
    }
}
