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
        $this->_checkCRUD();

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
                if (isset($this->CRUD['useDataTable'])) {
                    if ($this->CRUD['useDataTable']) {
                        $table = \CI4Xpander_AdminLTE\View\Component\Table::create();
                        $table->data->isDataTable = true;
                    }
                }

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

                    $colTable = \CI4Xpander_AdminLTE\View\Component\Column::create();
                    $colTable->data->content = $table;
                    $rowTable = \CI4Xpander_AdminLTE\View\Component\Row::create();
                    $rowTable->data->content = $colTable;

                    $box->data->body = $rowTable . $rowPager;
                }

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
        $this->_checkCRUD();

        $error = isset($this->CRUD['index']) ? (
            isset($this->CRUD['index']['useDataTable']) ? (
                $this->CRUD['index']['useDataTable'] ? false : true
            ) : true
        ) : true;

        if ($error) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forMethodNotFound("{$this->_reflectionClass->getName()}::data");
        }
    }

    public function create()
    {
        $this->_checkCRUD();

        return $this->_render(function () {
            $this->view->data->page->title = lang('CI4Xpander_Dashboard.create.page.title', [
                ($this->view->data->page->title ?: $this->name)
            ]);

            if (isset($this->CRUD['form'])) {
                helper('form');

                $form = form_open();
                $form .= form_hidden('_action', 'create');

                foreach ($this->CRUD['form'] as $name => $input) {
                }

                $form .= form_close();

                $this->view->data->template->content = $form;
            }

            return $this->view->render();
        });
    }

    protected function _checkCRUD()
    {
        if (!isset($this->CRUD['enable'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forMethodNotFound("{$this->_reflectionClass->getName()}::create");
        }

        if (!$this->CRUD['enable']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forMethodNotFound("{$this->_reflectionClass->getName()}::create");
        }
    }
}
