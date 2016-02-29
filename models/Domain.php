<?php
namespace app\models;

use app\components\ws\Model;

class Domain extends Model
{
	protected static $endpoint = 'user/self/zone';

	public $id;
	public $name;
	public $updateTime;

	public $records = [];

	public static function primaryKey()
    {
        return 'name';
    }

	public function getRecords()
	{
		if (!empty($this->records)) {
			return $this->records;
		}

		$this->records = $this->findNested('zone', 'record', 'app\models\Record');

		return $this->records;
	}

	public function getRecord($id)
	{
		return $this->findOneNested('zone', 'record', 'app\models\Record', $id);
	}
}