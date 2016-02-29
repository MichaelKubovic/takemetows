<?php
namespace app\components\ws;

use Yii;
use yii\base\Model as YiiModel;
use yii\base\InvalidConfigException;

/**
 * Model communicating over REST
 *
 * @todo refactor creating of nested resources into ResourceFactory
 */
class Model extends YiiModel
{
	protected static $endpoint;

	protected $parentResource = [];

	protected static function api()
	{
		return Yii::$app->wsClient;
	}

	public static function primaryKey()
    {
        return 'id';
    }

	protected static function endpoint()
	{
		if (empty(static::$endpoint)) {
			throw new InvalidConfigException('Missing endpoint for model ' . get_called_class());
		}

		return static::$endpoint;
	}

	public static function findOne($key)
	{
		$response = self::api()->request('GET', self::endpoint() . '/' . $key);
		return new static($response);
	}

	public function getCollectionEndpoint()
	{
		$endpoint = static::endpoint();

		if (!empty($this->parentResource)) {
			$needle = ':' . array_keys($this->parentResource)[0];
			$obj = array_values($this->parentResource)[0];
			$value = $obj->attributes[$obj::primaryKey()];
			$endpoint = str_replace($needle, $value, $endpoint);
		}

		return $endpoint;
	}

	protected function getResourceEndpoint()
	{
		if (empty($this->attributes[static::primaryKey()])) {
			throw new InvalidConfigException('Model ' . get_called_class() .
				' cannot determine endpoint becuase primaryId attribute is empty');
		}
		
		$endpoint = $this->getCollectionEndpoint();
		return $endpoint . '/' . $this->attributes[static::primaryKey()];
	}

	public function setParentResource($resource)
	{
		if (!is_array($resource) || !array_values($resource)[0] instanceof Model) {
			throw new InvalidConfigException('Parent resrounce must be an array [resource => Object]');
		}

		$this->parentResource = $resource;
	}

	public function findNested($parentResource, $nestedResource, $nestedClassName)
	{
		$items = [];
		$response = self::api()->request('GET', $this->getResourceEndpoint() . '/' . $nestedResource);
		foreach ($response['items'] as $item) {
			$item = new $nestedClassName($item);
			$item->setParentResource([$parentResource => $this]);
			$items[] = $item;
		}
		return $items;
	}

	public function findOneNested($parentResource, $nestedResource, $nestedClassName, $nestedId)
	{
		$response = self::api()->request('GET', $this->getResourceEndpoint() . '/' . $nestedResource . '/' . $nestedId);
		$item = new $nestedClassName($response);
		$item->setParentResource([$parentResource => $this]);
		return $item;
	}

	public function save()
	{
		if (empty($this->id)) {
			return $this->create();
		} else {
			return $this->update();
		}
	}

	public function update()
	{
		throw new \Exception('Not implemented.');
	}

	public function create()
	{
		$response = self::api()->request('POST', $this->getCollectionEndpoint(), $this->getAttributes());
		if ($response['status'] == 'error') {
            $this->addErrors($response['errors']);
            return false;
        }

        // $createdResource = $response['item'];
        // $this->{self::primaryKey()} = $createdResource[self::primaryKey()];
        return new static($response['item']);

        return $this;
	}

	public function delete()
	{
		$response = self::api()->request('DELETE', $this->getResourceEndpoint());
		if ($response['status'] === 'error') {
			$this->addErrors($response['errors']);
            return false;
		}

		return true;
	}
}