<?php
namespace app\components\ws;

use Yii;
use yii\base\Component;
use GuzzleHttp;

/**
 * Simple Guzzle Wrapper
 */
class Api extends Component
{
	protected $client;

	public function __construct()
	{
		parent::__construct();

		$params = Yii::$app->params['ws'];
		$config = [
			'base_uri' => 'https://rest.websupport.sk/v1/',
			'headers' => [
				'Authorization' => 'Basic ' . base64_encode($params['login'] . ':' . $params['password'])
			]
		];
		$this->client = new GuzzleHttp\Client($config);
	}

	public function request($method, $uri, $data = null)
	{
		$options = [];
		if ($data) {
			$options['json'] = $data;
		}

		$response = $this->client->request($method, $uri, $options);
		if (in_array($response->getStatusCode(), [200, 201, 202])) {
			return json_decode($response->getBody(), true);
		}

		throw new \Exception('Invalid request. ' . $response->getBody());
	}
}