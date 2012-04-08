<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Result extends Controller {

	private $tests;

	public function action_index()
	{
		Database::instance();
		DB::query(Database::UPDATE, "DELETE FROM test;")->execute();
		DB::query(Database::INSERT, "INSERT INTO test (changed) VALUES (0);")->execute();
	
		$tests = array();
		$tests[]	= $this->test('undeclared', 'success', 200, 1);
		$tests[]	= $this->test('undeclared', 'fail', 500, 1);
		$tests[]	= $this->test('undeclared', 'die', 200, 1);
		$tests[]	= $this->test('true', 'success', 200, 1);
		$tests[]	= $this->test('true', 'fail', 500, 0);
		$tests[]	= $this->test('true', 'die', 200, 1);
		$tests[]	= $this->test('false', 'success', 200, 1);
		$tests[]	= $this->test('false', 'fail', 500, 1);
		$tests[]	= $this->test('false', 'die', 200, 1);
		$tests[]	= $this->test('inherited', 'success', 200, 1);
		$tests[]	= $this->test('inherited', 'fail', 500, 0);
		$tests[]	= $this->test('inherited', 'die', 200, 1);
		$tests[]	= $this->test('inherited', 'success2', 200, 1);
		$tests[]	= $this->test('inherited', 'fail2', 500, 0);
		$tests[]	= $this->test('inherited', 'die2', 200, 1);
		$tests[]	= $this->test('listed', 'success', 200, 1);
		$tests[]	= $this->test('listed', 'fail', 500, 1);
		$tests[]	= $this->test('listed', 'die', 200, 1);
		$tests[]	= $this->test('listed', 'success_listed', 200, 1);
		$tests[]	= $this->test('listed', 'fail_listed', 500, 0);
		$tests[]	= $this->test('listed', 'die_listed', 200, 1);
		$tests[]	= $this->test('overridden', 'success', 200, 1);
		$tests[]	= $this->test('overridden', 'fail', 500, 0);
		$tests[]	= $this->test('overridden', 'die', 200, 1);
		$tests[]	= $this->test('overridden', 'success_listed', 200, 1);
		$tests[]	= $this->test('overridden', 'fail_listed', 500, 1);
		$tests[]	= $this->test('overridden', 'die_listed', 200, 1);
		
		$view = new View('result');
		$view->tests = $tests;
		$this->response->body($view->render());
	}
	
	private function test($controller, $action, $responseCode_expected, $dbChange_expected){
		DB::update('test')->set(array('changed' => 0))->execute();
		
		$url = url::site("$controller/$action");

		$request = curl_init($url);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($request);
		$error = curl_error($request);
		$responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
		curl_close($request);
		
		$dbChange = DB::select('changed')->from('test')->where('changed', '=', 1)->execute()->count();		
		
		$test = new stdClass;
		$test->controller = $controller;
		$test->action = $action;
		$test->url = $url;
		$test->responseCode = $responseCode;
		$test->responseCode_status = ($responseCode == $responseCode_expected)?'ok':'fail';
		$test->dbChange = $dbChange;
		$test->dbChange_status = ($dbChange==$dbChange_expected)?'ok':'fail';
		return $test;
	}

}