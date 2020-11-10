<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class Migration_create_table_user_role extends \CI4Xpander\Migration
{
	public function up()
	{
        $this->db->transStart();

        $this->forge->addField(array_merge(
            \CI4Xpander\Helpers\Database\Table\Field::ID(),
            \CI4Xpander\Helpers\Database\Table\Field::foreignID('status'),
            \CI4Xpander\Helpers\Database\Table\Field::foreignID('user'),
            \CI4Xpander\Helpers\Database\Table\Field::foreignID('role'),
            \CI4Xpander\Helpers\Database\Table\Field::trackable()
        ))->addUniqueKey([
            'user_id', 'role_id'
        ])->addPrimaryKey('id')->createTable('user_role');

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->forge->dropTable('user_role', true);

        $this->db->transComplete();
	}
}
