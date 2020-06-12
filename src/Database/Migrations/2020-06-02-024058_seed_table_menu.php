<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class SeedTableMenu extends \CI4Xpander\Migration
{
	public function up()
	{
        $date = date('Y-m-d H:i:s');

        $this->db->transStart();

        $this->db->table('menu')->insertBatch([
            [
                'parent_id' => 0,
                'code' => 'dashboard',
                'name' => 'Dashboard',
                'description' => 'Dashboard',
                'url' => 'dashboard',
                'icon' => 'fa fa-tachometer',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1,
                'level' => 1,
                'sequence_position' => 0,
                'type_id' => 1
            ],
            [
                'parent_id' => 0,
                'code' => 'dashboardSetting',
                'name' => 'Setting',
                'description' => 'Dashboard Setting',
                'url' => 'dashboard/setting',
                'icon' => 'fa fa-wrench',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1,
                'level' => 1,
                'sequence_position' => 99,
                'type_id' => 1
            ],
            [
                'parent_id' => 2,
                'code' => 'dashboardSettingSite',
                'name' => 'Site',
                'description' => 'Site Setting',
                'url' => 'dashboard/setting/site',
                'icon' => 'fa fa-bars',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1,
                'level' => 2,
                'sequence_position' => 1,
                'type_id' => 1
            ],
            [
                'parent_id' => 2,
                'code' => 'dashboardSettingRoleAndPermission',
                'name' => 'Role & Permission',
                'description' => 'Dashboard Setting Role & Permission',
                'url' => 'dashboard/setting/role-and-permission',
                'icon' => 'fa fa-sitemap',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1,
                'level' => 2,
                'sequence_position' => 2,
                'type_id' => 1
            ],
            [
                'parent_id' => 2,
                'code' => 'dashboardSettingUser',
                'name' => 'User',
                'description' => 'User setting',
                'url' => 'dashboard/setting/user',
                'icon' => 'fa fa-user',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1,
                'level' => 2,
                'sequence_position' => 3,
                'type_id' => 1
            ],
            [
                'parent_id' => 2,
                'code' => 'dashboardSettingDatabase',
                'name' => 'Database',
                'description' => 'Database setting',
                'url' => 'dashboard/setting/database',
                'icon' => 'fa fa-database',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1,
                'level' => 2,
                'sequence_position' => 98,
                'type_id' => 1
            ],
            [
                'parent_id' => 6,
                'code' => 'dashboardSettingDatabasePanel',
                'name' => 'Panel',
                'description' => 'Database panel',
                'url' => 'dashboard/setting/database/panel',
                'icon' => 'fa fa-table',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1,
                'level' => 3,
                'sequence_position' => 1,
                'type_id' => 1
            ],
            [
                'parent_id' => 6,
                'code' => 'dashboardSettingDatabaseMigration',
                'name' => 'Migration',
                'description' => 'Database migration',
                'url' => 'dashboard/setting/database/migration',
                'icon' => 'fa fa-refresh',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1,
                'level' => 3,
                'sequence_position' => 2,
                'type_id' => 1
            ],
            [
                'parent_id' => 2,
                'code' => 'dashboardSettingProcess',
                'name' => 'Process',
                'description' => 'Process',
                'url' => 'dashboard/setting/process',
                'icon' => 'fa fa-spinner',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1,
                'level' => 2,
                'sequence_position' => 99,
                'type_id' => 1
            ]
        ]);

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->db->table('menu')->truncate();

        $this->db->transComplete();
	}
}
