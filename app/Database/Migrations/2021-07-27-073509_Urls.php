<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Urls extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'urls_id'          => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'urls_short'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '6',
				'null' => false
			],
			'urls_full'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '1000',
				'null' => false
			],
			'urls_click'       => [
				'type'           => 'INT',
				'constraint'     => 11,
				'default' => '0'
			],
			'urls_cid'       => [
				'type'           => 'INT',
				'constraint'     => 11,
				'null' => true
			],
			'urls_create_at datetime default current_timestamp',
			'urls_update_at datetime default null on update current_timestamp',
		]);
		$this->forge->addKey('urls_id', true);
		$this->forge->createTable('urls');
	}

	public function down()
	{
		$this->forge->dropTable('urls');
	}
}
