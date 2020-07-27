<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class SeedTableUser extends \CI4Xpander\Migration
{
	public function up()
	{
        helper('text');

        $this->db->transStart();

        \CI4Xpander_Dashboard\Helpers\Database\Table\User::create([
            'code' => 'system',
            'email' => 'system@yourdomain.com',
            'name' => 'System',
            'password' => \Config\Services::hashPassword(random_string('sha1')),
        ], [
            'system'
        ]);

        \CI4Xpander_Dashboard\Helpers\Database\Table\User::create([
            'code' => 'developer',
            'email' => 'developer@yourdomain.com',
            'name' => 'Developer',
            'password' => \Config\Services::hashPassword(env('ci4xpander.user.developer.first_password', '1234567890')),
        ], [
            'developer'
        ]);

        \CI4Xpander_Dashboard\Helpers\Database\Table\User::create([
            'code' => 'administrator',
            'email' => 'administrator@yourdomain.com',
            'name' => 'Administrator',
            'password' => \Config\Services::hashPassword(env('ci4xpander.user.administrator.first_password', '1234567890')),
        ], [
            'administrator'
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
