<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Green configuration.
 * 
 * @package		Green
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://www.gnu.org/licenses/gpl-3.0
 */
return array(

	/**
	 * Define the role hierarchy.
	 * The key defines the role and the value the psoition within the hierarchy, the higher the better.
	 * 
	 * Note: Green expects all used groups to exists in the hierarchy.
	 */
	'hierarchy' => array(
		'login' => 0,
		'user' => 10,
		'admin' => 20,
		'super' => 30,
	),
	
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
		'model' => TRUE,
		'controller' => FALSE,
		'action' => FALSE,
	),
);