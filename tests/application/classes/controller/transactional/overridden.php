<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transactional_Overridden extends Controller_Transactional_Listed {

	public $_transactional = array('success', 'fail', 'die');

}