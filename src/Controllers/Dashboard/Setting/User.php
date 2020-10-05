<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting;

use CI4Xpander\Helpers\Database\Builder;
use CI4Xpander\Helpers\Input;
use CI4Xpander_AdminLTE\View\Component\Form\Type;
use CI4Xpander_Dashboard\Models\User as ModelsUser;
use CI4Xpander_Dashboard\Models\Status;
use CI4Xpander_Dashboard\Models\User\Role;

class User extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'User';

    protected function _init()
    {
        parent::_init();

        $this->CRUD = [
            'enable' => true,
            'base_url' => base_url('dashboard/setting/user'),
            'permission' => 'dashboardSettingUser',
            'model' => \CI4Xpander_Dashboard\Models\User::class,
            'index' => [
                'isDataTable' => true,
                'isServerSide' => true,
                'isMapResultServerSide' => true,
                'query' => function (\CodeIgniter\Database\BaseConnection $builder, \CI4Xpander\Model $model) {
                    return $model->builder()
                        ->select('user.*')
                        ->select(
                            Builder::subQuery(
                                $builder->table('role')
                                    ->select(
                                        'ARRAY_TO_JSON(ARRAY_AGG(ROW_TO_JSON(' . Builder::protect('role') . ')))',
                                        false
                                    )
                                    ->join('user_role', 'user_role.role_id = role.id')
                                    ->where(Builder::protect('user_role.user_id'), Builder::protect('user.id'), false),
                                'the_roles'
                            ),
                            false
                        )
                        ->select("(
                            SELECT ARRAY_TO_JSON(ARRAY_AGG(" . Builder::protect('role.name') . "))
                            FROM " . Builder::protect('role') . "
                            JOIN " . Builder::protect('user_role') . " ON " . Builder::protect('user_role.role_id') . " = " . Builder::protect('role.id') . "
                            WHERE " . Builder::protect('user_role.user_id') . " = " . Builder::protect('user.id') . "
                        ) " . Builder::protect('roles'), false)
                        ->select("(
                            SELECT ARRAY_TO_JSON(ARRAY_AGG(" . Builder::protect('role.id') . "))
                            FROM " . Builder::protect('role') . "
                            JOIN " . Builder::protect('user_role') . " ON " . Builder::protect('user_role.role_id') . " = " . Builder::protect('role.id') . "
                            WHERE " . Builder::protect('user_role.user_id') . " = " . Builder::protect('user.id') . "
                        ) " . Builder::protect('roles_id'), false)
                        ->join('status user_status', 'user_status.id = user.status_id')
                        ->where('user.code !=', 'system')
                        ->where('user.deleted_at', null)
                        ->where('user_status.code', 'active');
                },
                'columns' => [
                    'code' => 'Code',
                    'name' => 'Name',
                    'email' => 'Email',
                    'roles' => [
                        'label' => 'Roles',
                        'value' => function ($value, $row) {
                            if (is_null($value)){
                                return '';
                            }

                            $value = json_decode($value);
                            $view = '<ul>';
                            foreach ($value as $role) {
                                $view .= "<li>{$role}</li>";
                            }
                            return $view . '</ul>';
                        }
                    ]
                ],
            ],

            'form' => [
                'input' => [
                    'code' => [
                        'type' => Type::TEXT,
                        'label' => 'Code',
                    ],
                    'name' => [
                        'type' => Type::TEXT,
                        'label' => 'Name',
                    ],
                    'email' => [
                        'type' => Type::TEXT,
                        'label' => 'Email',
                    ],
                    'password' => [
                        'type' => Type::PASSWORD,
                        'label' => 'Password',
                    ],
                    'roles[]' => [
                        'type' => Type::DROPDOWN_AUTOCOMPLETE,
                        'label' => 'Roles',
                        'ajax' => [
                            'url' => base_url('dashboard/ajax/setting/role-and-permission/role?where_not=code.system&where=code.administrator'),
                        ],
                        'multipleValue' => true
                    ],
                    'action' => [
                        'type' => Type::BUTTON_GROUP,
                        'buttons' => [
                            'reset' => [
                                'type' => Type::BUTTON_RESET,
                                'label' => 'Reset',
                            ],
                            'simpan' => [
                                'type' => Type::BUTTON_SUBMIT,
                                'label' => 'Simpan',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function _action_create()
    {
        if ($this->validate([
            'code' => 'required',
            'name' => 'required',
            'password' => 'required',
            'roles' => 'required',
            'level' => 'required|is_natural_no_zero|less_than[100]'
        ])) {
            return $this->_actionTransaction(function () {
                $data = Input::filter($this->request->getPost());

                $activeStatus = Status::create()->where('code', 'active')->first();

                $iduser = ModelsUser::create()->insert([
                    'code' => $data['code'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => \Config\Services::hashPassword($data['password']),
                    'status_id' => $activeStatus->id
                ]);

                $modelUserRole = Role::create();

                foreach ($data['roles'] as $role) {
                    $modelUserRole->insert([
                        'user_id' => $iduser,
                        'role_id' => $role,
                        'status_id' => $activeStatus->id
                    ]);
                }
            }, 'create');
        }else {
            \Config\Services::dashboardMessage()->setType(\CI4Xpander_Dashboard\Helpers\Message::DANGER)->setValue('Gagal menyimpan data. Mohon periksa data yang anda masukkan.');
        }
    }

    protected function _action_update($item = null)
    {
        if ($this->validate([
            'code' => 'required',
            'name' => 'required',
            'roles' => 'required',
            'level' => 'required|is_natural_no_zero|less_than[100]'
        ])) {
            return $this->_actionTransaction(function () use ($item) {
                $data = Input::filter($this->request->getPost());
                ModelsUser::create()->update($item->id, $data);
            }, 'update', $item->id);
        }else {
            \Config\Services::dashboardMessage()->setType(\CI4Xpander_Dashboard\Helpers\Message::DANGER)->setValue('Gagal menyimpan data. Mohon periksa data yang anda masukkan.');
        }
    }

    protected function _action_delete($item = null)
    {
        return $this->_actionTransaction(function () use ($item) {
            ModelsUser::create()->delete($item->id);
        }, 'delete', $item->id);
    }
}
