<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UrlsStatistics extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'ust_id'          => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'ust_date'       => [
				'type'           => 'DATE',
				'null' => false
			],
			'ust_click'       => [
				'type'           => 'INT',
				'constraint'     => 11,
				'default' => '0'
			],
			'ust_create_at datetime default current_timestamp',
			'ust_update_at datetime default null on update current_timestamp',
			'urls_id'       => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => TRUE,
				'null' => false
			],
		]);
		$this->forge->addForeignKey('urls_id','urls','urls_id','CASCADE','CASCADE');
		$this->forge->addKey('ust_id', true);
		$this->forge->createTable('urls_statistics');
	}

	public function down()
	{
		$this->forge->dropTable('urls_statistics');
	}
}
