<?php namespace CI4Xpander\Dashboard\Database\Migrations;

class SeedTableUserRole extends \CI4Xpander\Migration
{
	public function up()
	{
        $this->db->transStart();

        $date = date('Y-m-d H:i:s');

        $this->db->table('user_role')->insertBatch([
            [
                'user_id' => 1,
                'role_id' => 1,
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'user_id' => 2,
                'role_id' => 3,
                'status_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'user_id' => 3,
                'role_id' => 3,
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

        $this->db->table('user_role')->truncate();

        $this->db->transComplete();
	}
}
