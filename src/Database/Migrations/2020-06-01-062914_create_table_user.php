<?php namespace CI4Xpander_Dashboard\Database\Migrations;

class Migration_create_table_user extends \CI4Xpander\Migration
{
	public function up()
	{
        $this->db->transStart();

        $this->forge->addField(array_merge(
            \CI4Xpander\Helpers\Database\Table\Field::ID(),
            \CI4Xpander\Helpers\Database\Table\Field::foreignID('status'),
            \CI4Xpander\Helpers\Database\Table\Field::string('code', [
                'null' => false
            ]),
            \CI4Xpander\Helpers\Database\Table\Field::string('email', [
                'null' => false
            ]),
            \CI4Xpander\Helpers\Database\Table\Field::string('name', [
                'null' => false
            ]),
            \CI4Xpander\Helpers\Database\Table\Field::string('password', [
                'null' => false
            ]),
            \CI4Xpander\Helpers\Database\Table\Field::trackable()
        ))->addUniqueKey('code')->addUniqueKey('email')->addPrimaryKey('id')->createTable('user');

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->forge->dropTable('user', true);

        $this->db->transComplete();
	}
}
