<?php namespace AdamWathan\ActiveRest;

abstract class Model {
	
	protected $baseUrl;
	protected $attributes = array();
	protected $primaryKey = 'id';

	public static function find($id)
	{
		$instance = new static;
		if ( ! $instance->attributes = $instance->getClient()->find($id)) {
			return null;
		}
		return $instance;
	}

	protected function getClient()
	{
		$client = new Client($this->baseUrl);
		return $client;
	}

	public function save()
	{
		if ($this->exists()) {
			return $this->performUpdate();
		}
		return $this->performInsert();
	}

	protected function exists()
	{
		return isset($this->attributes[$this->primaryKey]);
	}

	protected function performUpdate()
	{
		if ( ! $this->attributes = $this->getClient()->update($this->getKey(), $this->attributes)){
			return false;
		}
		return true;
	}

	protected function getKey()
	{
		return $this->{$this->primaryKey};
	}

	protected function performInsert()
	{
		if ( ! $this->attributes = $this->getClient()->insert($this->attributes)){
			return false;
		}
		return true;
	}

	public function delete()
	{
		if ( ! $this->getClient()->delete($this->getKey())) {
			return false;
		}
		return true;
	}

	public function __set($key, $value)
	{
		$this->attributes[$key] = $value;
	}

	public function __get($key)
	{
		return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
	}
}