<?php namespace CI4Xpander\Dashboard\Database\Migrations;

class SeedTableRolePermission extends \CI4Xpander\Migration
{
	public function up()
	{
        $this->db->transStart();

        $date = date('Y-m-d H:i:s');

        $this->db->table('role_permission')->insertBatch([
            [
                'role_id' => 2,
                'permission_id' => 1,
                'status_id' => 1,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 2,
                'permission_id' => 2,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 2,
                'permission_id' => 3,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 2,
                'permission_id' => 4,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 2,
                'permission_id' => 5,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 2,
                'permission_id' => 6,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 2,
                'permission_id' => 7,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 2,
                'permission_id' => 8,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 2,
                'permission_id' => 9,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 3,
                'permission_id' => 1,
                'status_id' => 1,
                'C' => false,
                'R' => true,
                'U' => false,
                'D' => false,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 3,
                'permission_id' => 2,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 3,
                'permission_id' => 3,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'role_id' => 3,
                'permission_id' => 6,
                'status_id' => 1,
                'C' => true,
                'R' => true,
                'U' => true,
                'D' => true,
                'created_at' => $date,
                'updated_at' => $date,
                'created_by' => 1,
                'updated_by' => 1
            ],
        ]);

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->db->table('role_permission')->truncate();

        $this->db->transComplete();
	}
}
