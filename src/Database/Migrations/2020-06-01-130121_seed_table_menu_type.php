<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class SeedTableMenuType extends \CI4Xpander\Migration
{
	public function up()
	{
        $date = date('Y-m-d H:i:s');

        $this->db->transStart();

        $status = $this->db->table('status')->where('code', 'active')->get()->getRow();

        $this->db->table('menu_type')->insert([
            'code' => 'dashboard',
            'name' => 'Dashboard',
            'description' => 'Dashboard',
            'status_id' => $status->id,
            'created_at' => $date,
            'deleted_at' => $date,
            'created_by' => 1,
            'updated_by' => 1
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