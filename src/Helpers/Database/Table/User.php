<?php namespace CI4Xpander_Dashboard\Helpers\Database\Table;

use Config\Database;

class User
{
    public static function create($dataUser = [], $roles = [], $permissions = [], $trackable = [])
    {
        $date = date('Y-m-d H:i:s');

        $builder = Database::connect();

        $status = $builder->table('status')->where('code', 'active')->get()->getRow();

        $trackable = array_merge([
            'created_at' => $date,
            'updated_at' => $date,
            'created_by' => 1,
            'deleted_by' => 1
        ], $trackable);

        $user = $builder->table('user')->where('code', $dataUser['code'])->get()->getRow();

        if (is_null($user)) {
            $builder->table('user')->insert(array_merge(
                [
                    'status_id' => $status->id,
                ],
                $trackable,
                $dataUser
            ));
    
            $idUser = $builder->insertID();
        } else {
            $idUser = $user->id;
        }

        foreach ($roles as $roleName) {
            $role = $builder->table('role')->where('code', $roleName)->get()->getRow();
            $builder->table('user_role')->insert(array_merge(
                [
                    'user_id' => $idUser,
                    'role_id' => $role->id,
                    'status_id' => $status->id
                ],
                $trackable
            ));
        }

        foreach ($permissions as $name => $permission) {
            $p = $builder->table('permission')->where('code', $name)->get()->getRow();
            if (!is_null($p)) {
                $builder->table('iser_permission')->insert(array_merge(
                    [
                        'user_id' => $idUser,
                        'permission_id' => $p->id,
                        'status_id' => $status->id,
                        'C' => false,
                        'R' => false,
                        'U' => false,
                        'D' => false,
                    ],
                    $trackable,
                    $permission
                ));
            }
        }
    }

    public static function remove($code = '')
    {
        $builder = Database::connect();

        $builder->table('user_permission')->where('user_id', function (\CodeIgniter\Database\BaseBuilder $builder) use ($code) {
            return $builder->select('id')->from('user')->where('code', $code);
        })->delete();

        $builder->table('user_role')->where('user_id', function (\CodeIgniter\Database\BaseBuilder $builder) use ($code) {
            return $builder->select('id')->from('user')->where('code', $code);
        })->delete();

        $builder->table('user')->where('code', $code)->delete();
    }
}