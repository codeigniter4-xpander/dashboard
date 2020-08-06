<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting;

use \CI4Xpander\Helpers\Database\Builder;
use CI4Xpander\Helpers\Input;
use CI4Xpander_AdminLTE\View\Component\Form\Type;
use CI4Xpander_Dashboard\Models\User as ModelsUser;
use CI4Xpander_Dashboard\Models\Status;

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
            'index' => [
                'isDataTable' => true,
                'isServerSide' => true,
                'isMapResultServerSide' => true,
                'query' => \Config\Database::connect()->table('user')
                    ->select('user.*')
                    ->select("(
                        SELECT ARRAY_TO_JSON(ARRAY_AGG(" . Builder::protect('role.name') . "))
                        FROM " . Builder::protect('role') . "
                        JOIN " . Builder::protect('user_role') . " ON " . Builder::protect('user_role.role_id') . " = " . Builder::protect('role.id') . "
                        WHERE " . Builder::protect('user_role.user_id') . " = " . Builder::protect('user.id') . "
                    ) " . Builder::protect('roles'), false)
                    ->join('status user_status', 'user_status.id = user.status_id')
                    ->where('user.deleted_at', null)
                    ->where('user_status.code', 'active'),
                'columns' => [
                    'code' => 'Code',
                    'name' => 'Name',
                    'email' => 'Email',
                    'roles' => [
                        'label' => 'Roles',
                        'value' => function ($value, $row) {
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
                // 'script' => [
                //     'file' => 'CI4Xpander_Dashboard\Views\Script\Dashboard\Setting',
                // ],
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
                        'type' => Type::TEXT,
                        'label' => 'Password',
                    ],
                    'roles[]' => [
                        'type' => Type::DROPDOWN_AUTOCOMPLETE,
                        'label' => 'Roles',
                        'ajax' => [
                            'url' => base_url('dashboard/api/setting/role-and-permission/role'),
                        ],
                        'multipleValue' => true,
                    ],
                    // 'crudTemplate' => [
                    //     'type' => Type::CHECKBOX,
                    //     'label' => 'Role',
                    //     'options' => [
                    //         'create' => 'Create',
                    //         'read' => 'Read',
                    //         'update' => 'Update',
                    //         'delete' => 'Delete'
                    //     ],
                    //     'column' => 4,
                    //     'containerClass' => [
                    //         'hidden'
                    //     ],
                    //     'containerAttr' => [
                    //         'data-crud' => ''
                    //     ]
                    // ],
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
        return $this->_actionTransaction(function () {
            $data = Input::filter($this->request->getPost());

            $activeStatus = Status::create()->where('code', 'active')->first();

            ModelsUser::create()->insert([
                'code' => $data['code'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'status_id' => $activeStatus->id
            ]);

            // $roleRole = Role::create();
            // foreach ($data['roles'] as $role) {
            //     $roleRole->insert([
            //         'user_id',
            //         'role_id'
            //     ]);
            // }

        }, 'create');
    }

}
