<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class SeedTableUser extends \CI4Xpander\Migration
{
	public function up()
	{
        helper('text');

        $this->db->transStart();

        \CI4Xpander_Dashboard\Helpers\Database\Table\User::create([
            'code' => 'system',
            'email' => env('ci4xpander.dashboard.user.system.email', 'system@yourdomain.com'),
            'name' => 'System',
            'password' => \Config\Services::hashPassword(random_string('sha1')),
        ], [
            'system'
        ]);

        \CI4Xpander_Dashboard\Helpers\Database\Table\User::create([
            'code' => env('ci4xpander.dashboard.user.developer.code', 'superman'),
            'email' => env('ci4xpander.dashboard.user.developer.email', 'superman@yourdomain.com'),
            'name' => env('ci4xpander.dashboard.user.developer.name', 'Superman'),
            'password' => \Config\Services::hashPassword(env('ci4xpander.dashboard.user.developer.first_password', '1234567890')),
        ], [
            'user', 'developer'
        ]);

        \CI4Xpander_Dashboard\Helpers\Database\Table\User::create([
            'code' => env('ci4xpander.dashboard.user.administrator.code', 'administrator'),
            'email' => env('ci4xpander.dashboard.user.administrator.email', 'administrator@yourdomain.com'),
            'name' => env('ci4xpander.dashboard.user.administrator.name', 'Administrator'),
            'password' => \Config\Services::hashPassword(env('ci4xpander.dashboard.user.administrator.first_password', '1234567890')),
        ], [
            'user', 'administrator'
        ]);

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->db->table('user')->truncate();
        $this->db->table('user_role')->truncate();
        $this->db->table('user_permission')->truncate();

        $this->db->transComplete();
	}
}
