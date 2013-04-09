<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Implement declarative transactions. Controller actions marked as transactional are executed 
 * inside a transaction, which is rolled back if there is an uncaught exception. Using die() or exit()
 * will still have the transaciton committed unless a HTTP response code >= 400 is set and the PHP
 * installation supports the http_response_code() function (present in PHP 5.4)
 * 
 * The behaviour can be configured by adding a public property '_transactional' to a controller, which
 * is either true or false (affects all actions) or contains an array with the names (minus
 * the 'action_' prefix) of the actions that should be transactional.
 * 
 * The configuration can be inherited.
 * 
 * @author MBorgwardt
 *
 */
class Transactional_Request_Client_Internal extends Kohana_Request_Client_Internal {
	private $_transactional = false;
	private $_transaction_finished = false;
	
	public function execute(Request $request)
	{
		// Code to get controller instance copied from Kohana_Request_Client_Internal
		$prefix = 'Controller_';
		$directory = $request->directory();
		$controller = $request->controller();		
		if ($directory)
		{
			$prefix .= str_replace(array('\\', '/'), '_', trim($directory, '/')).'_';
		}
		if ( ! class_exists($prefix.$controller))
		{
			throw new HTTP_Exception_404('The requested URL :uri was not found on this server.',
			array(':uri' => $request->uri()));
		}
		$class = new ReflectionClass($prefix.$controller);		
		if ($class->isAbstract())
		{
			throw new Kohana_Exception('Cannot create instances of abstract :controller',
			array(':controller' => $prefix.$controller));
		}
		$controller = $class->newInstance($request, Response::factory());
		
		if(property_exists($controller, '_transactional'))
		{
			if(is_array($controller->_transactional))
			{
				$this->_transactional = in_array($request->action(), $controller->_transactional);
			}
			else
			{
				$this->_transactional = $controller->_transactional;
			}
		}
		
		// run action inside transaction
		if($this->_transactional)
		{
			Database::instance()->begin();
			
			// commit at end of script, even if someone uses die/exit
			register_shutdown_function(array($this, 'commit'));
		}
		
		try
		{
			return parent::execute($request);
		}
		catch (Exception $e)
		{
			if($this->_transactional && !$this->_transaction_finished)
			{
				Database::instance()->rollback();
				$this->_transaction_finished = true;
			}
			throw $e;
		}
	}

	public function commit()
	{
		if($this->_transactional && !$this->_transaction_finished)
		{
			if (function_exists('http_response_code') && http_response_code() >= 400) 
			{
				Kohana::$log->add(Log::WARNING, 'rollback');
				Database::instance()->rollback();
			} 
			else
			{
				Kohana::$log->add(Log::NOTICE, 'commit');
				Database::instance()->commit();
			}
			$this->_transaction_finished = true;
			Kohana::$log->write();
		}		
	}	
}
