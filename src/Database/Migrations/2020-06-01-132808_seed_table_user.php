<?php namespace CI4Xpander\Dashboard\Database\Migrations;

class SeedTableUser extends \CI4Xpander\Migration
{
	public function up()
	{
        helper('text');

        $this->db->transStart();

        $date = date('Y-m-d H:i:s');

        $this->db->table('user')->insertBatch([
            [
                'code' => 'system',
                'email' => 'system@yourdomain.com',
                'name' => 'System',
                'password' => password_hash(random_string('sha1'), PASSWORD_ARGON2ID),
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'developer',
                'email' => 'developer@yourdomain.com',
                'name' => 'Developer',
                'password' => password_hash('12345678', PASSWORD_ARGON2ID),
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'administrator',
                'email' => 'administrator@yourdomain.com',
                'name' => 'Aministrator',
                'password' => password_hash('12345678', PASSWORD_ARGON2ID),
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

        $this->db->table('user')->truncate();

        $this->db->transComplete();
	}
}
