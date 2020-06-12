<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class SeedTableMenuPermission extends \CI4Xpander\Migration
{
	public function up()
	{
        $date = date('Y-m-d H:i:s');

        $this->db->transStart();

        $this->db->table('menu_permission')->insertBatch([
            [
                'status_id' => 1,
                'menu_id' => 1,
                'permission_id' => 2,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'deleted_by' => 1
            ],
            [
                'status_id' => 1,
                'menu_id' => 2,
                'permission_id' => 3,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'deleted_by' => 1
            ],
            [
                'status_id' => 1,
                'menu_id' => 3,
                'permission_id' => 4,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'deleted_by' => 1
            ],
            [
                'status_id' => 1,
                'menu_id' => 4,
                'permission_id' => 5,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'deleted_by' => 1
            ],
            [
                'status_id' => 1,
                'menu_id' => 5,
                'permission_id' => 6,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'deleted_by' => 1
            ],
            [
                'status_id' => 1,
                'menu_id' => 6,
                'permission_id' => 7,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'deleted_by' => 1
            ],
            [
                'status_id' => 1,
                'menu_id' => 7,
                'permission_id' => 8,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'deleted_by' => 1
            ],
            [
                'status_id' => 1,
                'menu_id' => 8,
                'permission_id' => 9,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'deleted_by' => 1
            ],
            [
                'status_id' => 1,
                'menu_id' => 9,
                'permission_id' => 10,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'deleted_by' => 1
            ]
        ]);

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->db->table('menu_permission')->truncate();

        $this->db->transComplete();
	}
}
