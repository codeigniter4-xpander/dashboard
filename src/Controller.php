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

    protected $isCRUD = false;

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
        if (!$this->isCRUD) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forMethodNotFound($this->_reflectionClass->getName() . "::index");
        }

        return $this->_render(function () {
            $this->view->data->page->title = $this->view->data->page->title ?: "{$this->name} List";

            return $this->view->render();
        });
    }
}