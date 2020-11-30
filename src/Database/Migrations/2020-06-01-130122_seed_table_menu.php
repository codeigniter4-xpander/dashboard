<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class Migration_seed_table_menu extends \CI4Xpander\Migration
{
	public function up()
	{
        $this->db->transStart();

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboard',
            'name' => 'Dashboard',
            'description' => 'Dashboard',
            'url' => 'dashboard',
            'icon' => 'fa fa-tachometer',
            'level' => 1,
            'sequence_position' => 0,
            'type_id' => 1
        ], [
            'code' => 'dashboard',
            'name' => 'Dashboard',
            'description' => 'Dashboard',
        ], [
            'R' => true
        ]);

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSetting',
            'name' => 'Setting',
            'description' => 'Dashboard Setting',
            'url' => 'dashboard/setting',
            'icon' => 'fa fa-wrench',
            'level' => 1,
            'sequence_position' => 99,
            'type_id' => 1
        ], [
            'code' => 'dashboardSetting',
            'name' => 'Dashboard Setting',
            'description' => 'Dashboard Setting'
        ], [
            'R' => true,
        ]);

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSettingMasterData',
            'name' => 'Master Data',
            'description' => 'Master Data Setting',
            'url' => 'dashboard/setting/master-data',
            'level' => 2,
            'sequence_position' => 1,
            'type_id' => 1
        ], [
            'code' => 'dashboardSettingMasterData',
            'name' => 'Dashboard Setting Master Data',
            'description' => 'Dashboard Setting Master Data'
        ], [
            'R' => true
        ], 'dashboardSetting');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSettingSite',
            'name' => 'Site',
            'description' => 'Site Setting',
            'url' => 'dashboard/setting/site',
            'icon' => 'fa fa-bars',
            'level' => 2,
            'sequence_position' => 91,
            'type_id' => 1
        ], [
            'code' => 'dashboardSettingSite',
            'name' => 'Dashboard Setting Site',
            'description' => 'Dashboard Setting Site'
        ], [
            'R' => true
        ], 'dashboardSetting');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSettingRoleAndPermission',
            'name' => 'Role & Permission',
            'description' => 'Dashboard Setting Role & Permission',
            'url' => 'dashboard/setting/role-and-permission',
            'icon' => 'fa fa-sitemap',
            'level' => 2,
            'sequence_position' => 96,
            'type_id' => 1
        ], [
            'code' => 'dashboardSettingRoleAndPermission',
            'name' => 'Dashboard Setting Role & Permission',
            'description' => 'Dashboard Setting Role & Permission'
        ], [
            'R' => true
        ], 'dashboardSetting');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSettingUser',
            'name' => 'User',
            'description' => 'User setting',
            'url' => 'dashboard/setting/user',
            'icon' => 'fa fa-users',
            'level' => 2,
            'sequence_position' => 97,
            'type_id' => 1
        ], [
            'code' => 'dashboardSettingUser',
            'name' => 'Dashboard Setting User',
            'description' => 'Dashboard Setting User'
        ], [
            'R' => true
        ], 'dashboardSetting');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSettingDatabase',
            'name' => 'Database',
            'description' => 'Database setting',
            'url' => 'dashboard/setting/database',
            'icon' => 'fa fa-database',
            'level' => 2,
            'sequence_position' => 98,
            'type_id' => 1
        ], [
            'code' => 'dashboardSettingDatabase',
            'name' => 'Dashboard Setting Database',
            'description' => 'Dashboard Setting Database'
        ], [
            'R' => true
        ], 'dashboardSetting');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSettingProcess',
            'name' => 'Process',
            'description' => 'Process',
            'url' => 'dashboard/setting/process',
            'icon' => 'fa fa-spinner',
            'level' => 2,
            'sequence_position' => 99,
            'type_id' => 1
        ], [
            'code' => 'dashboardSettingProcess',
            'name' => 'Dashboard Setting Process',
            'description' => 'Dashboard Setting Process'
        ], [
            'R' => true
        ], 'dashboardSetting');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSettingDatabasePanel',
            'name' => 'Panel',
            'description' => 'Database panel',
            'url' => 'dashboard/setting/database/panel',
            'icon' => 'fa fa-table',
            'level' => 3,
            'sequence_position' => 1,
            'type_id' => 1
        ], [
            'code' => 'dashboardSettingDatabasePanel',
            'name' => 'Dashboard Setting Database Panel',
            'description' => 'Dashboard Setting Database Panel'
        ], [
            'R' => true
        ], 'dashboardSettingDatabase');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSettingDatabaseMigration',
            'name' => 'Migration',
            'description' => 'Database migration',
            'url' => 'dashboard/setting/database/migration',
            'icon' => 'fa fa-refresh',
            'level' => 3,
            'sequence_position' => 2,
            'type_id' => 1
        ], [
            'code' => 'dashboardSettingDatabaseMigration',
            'name' => 'Dashboard Setting Database Migration',
            'description' => 'Dashboard Setting Database Migration'
        ], [
            'R' => true
        ], 'dashboardSettingDatabase');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSettingRole',
            'name' => 'Role',
            'description' => 'Dashboard Setting Role',
            'url' => 'dashboard/setting/role-and-permission/role',
            'icon' => 'fa fa-star',
            'level' => 3,
            'sequence_position' => 1,
            'type_id' => 1
        ], [
            'code' => 'dashboardSettingRole',
            'name' => 'Dashboard Setting Role',
            'description' => 'Dashboard Setting Role'
        ], [
            'R' => true
        ], 'dashboardSettingRoleAndPermission');

        \CI4Xpander_Dashboard\Helpers\Database\Table\Menu::create([
            'code' => 'dashboardSettingPermission',
            'name' => 'Permission',
            'description' => 'Dashboard Setting Permission',
            'url' => 'dashboard/setting/role-and-permission/permission',
            'icon' => 'fa fa-check-square-o',
            'level' => 3,
            'sequence_position' => 2,
            'type_id' => 1
        ], [
            'code' => 'dashboardSettingPermission',
            'name' => 'Dashboard Setting Permission',
            'description' => 'Dashboard Setting Permission'
        ], [
            'R' => true
        ], 'dashboardSettingRoleAndPermission');

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->db->table('menu')->truncate();
        $this->db->table('menu_permission')->truncate();

        $this->db->transComplete();
	}
}
