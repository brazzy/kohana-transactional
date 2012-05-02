<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Transactional_Overridden extends Controller_Transactional_Listed {

	public $_transactional = array('success', 'exception', 'die', 'die301', 'die401', 'die500');

}