<?php namespace CI4Xpander_Dashboard\Controllers;

use CI4Xpander_AdminLTE\View\Component\Box;
use CI4Xpander_AdminLTE\View\Component\Column;
use CI4Xpander_AdminLTE\View\Component\Row;

/**
 * @property \CI4Xpander_Dashboard\View $view
 */
class Dashboard extends \CI4Xpander\Controller
{
    protected $isCRUD = false;

    protected function _buildMenuTree($items, $parent = null)
    {
        $result = [];

        foreach ($items as $item) {
            if (!is_null($parent)) {
                if ($item->parent_id == $parent->id) {
                    $m = \CI4Xpander_AdminLTE\View\Component\Menu\Item\Data::create([
                        'name' => $item->name,
                        'url' => $item->url,
                        'isActive' => strpos(uri_string(), $item->url) !== false,
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
                        'isActive' => strpos(uri_string(), $item->url) !== false,
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

    protected function _init()
    {
        $this->view->data->user->name = \Config\Services::session()->get('user')->name;

        $grantedMenu = \CI4Xpander_Dashboard\Models\Menu::create()
            ->select('menu.*')
            ->join('menu_type mt', 'mt.id = menu.type_id')
            ->join('menu_permission mp', 'mp.menu_id = menu.id')
            ->join('permission p', 'p.id = mp.permission_id')
            ->join('role_permission rp', 'rp.permission_id = p.id')
            ->join('role r', 'r.id = rp.role_id')
            ->join('user_role ur', 'ur.role_id = r.id')
            ->where('mt.code', 'dashboard')
            ->where('ur.user_id', \Config\Services::session()->get('user')->id)
            ->orderBy('menu.level', 'ASC')
            ->orderBy('menu.sequence_position', 'ASC')
            ->findAll();

        $this->view->data->template->menu->items = $this->_buildMenuTree($grantedMenu);
    }

    public function index()
    {
        return $this->_render(function () {
            $box = Box::create();
            $box->data->head->title = 'Daftar';
            $box->data->body = 'DAFTAR';

            $col = Column::create();
            $col->data->content = $box;

            $row = Row::create();
            $row->data->content = $col;

            $this->view->data->template->content = $row;
            return $this->view->render();
        });
    }
}
