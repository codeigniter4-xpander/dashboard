<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class CreateTableRolePermission extends \CI4Xpander\Migration
{
	public function up()
	{
        $this->db->transStart();

        $this->forge->addField(array_merge(
            \CI4Xpander\Helpers\Database\Table\Field::ID(),
            \CI4Xpander\Helpers\Database\Table\Field::foreignID('status'),
            \CI4Xpander\Helpers\Database\Table\Field::foreignID('role'),
            \CI4Xpander\Helpers\Database\Table\Field::foreignID('permission'),
            \CI4Xpander\Helpers\Database\Table\Field::boolean('C'),
            \CI4Xpander\Helpers\Database\Table\Field::boolean('R'),
            \CI4Xpander\Helpers\Database\Table\Field::boolean('U'),
            \CI4Xpander\Helpers\Database\Table\Field::boolean('D'),
            \CI4Xpander\Helpers\Database\Table\Field::trackable()
        ))->addPrimaryKey('id')->addUniqueKey([
            'role_id', 'permission_id'
        ])->createTable('role_permission');

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->forge->dropTable('role_permission', true);

        $this->db->transComplete();
	}
}
