<?php

use AdamWathan\ActiveRest\Model as ActiveRestModel;
use AdamWathan\ActiveRest\Client as ActiveRestClient;

class BasicFormBuilderTest extends PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		Mockery::close();
	}

	public function testFindById()
	{
		$model = RestModelFindStub::find(1);
		$this->assertEquals('bar', $model->foo);
	}

	public function testSaveNewModel()
	{
		$model = new RestModelInsertStub;
		$model->foo = 'bar';
		$model->save();
		$this->assertEquals('bar', $model->foo);
		$this->assertEquals(1, $model->id);
	}

	public function testUpdateModel()
	{
		$model = new RestModelUpdateStub;
		$model->id = 1;
		$model->foo = 'bar';
		$model->save();
		$this->assertEquals('bar', $model->foo);
		$this->assertEquals(1, $model->id);
	}

	public function testDeleteModel()
	{
		$model = new RestModelDeleteStub;
		$model->id = 1;
		$model->foo = 'bar';
		$result = $model->delete();
		$this->assertTrue($result);
	}
}

class RestModelFindStub extends ActiveRestModel {
	protected $baseUrl = 'foo';

	protected function getClient()
	{
		$mock = Mockery::mock('AdamWathan\ActiveRest\Client');
		$mock->shouldReceive('show')->once()->with(1)->andReturn(array('id' => 1, 'foo' => 'bar'));
		return $mock;
	}
}

class RestModelInsertStub extends ActiveRestModel {
	protected $baseUrl = 'foo';

	protected function getClient()
	{
		$mock = Mockery::mock('AdamWathan\ActiveRest\Client');
		$mock->shouldReceive('store')->once()->with(array('foo' => 'bar'))->andReturn(array('id' => 1, 'foo' => 'bar'));
		return $mock;
	}
}

class RestModelUpdateStub extends ActiveRestModel {
	protected $baseUrl = 'foo';

	protected function getClient()
	{
		$mock = Mockery::mock('AdamWathan\ActiveRest\Client');
		$mock->shouldReceive('update')->once()->with(1, array('id' => 1, 'foo' => 'bar'))->andReturn(array('id' => 1, 'foo' => 'bar'));
		return $mock;
	}
}

class RestModelDeleteStub extends ActiveRestModel {
	protected $baseUrl = 'foo';

	protected function getClient()
	{
		$mock = Mockery::mock('AdamWathan\ActiveRest\Client');
		$mock->shouldReceive('destroy')->once()->with(1)->andReturn(true);
		return $mock;
	}
}