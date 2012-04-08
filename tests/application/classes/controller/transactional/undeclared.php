<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transactional_Undeclared extends Controller {

	public function action_success()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		$this->response->body('success!');
	}

	public function action_fail()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		throw new Exception("failure!");
	}

	public function action_die()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		die();
	}
}