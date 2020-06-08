<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class SeedTableStatus extends \CI4Xpander\Migration
{
	public function up()
	{
        $this->db->transStart();

        $date = date('Y-m-d H:i:s');

        $this->db->table('status')->insertBatch([
            [
                'code' => 'active',
                'name' => 'Active',
                'description' => 'Active',
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'code' => 'inactive',
                'name' => 'Inactive',
                'description' => 'Inactive',
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

        $this->db->table('status')->truncate();

        $this->db->transComplete();
	}
}
