<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transactional_Inherited extends Controller_Transactional_True {

	public function action_success_2()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		$this->response->body('success!');
	}

	public function action_exception_2()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		throw new Exception("failure!");
	}

	public function action_die_2()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		die();
	}
	
	public function action_die301_2()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		header("HTTP/1.0 301");
		die();
	}

	public function action_die401_2()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		header("HTTP/1.0 401");
		die();
	}

	public function action_die500_2()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		header("HTTP/1.0 500");
		die();
	}

}