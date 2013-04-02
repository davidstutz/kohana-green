<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Green configuration.
 * 
 * @package		Green
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://opensource.org/licenses/bsd-3-clause
 */
return array(
	
	/**
	 * Green can either be used as blacklist or as whitelist.
	 * Blacklist: ALL actions are allowed at default, the rules can limit access for certain actions.
	 * Whitelist: ALL actions are denied at default, the rules will allow access for certain actions to groups.
	 */
	'whitelist' => TRUE,
	
	/**
	 * Options concerning the logger.
	 * The logger can log each action allowed and denied on all models, controllers and actions.
	 */
	'logger' => array(
		'model' => FALSE,
		'controller' => FALSE,
	),
);