<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class SeedTableRole extends \CI4Xpander\Migration
{
	public function up()
	{
        $this->db->transStart();

        $date = date('Y-m-d H:i:s');

        $this->db->table('role')->insertBatch([
            [
                'code' => 'system',
                'name' => 'System',
                'description' => 'System',
                'status_id' => 1,
                'level' => 0,
                'parent_id' => 0,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'developer',
                'name' => 'Developer',
                'description' => 'Developer',
                'status_id' => 1,
                'level' => 1,
                'parent_id' => 1,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'administrator',
                'name' => 'Administrator',
                'description' => 'Administrator',
                'status_id' => 1,
                'level' => 10,
                'parent_id' => 1,
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

        $this->db->table('role')->truncate();

        $this->db->transComplete();
	}
}
