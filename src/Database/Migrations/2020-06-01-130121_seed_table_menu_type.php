<?php

namespace CI4Xpander_Dashboard\Database\Migrations;

class Migration_seed_table_menu_type extends \CI4Xpander\Migration
{
    public function up()
    {
        $date = date('Y-m-d H:i:s');

        $this->db->transStart();

        $status = $this->db->table('status')->where('code', 'active')->get()->getRow();

        $this->db->table('menu_type')->insert([
            'code' => 'dashboard',
            'name' => 'Dashboard',
            'description' => 'Dashboard menu',
            'status_id' => $status->id,
            'created_at' => $date,
            'updated_at' => $date,
            'created_by' => 1,
            'updated_by' => 1
        ]);

        $this->db->table('menu_type')->insert([
            'code' => 'public',
            'name' => 'Public',
            'description' => 'Public menu',
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

        $this->db->table('menu_type')->truncate();

        $this->db->transComplete();
    }
}
