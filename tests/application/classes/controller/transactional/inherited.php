<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transactional_Inherited extends Controller_Transactional_True {

	public function action_success2()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		$this->response->body('success!');
	}

	public function action_fail2()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		throw new Exception("failure!");
	}

	public function action_die2()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		die();
	}
}