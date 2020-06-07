<?php namespace CI4Xpander\Dashboard\Database\Migrations;

class SeedTableMenuType extends \CI4Xpander\Migration
{
	public function up()
	{
        $date = date('Y-m-d H:i:s');

        $this->db->transStart();

        $this->db->table('menu_type')->insertBatch([
            [
                'code' => 'dashboard',
                'name' => 'Dashboard',
                'description' => 'Dashboard',
                'status_id' => 1,
                'created_at' => $date,
                'deleted_at' => $date,
                'status_id' => 1,
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

        $this->db->table('menu_type')->truncate();

        $this->db->transComplete();
	}
}
