<?php namespace CI4Xpander_Dashboard\Helpers\Database\Table;

use Config\Database;

class Menu
{
    public static function create($dataMenu = [], $dataPermission = [], $CRUD = [], $parent = null, $trackable = [])
    {
        $date = date('Y-m-d H:i:s');
        $builder = Database::connect();

        $status = $builder->table('status')->where('code', 'active')->get()->getRow();

        $parentID = 0;
        if (!is_null($parent)) {
            $parentID = $builder->table('menu')->where('code', $parent)->get()->getRow()->id;
        }

        $trackable = array_merge([
            'created_at' => $date,
            'updated_at' => $date,
            'created_by' => 1,
            'updated_by' => 1,
        ], $trackable);

        $dataMenu = array_merge(
            [
                'parent_id' => $parentID,
                'icon' => 'fa fa-circle',
                'status_id' => $status->id,
                'level' => 1,
                'sequence_position' => 99,
                'type_id' => 1
            ],
            $trackable,
            $dataMenu
        );

        $builder->table('menu')->insert($dataMenu);
        
        $idMenu = $builder->insertID();

        $permission = $builder->table('permission')->where('code', $dataPermission['code'])->get()->getRow();

        if (is_null($permission)) {
            $dataPermission = array_merge(
                [
                    'status_id' => $status->id,
                ],
                $trackable,
                $dataPermission
            );
    
            $builder->table('permission')->insert($dataPermission);
    
            $idPermission = $builder->insertID();
        } else {
            $idPermission = $permission->id;
        }

        $builder->table('menu_permission')->insert(array_merge(
            [
                'status_id' => $status->id,
                'menu_id' => $idMenu,
                'permission_id' => $idPermission,
                'C' => false,
                'R' => false,
                'U' => false,
                'D' => false,
            ],
            $trackable,
            $CRUD,
        ));
    }

    public static function remove($code = '')
    {
        $builder = Database::connect();

        $builder->table('permission')
            ->whereIn('id', function (\CodeIgniter\Database\BaseBuilder $builder) use ($code) {
                return $builder
                    ->select('permission_id')
                    ->from('menu_permission')
                    ->join('menu', 'menu.id = menu_permission.menu_id')
                    ->where('menu.code', $code);
            })
            ->delete();

        $builder->table('menu_permission')
            ->where('menu_id', function (\CodeIgniter\Database\BaseBuilder $builder) use ($code) {
                return $builder
                    ->select('id')
                    ->from('menu')
                    ->where('code', $code);
            })
            ->delete();

        $builder->table('menu')->where('code', $code)->delete();
    }
}