<?php namespace CI4Xpander_Dashboard\Helpers\Database\Table;

use Config\Database;

class Role
{
    public static function create($dataRole = [], $permissions = [], $parent = null, $trackable = [])
    {
        $builder = Database::connect();

        $date = date('Y-m-d H:i:s');

        $trackable = array_merge([
            'created_at' => $date,
            'updated_at' => $date,
            'created_by' => 1,
            'updated_by' => 1
        ], $trackable);

        $status = $builder->table('status')->where('code', 'active')->get()->getRow();

        $parentID = 0;
        if (!is_null($parent)) {
            $parentID = $builder->table('role')->where('code', $parent)->get()->getRow()->id;
        }

        $dataRole = array_merge([
            'status_id' => $status->id,
            'level' => 0,
            'parent_id' => $parentID,
        ], $trackable, $dataRole);

        $builder->table('role')->insert($dataRole);

        $idRole = $builder->insertID();

        foreach ($permissions as $name => $permission) {
            $p = $builder->table('permission')->where('code', $name)->get()->getRow();
            if (!is_null($p)) {
                $builder->table('role_permission')->insert(array_merge(
                    [
                        'role_id' => $idRole,
                        'permission_id' => $p->id,
                        'status_id' => $status->id,
                        'C' => false,
                        'R' => false,
                        'U' => false,
                        'D' => false,
                    ],
                    $permission,
                    $trackable
                ));
            }
        }
    }
}