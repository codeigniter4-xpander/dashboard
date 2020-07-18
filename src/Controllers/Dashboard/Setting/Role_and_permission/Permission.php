<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting\ROle_and_permission;

use \CI4Xpander\Helpers\Database\Builder;
use CI4Xpander\Helpers\Input;
use CI4Xpander_AdminLTE\View\Component\Form\Type;
use CI4Xpander_Dashboard\Models\Permission as ModelsPermission;
use CI4Xpander_Dashboard\Models\Status;

class Permission extends \CI4Xpander_Dashboard\Controller
{
    protected $name = 'Permission';

    protected function _init()
    {
        parent::_init();

        $this->CRUD = [
            'enable' => true,
            'base_url' => base_url('dashboard/setting/role-and-permission/permission'),
            'permission' => 'dashboardSettingPermission',
            'index' => [
                'isDataTable' => true,
                'isServerSide' => true,
                'isMapResultServerSide' => true,
                'query' => \Config\Database::connect()->table('permission')
                    ->select('permission.*')
                    ->join('status permission_status', 'permission_status.id = permission.status_id')
                    ->where('permission.deleted_at', null)
                    ->where('permission_status.code', 'active'),
                'columns' => [
                    'code' => 'Code',
                    'name' => 'Name',
                    'description' => 'Description',
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
                    'description' => [
                        'type' => Type::TEXT_AREA,
                        'label' => 'Description',
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
        return $this->_actionTransaction(function () {
            $data = Input::filter($this->request->getPost());

            $activeStatus = Status::create()->where('code', 'active')->first();

            ModelsPermission::create()->insert([
                'code' => $data['code'],
                'name' => $data['name'],
                'description' => $data['description'],
                'status_id' => $activeStatus->id
            ]);
        }, 'create');
    }
}
