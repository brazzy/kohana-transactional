<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transactional_Undeclared extends Controller {

	public function action_success()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		$this->response->body('success!');
	}

	public function action_exception()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		throw new Exception("failure!");
	}

	public function action_die()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		die();
	}

	public function action_die301()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		header("HTTP/1.0 301");
		die();
	}

	public function action_die401()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		header("HTTP/1.0 401");
		die();
	}

	public function action_die500()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		header("HTTP/1.0 500");
		die();
	}

}