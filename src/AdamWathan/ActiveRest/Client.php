<?php namespace AdamWathan\ActiveRest;

use Guzzle\Http\Client as GuzzleClient;

class Client {

	protected $BaseUrl;
	protected $actions;
	protected $defaultActions = array(
		'index' => array(
			'method' => 'GET',
			'uri' => ''
			),
		'store' => array(
			'method' => 'POST',
			'uri' => ''
			),
		'show' => array(
			'method' => 'GET',
			'uri' => '{id}'
			),
		'update' => array(
			'method' => 'PUT',
			'uri' => '{id}'
			),
		'destroy' => array(
			'method' => 'DELETE',
			'uri' => '{id}'
			),);

	public function __construct($baseUrl, $actions)
	{
		$this->baseUrl = $baseUrl;
		$this->actions = $actions;
	}

	public function Show($id) {
		$response = $this->sendRequest('show', array('id' => $id));
		if ( ! $response->isSuccessful()) {
			return null;
		}
		return $response->json();
	}

	public function update($id, array $attributes)
	{
		$response = $this->sendRequest('update', array('id' => $id), $attributes);
		if ( ! $response->isSuccessful()) {
			return false;
		}
		return $response->json();
	}

	public function store(array $attributes)
	{
		$response = $this->sendRequest('store', array(), $attributes);
		if ( ! $response->isSuccessful()) {
			return false;
		}
		return $response->json();
	}

	public function destroy($id)
	{
		$response = $this->sendRequest('destroy', array('id' => $id));
		if ( ! $response->isSuccessful()) {
			return false;
		}
		return true;
	}

	protected function sendRequest($action, $parameters = array(), $payload = array())
	{
		$uri = $this->getUri($action, $parameters);
		$method = $this->getMethod($action);
		$request = $this->getClient()->{$method}($uri, array(), $payload);
		return $request->send();
	}

	protected function getMethod($action)
	{
		if ($this->hasCustom($action)) {
			return $this->getCustomMethod($action);
		}
		return $this->getDefaultMethod($action);
	}

	protected function getCustomMethod($action)
	{
		return $this->actions[$action]['method'];
	}

	protected function getDefaultMethod($action)
	{
		return $this->defaultActions[$action]['method'];
	}

	protected function getUri($action, $parameters)
	{
		if ($this->hasCustom($action)) {
			return $this->buildUri($this->getCustomUri($action), $parameters);
		}
		return $this->buildUri($this->getDefaultUri($action), $parameters);
	}

	protected function hasCustom($action)
	{
		return isset($this->actions[$action]);
	}

	protected function getCustomUri($action)
	{
		return $this->actions[$action]['uri'];
	}

	protected function getDefaultUri($action)
	{
		return $this->defaultActions[$action]['uri'];
	}

	protected function buildUri($uri, $parameters)
	{
		foreach ($parameters as $key => $value) {
			$uri = str_replace('{'.$key.'}', $value, $uri);
		}
		return $uri;
	}

	protected function getClient()
	{
		$client = new GuzzleClient($this->baseUrl);
		return $client;
	}
}
