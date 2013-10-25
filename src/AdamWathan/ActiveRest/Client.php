<?php namespace AdamWathan\ActiveRest;

use Guzzle\Http\Client as GuzzleClient;

class Client {

	protected $baseUrl;

	public function __construct($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}

	public function find($id) {
		$request = $this->getClient()->get($id);
		$response = $request->send();
		if ( ! $response->isSuccessful()) {
			return null;
		}
		return $response->json();
	}

	public function update($key, array $attributes)
	{
		$request = $this->getClient()->put($key, array(), $attributes);
		$response = $request->send();
		if ( ! $response->isSuccessful()) {
			return false;
		}
		return $response->json();
	}

	public function insert(array $attributes)
	{
		$request = $this->getClient()->post('', array(), $attributes);
		$response = $request->send();
		if ( ! $response->isSuccessful()) {
			return false;
		}
		return $response->json();
	}

	public function delete($id)
	{
		$request = $this->getClient()->delete($id);
		$response = $request->send();
		if ( ! $response->isSuccessful()) {
			return false;
		}
		return true;
	}

	protected function getClient()
	{
		$client = new GuzzleClient($this->baseUrl);
		return $client;
	}
}