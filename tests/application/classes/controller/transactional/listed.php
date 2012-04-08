<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transactional_Listed extends Controller_Transactional_Undeclared {

	public $_transactional = array('success_listed', 'fail_listed', 'die_listed');

	public function action_success_listed()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		$this->response->body('success!');
	}

	public function action_fail_listed()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		throw new Exception("failure!");
	}

	public function action_die_listed()
	{
		DB::update('test')->set(array('changed' => 1))->execute();
		die();
	}
}