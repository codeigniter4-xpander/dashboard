<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting\Role_and_permission;

use \CI4Xpander\Helpers\Database\Builder;
use CI4Xpander\Helpers\Input;
use CI4Xpander_AdminLTE\View\Component\Form\Type;
use CI4Xpander_Dashboard\Models\Role as ModelsRole;
use CI4Xpander_Dashboard\Models\Role\Permission;
use CI4Xpander_Dashboard\Models\Status;

class Role extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'Role';

    protected function _init()
    {
        parent::_init();

        $this->CRUD = [
            'enable' => true,
            'base_url' => base_url('dashboard/setting/role-and-permission/role'),
            'permission' => 'dashboardSettingRole',
            'model' => \CI4Xpander_Dashboard\Models\Role::class,
            'index' => [
                'isDataTable' => true,
                'isServerSide' => true,
                'isMapResultServerSide' => true,
                'query' => function (\CodeIgniter\Database\BaseConnection $builder, \CI4Xpander\Model $model) {
                    return $model->builder()
                        ->select('role.*')
                        ->select(Builder::subQuery(
                            $builder->table('p')
                                ->select('ARRAY_TO_JSON(
                                    ARRAY_AGG(
                                        ROW_TO_JSON(' . Builder::protect('p') . ')
                                    )
                                )', false)
                                ->from(
                                    Builder::subQuery(
                                        $builder->table('permission')
                                            ->select('permission.*')
                                            ->select('role_permission.C')
                                            ->select('role_permission.R')
                                            ->select('role_permission.U')
                                            ->select('role_permission.D')
                                            ->join('role_permission', 'role_permission.permission_id = permission.id')
                                            ->join('status permission_status', 'permission_status.id = permission.status_id')
                                            ->join('status role_permission_status', 'role_permission_status.id = role_permission.status_id')
                                            ->where('role_permission.role_id = role.id')
                                            ->where('permission.deleted_at', null)
                                            ->where('role_permission.deleted_at', null)
                                            ->where('permission_status.code', 'active')
                                            ->where('role_permission_status.code', 'active'),
                                        'p'
                                    ),
                                    true
                                ),
                            'permissions'
                        ), false)
                        ->join('status role_status', 'role_status.id = role.status_id')
                        ->where('role.code !=', 'system')
                        ->where('role.deleted_at', null)
                        ->where('role_status.code', 'active');
                },
                'columns' => [
                    'code' => 'Code',
                    'name' => 'Name',
                    'level' => 'Level',
                    'permissions' => [
                        'label' => 'Permissions',
                        'value' => function ($value, $data) {
                            if (is_null($value) || empty($value)) {
                                return '';
                            }

                            $result = [];
                            $decodedValue = json_decode($value);
                            foreach ($decodedValue as $d) {
                                $result[] = is_object($d) ? ($d->label ?? $d->name) : ($d['label'] ?? $d['name']);
                            }

                            return implode(', ', $result);
                        },
                    ],
                ],
            ],
            'form' => [
                'script' => [
                    [
                        'file' => 'CI4Xpander_Dashboard\Views\Script\Dashboard\Setting\Role_and_permission\Role',
                    ]
                ],
                'input' => [
                    'code' => [
                        'type' => Type::TEXT,
                        'label' => 'Code',
                    ],
                    'name' => [
                        'type' => Type::TEXT,
                        'label' => 'Name',
                    ],
                    'level' => [
                        'type' => Type::TEXT,
                        'label' => 'Level',
                        'default' => 99,
                    ],
                    'description' => [
                        'type' => Type::TEXT_AREA,
                        'label' => 'Description',
                    ],
                    'permissions[]' => [
                        'type' => Type::DROPDOWN_AUTOCOMPLETE,
                        'label' => 'Permissions',
                        'ajax' => [
                            'url' => base_url('dashboard/ajax/setting/role-and-permission/permission'),
                        ],
                        'multipleValue' => true,
                        'dataTypeFromDatabase' => 'json'
                    ],
                    'crudTemplate' => [
                        'type' => Type::CHECKBOX,
                        'label' => 'Permission',
                        'options' => [
                            'create' => 'Create',
                            'read' => 'Read',
                            'update' => 'Update',
                            'delete' => 'Delete'
                        ],
                        'column' => 4,
                        'containerClass' => [
                            'hidden'
                        ],
                        'containerAttr' => [
                            'data-crud' => ''
                        ]
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
            'level' => 'required|is_natural_no_zero|less_than[100]'
        ])) {
            return $this->_actionTransaction(function () {
                $data = Input::filter($this->request->getPost());

                $statusActive = Status::create()->where('code', 'active')->first();

                $roleID = ModelsRole::create()->insert([
                    'code' => $data['code'],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'level' => $data['level'],
                    'status_id' => $statusActive->id
                ]);

                $rolePermission = Permission::create();
                foreach ($data['permissions'] as $permission) {
                    $rolePermission->insert([
                        'role_id',
                        'permission_id',
                        'C',
                        'R',
                        'U',
                        'D'
                    ]);
                }
            }, 'create');
        } else {
            \Config\Services::dashboardMessage()->setType(\CI4Xpander_Dashboard\Helpers\Message::DANGER)->setValue('Gagal menyimpan data. Mohon periksa data yang anda masukkan.');
        }
    }

    protected function _action_update($item = null)
    {
        if ($this->validate([
            'code' => 'required',
            'name' => 'required',
            'level' => 'required|is_natural_no_zero|less_than[100]'
        ])) {
            return $this->_actionTransaction(function () use ($item) {
                $data = Input::filter($this->request->getPost());
                ModelsRole::create()->update($item->id, $data);
            }, 'update', $item->id);
        }else {
            \Config\Services::dashboardMessage()->setType(\CI4Xpander_Dashboard\Helpers\Message::DANGER)->setValue('Gagal menyimpan data. Mohon periksa data yang anda masukkan.');
        }
    }

    protected function _action_delete($item = null)
    {
        return $this->_actionTransaction(function () use ($item) {
        ModelsRole::create()->delete($item->id);
        }, 'delete', $item->id);
    }

}
