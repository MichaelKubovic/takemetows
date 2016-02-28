<?php
namespace app\models;

use app\components\ws\Model;

class Record extends Model
{
	protected static $endpoint = 'user/self/zone/:zone/record';

	public $id;
	public $type;
	public $name;
	public $content;
	public $ttl;
	public $prio;
	public $weight;
	public $port;

	public $zone;

	/**
	 * @todo implenent scenarious based on selected type
	 */
	public static function types()
	{
		return [
			'A' => 'A',
			'AAAA' => 'AAAA',
			'MX' => 'MX',
			'CNAME' => 'CNAME',
			'NS' => 'NS',
			'TXT' => 'TXT',
			'SRV' => 'SRV'
		];
	}

	public function rules()
	{
	    return [
	        [['type', 'name', 'content', 'ttl'], 'safe'],
	    ];
	}
}