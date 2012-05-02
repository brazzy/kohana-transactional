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
		$tests[]	= $this->test('undeclared', 'exception', 500, 1);
		$tests[]	= $this->test('undeclared', 'die', 200, 1);
		$tests[]	= $this->test('undeclared', 'die301', 301, 1, true);
		$tests[]	= $this->test('undeclared', 'die401', 401, 1, true);
		$tests[]	= $this->test('undeclared', 'die500', 500, 1, true);

		$tests[]	= $this->test('true', 'success', 200, 1);
		$tests[]	= $this->test('true', 'exception', 500, 0);
		$tests[]	= $this->test('true', 'die', 200, 1);
		$tests[]	= $this->test('true', 'die301', 301, 1, true);
		$tests[]	= $this->test('true', 'die401', 401, 0, true);
		$tests[]	= $this->test('true', 'die500', 500, 0, true);

		$tests[]	= $this->test('false', 'success', 200, 1);
		$tests[]	= $this->test('false', 'exception', 500, 1);
		$tests[]	= $this->test('false', 'die', 200, 1);
		$tests[]	= $this->test('false', 'die301', 301, 1, true);
		$tests[]	= $this->test('false', 'die401', 401, 1, true);
		$tests[]	= $this->test('false', 'die500', 500, 1, true);

		$tests[]	= $this->test('inherited', 'success', 200, 1);
		$tests[]	= $this->test('inherited', 'exception', 500, 0);
		$tests[]	= $this->test('inherited', 'die', 200, 1);
		$tests[]	= $this->test('inherited', 'die301', 301, 1, true);
		$tests[]	= $this->test('inherited', 'die401', 401, 0, true);
		$tests[]	= $this->test('inherited', 'die500', 500, 0, true);
		$tests[]	= $this->test('inherited', 'success_2', 200, 1);
		$tests[]	= $this->test('inherited', 'exception_2', 500, 0);
		$tests[]	= $this->test('inherited', 'die_2', 200, 1);
		$tests[]	= $this->test('inherited', 'die301_2', 301, 1, true);
		$tests[]	= $this->test('inherited', 'die401_2', 401, 0, true);
		$tests[]	= $this->test('inherited', 'die500_2', 500, 0, true);

		$tests[]	= $this->test('listed', 'success', 200, 1);
		$tests[]	= $this->test('listed', 'exception', 500, 1);
		$tests[]	= $this->test('listed', 'die', 200, 1);
		$tests[]	= $this->test('listed', 'die301', 301, 1, true);
		$tests[]	= $this->test('listed', 'die401', 401, 1, true);
		$tests[]	= $this->test('listed', 'die500', 500, 1, true);
		$tests[]	= $this->test('listed', 'success_listed', 200, 1);
		$tests[]	= $this->test('listed', 'exception_listed', 500, 0);
		$tests[]	= $this->test('listed', 'die_listed', 200, 1);
		$tests[]	= $this->test('listed', 'die301_listed', 301, 1, true);
		$tests[]	= $this->test('listed', 'die401_listed', 401, 0, true);
		$tests[]	= $this->test('listed', 'die500_listed', 500, 0, true);

		$tests[]	= $this->test('overridden', 'success', 200, 1);
		$tests[]	= $this->test('overridden', 'exception', 500, 0);
		$tests[]	= $this->test('overridden', 'die', 200, 1);
		$tests[]	= $this->test('overridden', 'die301', 301, 1, true);
		$tests[]	= $this->test('overridden', 'die401', 401, 0, true);
		$tests[]	= $this->test('overridden', 'die500', 500, 0, true);
		$tests[]	= $this->test('overridden', 'success_listed', 200, 1);
		$tests[]	= $this->test('overridden', 'exception_listed', 500, 1);
		$tests[]	= $this->test('overridden', 'die_listed', 200, 1);
		$tests[]	= $this->test('overridden', 'die301_listed', 301, 1, true);
		$tests[]	= $this->test('overridden', 'die401_listed', 401, 1, true);
		$tests[]	= $this->test('overridden', 'die500_listed', 500, 1, true);
		
		$view = new View('result');
		$view->tests = $tests;
		$this->response->body($view->render());
	}
	
	private function test($controller, $action, $responseCode_expected, $dbChange_expected, $use_response_code=false){
	
		$url = url::site("$controller/$action");

		$test = new stdClass;
		$test->controller = $controller;
		$test->action = $action;
		$test->url = $url;
		
		if (!$use_response_code || function_exists('http_response_code')) {
			DB::update('test')->set(array('changed' => 0))->execute();
			
			$request = curl_init($url);
			curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($request);
			$error = curl_error($request);
			$responseCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
			curl_close($request);
			
			$dbChange = DB::select('changed')->from('test')->where('changed', '=', 1)->execute()->count();		
			
			$test->responseCode = $responseCode;
			$test->responseCode_status = ($responseCode == $responseCode_expected)?'ok':'fail';
			$test->dbChange = $dbChange;
			$test->dbChange_status = ($dbChange==$dbChange_expected)?'ok':'fail';
		} else {
			$test->responseCode = '?';
			$test->responseCode_status = '';
			$test->dbChange = '?';
			$test->dbChange_status = '';
		}
		
		return $test;
	}

}