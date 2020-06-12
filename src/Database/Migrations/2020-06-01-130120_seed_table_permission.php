<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class SeedTablePermission extends \CI4Xpander\Migration
{
	public function up()
	{
        $this->db->transStart();

        $date = date('Y-m-d H:i:s');

        $this->db->table('permission')->insertBatch([
            [
                'code' => 'login',
                'name' => 'Login',
                'description' => 'Login',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'dashboard',
                'name' => 'Dashboard',
                'description' => 'Dashboard',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'dashboardSetting',
                'name' => 'Dashboard/Setting',
                'description' => 'Dashboard/Setting',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'dashboardSettingSite',
                'name' => 'Dashboard/Setting/Site',
                'description' => 'Dashboard/Setting/Site',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'dashboardSettingRoleAndPermission',
                'name' => 'Dashboard/Setting/Role & Permission',
                'description' => 'Dashboard/Setting/Role & Permission',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'dashboardSettingUser',
                'name' => 'Dashboard/Setting/User',
                'description' => 'Dashboard/Setting/User',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'dashboardSettingDatabase',
                'name' => 'Dashboard/Setting/Database',
                'description' => 'Dashboard/Setting/Database',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'dashboardSettingDatabasePanel',
                'name' => 'Dashboard/Setting/Database/Panel',
                'description' => 'Dashboard/Setting/Database/Panel',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'dashboardSettingDatabaseMigration',
                'name' => 'Dashboard/Setting/Database/Migration',
                'description' => 'Dashboard/Setting/Database/Migration',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'dashboardSettingProcess',
                'name' => 'Dashboard/Setting/Process',
                'description' => 'Dashboard/Setting/Process',
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ]
        ]);

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->db->table('permission')->truncate();

        $this->db->transComplete();
	}
}
