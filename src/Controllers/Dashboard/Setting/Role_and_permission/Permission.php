<?php namespace CI4Xpander_Dashboard\Controllers\Dashboard\Setting\Role_and_permission;

use CI4Xpander_AdminLTE\View\Component\Form\Type;

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
            'model' => \CI4Xpander_Dashboard\Models\Permission::class,
            'index' => [
                'isDataTable' => true,
                'isServerSide' => true,
                'isMapResultServerSide' => true,
                'query' => function (\CodeIgniter\Database\BaseConnection $builder, \CI4Xpander\Model $model) {
                    return $model
                        ->select('permission.*')
                        ->join('status permission_status', 'permission_status.id = permission.status_id')
                        ->where('permission.deleted_at', null)
                        ->where('permission_status.code', 'active');
                },
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
        if ($this->validate([
            'code' => 'required|is_unique[permission.code]',
            'name' => 'required',
        ])) {
            return $this->_actionTransaction(function () {
                $data = \CI4Xpander\Helpers\Input::filter($this->request->getPost());

                $activeStatus = \CI4Xpander_Dashboard\Models\Status::create()->where('code', 'active')->first();

                \CI4Xpander_Dashboard\Models\Permission::create()->insert([
                    'code' => $data['code'],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'status_id' => $activeStatus->id
                ]);
            }, 'create');
        } else {
            \Config\Services::dashboardMessage()->setType(\CI4Xpander_Dashboard\Helpers\Message::DANGER)->setValue('Form validation error');
        }
    }

    protected function _action_update($item = null)
    {
        if ($this->validate([
            'code' => "required|is_unique[permission.code,id,{$item->id}]",
            'name' => 'required'
        ])) {
            d($this->request->getPost());
        } else {
            \Config\Services::dashboardMessage()->setType(\CI4Xpander_Dashboard\Helpers\Message::DANGER)->setValue('Form validation error');
        }
    }
}
