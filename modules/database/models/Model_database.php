<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_database extends MY_Model {

    private $primary_key    = 'id';
    private $table_name     = 'database';
    public $field_search   = ['table_name', 'created_at'];

    public function __construct()
    {
        $config = array(
            'primary_key'   => $this->primary_key,
            'table_name'    => $this->table_name,
            'field_search'  => $this->field_search,
         );

        parent::__construct($config);
    }

    // public function get_field_type()
    // {
    //     return  [
	// 		'most' => [
	// 			'INT',
	// 			'VARCHAR',
	// 			'TEXT',
	// 			'DATE',
	// 		],
	// 		'numeric' => [
	// 			'DECIMAL',
	// 			'FLOAT',
	// 			'DOUBLE',
	// 			'BIGINT',
	// 			'SMALLINT',
	// 			'MEDIUMINT',
	// 			'TINYINT',
	// 			'BIT',
	// 			'BOOLEAN',
	// 			'SERIAL',
				
	// 		],
	// 		'string' => [
	// 			'TEXT',
	// 			'VARCHAR',
	// 			'CHAR',
	// 			'LONGTEXT',
	// 			'MEDIUMTEXT',
	// 			'TINYTEXT',
	// 		],
	// 		'date & time' => [
	// 			'DATE',
	// 			'DATETIME',
	// 			'TIMESTAMP',
	// 			'TIME',
	// 			'YEAR',
	// 		],
			
	// 		'spatial' => [
	// 			'GEOMETRY',
	// 			'LINESTRING',
	// 			'POLYGON',
	// 			'MULTIPOINT',
	// 		],
			
	// 		'other' => [
	// 			'ENUM',
	// 			'JSON',
	// 		],
			
	// 	];
    // }

	public function get_field_type()
	{
		return [
			'most' => [
				'INTEGER',
				'VARCHAR',
				'TEXT',
				'DATE',
			],
			'numeric' => [
				'NUMERIC',
				'REAL',
				'DOUBLE PRECISION',
				'BIGINT',
				'SMALLINT',
				'INTEGER',
				'SMALLINT',
				'BIT',
				'BOOLEAN',
				'SERIAL',
				'BIGSERIAL',
				'SMALLSERIAL',
			],
			'string' => [
				'TEXT',
				'VARCHAR',
				'CHAR',
				'TEXT',  // PostgreSQL doesn't have LONGTEXT
				'TEXT',  // PostgreSQL doesn't have MEDIUMTEXT
				'TEXT',  // PostgreSQL doesn't have TINYTEXT
				'CHARACTER VARYING',
				'CHARACTER',
			],
			'date & time' => [
				'DATE',
				'TIMESTAMP',
				'TIMESTAMP WITH TIME ZONE',
				'TIMESTAMP WITHOUT TIME ZONE',
				'TIME',
				'TIME WITH TIME ZONE',
				'TIME WITHOUT TIME ZONE',
				'INTERVAL',
			],
			'spatial' => [
				'GEOMETRY',
				'POINT',
				'LINE',
				'LSEG',
				'BOX',
				'PATH',
				'POLYGON',
				'CIRCLE',
			],
			'other' => [
				'ENUM',  // PostgreSQL supports ENUM but implementation differs
				'JSON',
				'JSONB',
				'UUID',
				'CIDR',
				'INET',
				'MACADDR',
				'XML',
				'MONEY',
			],
		];
	}

}

/* End of file Model_database.php */
/* Location: ./application/models/Model_database.php */