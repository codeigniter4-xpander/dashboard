<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class SeedTablePermission extends \CI4Xpander\Migration
{
    public function up()
	{
        $this->db->transStart();

        $date = date('Y-m-d H:i:s');

        $status = $this->db->table('status')->where('code', 'active')->get()->getRow();

        $this->db->table('permission')->insert([
            'code' => 'login',
            'name' => 'Login',
            'description' => 'Login',
            'status_id' => $status->id,
            'created_at' => $date,
            'updated_at' => $date,
            'created_by' => 1,
            'updated_by' => 1
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
