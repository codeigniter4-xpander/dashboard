<?php namespace CI4Xpander\Dashboard\Database\Migrations;

class CreateTablePermission extends \CI4Xpander\Migration
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
            \CI4Xpander\Helpers\Database\Table\Field::string('name', [
                'null' => false
            ]),
            \CI4Xpander\Helpers\Database\Table\Field::text('description'),
            \CI4Xpander\Helpers\Database\Table\Field::trackable()
        ))->addUniqueKey('code')->addPrimaryKey('id')->createTable('permission');

        $this->db->transComplete();
	}

	//--------------------------------------------------------------------

	public function down()
	{
        $this->db->transStart();

        $this->forge->dropTable('permission', true);

        $this->db->transComplete();
	}
}
