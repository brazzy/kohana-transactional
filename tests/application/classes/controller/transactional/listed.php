<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transactional_Listed extends Controller_Transactional_Undeclared {

	public $_transactional = array('success_listed', 'exception_listed', 'die_listed', 'die301_listed', 'die401_listed', 'die500_listed');

	public function action_success_listed()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		$this->response->body('success!');
	}

	public function action_exception_listed()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		throw new Exception("failure!");
	}

	public function action_die_listed()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		die();
	}

	public function action_die301_listed()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		header("HTTP/1.0 301");
		die();
	}

	public function action_die401_listed()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		header("HTTP/1.0 401");
		die();
	}

	public function action_die500_listed()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		header("HTTP/1.0 500");
		die();
	}

}