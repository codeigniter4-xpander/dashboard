<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class Migration_seed_table_role extends \CI4Xpander\Migration
{
	public function up()
	{
        $this->db->transStart();

        \CI4Xpander_Dashboard\Helpers\Database\Table\Role::create([
            'code' => 'system',
            'name' => 'System',
            'description' => 'System',
            'level' => 0,
        ]);

        \CI4Xpander_Dashboard\Helpers\Database\Table\Role::create([
            'code' => 'developer',
            'name' => 'Developer',
            'description' => 'Developer',
            'level' => 1,
        ], [
            'dashboard' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSetting' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingMasterData' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingSite' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingRoleAndPermission' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingRole' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingPermission' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingUser' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingProcess' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingDatabase' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingDatabasePanel' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingDatabaseMigration' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ]
        ], 'system');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Role::create([
            'code' => 'administrator',
            'name' => 'Administrator',
            'description' => 'Administrator',
            'level' => 10,
        ], [
            'dashboard' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSetting' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingMasterData' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingSite' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingRoleAndPermission' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingRole' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingPermission' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
            'dashboardSettingUser' => [
                'C' => true, 'R' => true, 'U' => true, 'D' => true
            ],
        ], 'system');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Role::create([
            'code' => 'user',
            'name' => 'User',
            'description' => 'User',
            'level' => 90,
        ], [
            'login' => [
                'R' => true
            ],
            'dashboard' => [
                'C' => false, 'R' => true, 'U' => false, 'D' => false
            ],
        ], 'system');

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->db->table('role')->truncate();
        $this->db->table('role_permission')->truncate();

        $this->db->transComplete();
	}
}
