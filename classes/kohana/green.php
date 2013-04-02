<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Green - Access Control List implementation.
 * 
 * @package		Green
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://opensource.org/licenses/bsd-3-clause
 */
class Kohana_Green
{

	/**
	 * @var	object	instance
	 */
	protected static $_instance;

	/**
	 * @var	array 	config
	 */
	protected $_config;

	/**
	 * Singleton.
	 *
	 * @return	object	Green
	 */
	public static function instance()
	{
		if (!is_object(Green::$_instance))
		{
			Green::$_instance = new Green();
		}

		return Green::$_instance;
	}

	/**
	 * Loads configuration and rules.
	 */
	public function __construct()
	{
		$this->_config = Kohana::$config->load('green');
	}
	
	/**
	 * Compares the given group with the group of current user logged in.
	 * 
	 * @param	string	group
	 * @return	boolean	true if current group is higher or equal to given one.
	 */
	public function high_enough($group)
	{
		$user = Red::instance()->get_user();
			
		if (!$user)
		{
			return FALSE;
		}
		
		$group = ORM::factory('user_group', array('name' => $group));
		
		if (!$group->loaded())
		{
			return FALSE;
		}
		
		return $user->group->position >= $group->position;
	}
	
	/**
	 * Allow (so continue) a controller/action combination execution for currently logged in user and currently requested controller.
	 * If ther are not any limitations on the aciton the controller will checked. 
	 * If ther are not any limitations on the controller, too, execution will be continued.
	 * 
	 * Usage:
	 * 	try
	 * 	{
	 * 		$this->proceed(); // Will evaluate using current request.
	 * 	}
	 * 	catch (Green_Access_Esxception $e)
	 * 	{
	 * 		$this->redirect('no/access');
	 * 	}
	 * 
	 * @throws Green_Access_Exception
	 */
	public function proceed()
	{
		/**
		 * Check if an controller rule exists.
		 * If a rule exists evaluate rule.
		 */
		$controller = strtolower(Request::current()->controller());
		$rules = ORM::factory('rule')->where('type', '=', 'controller')->and_where('key', '=', $controller)->find_all();
		
		/**
		 * If no rules found and whitelsit is set up throw exception.
		 */
		if (sizeof($rules) == 0
			AND $this->_config['whitelist'])
		{
			throw new Green_Access_Exception('access denied.');
		}
		
		/**
		 * Will go through all rules.
		 * If one of the rules matches access is granted.
		 */
		
		foreach ($rules as $rule)
		{
			if (!$this->high_enough($rule->rule))
			{
				throw new Green_Access_Exception('access denied.');
			}
		}
		
		/**
		 * Log the controller if logging is enabled.
		 */
		if (FALSE !== $this->_config['logger']['controller'])
		{
			$this->_config['logger']['controller']->log(array(
				'controller' => $controller,
				'uri' => Request::current()->uri(),
			));
		}
	}
	
	/**
	 * Allow given method on given object for current user.
	 * 
	 * The given action must be part of the allowed actions in config.
	 * 
	 * Usage:
	 * 	$model->data = $data;
	 * 	// ...
	 * 	Green::instance()->allow($model, 'update'); // Will check access and update the model.
	 * 
	 * @throws Green_Access_Exception
	 * @param	object	object to call method on
	 * @param	string	method to call
	 * @param	array 	additional parameters for method
	 * @return	boolean	allowed and executed.
	 */
	public function allow($object, $method, $array = array())
	{
		/**
		 * Check if rule is defined.
		 * First check for model.
		 * Then check for action.
		 * If not deny access.
		 */
		$rules = ORM::factory('rule')->where('type', '=', 'model')->and_where('key', '=', $object->object_name() . '.' . $method)->find_all();
		
		/**
		 * If no rules found and whitelsit is set up throw exception.
		 */
		if (sizeof($rules) == 0
			AND $this->_config['whitelist'])
		{
			throw new Green_Access_Exception('access denied.');
		}
		
		/**
		 * Will go through all rules.
		 * If one of the rules matches access is granted.
		 */
		
		foreach ($rules as $rule)
		{
			if (!$this->high_enough($rule->rule))
			{
				throw new Green_Access_Exception('access denied.');
			}
		}
		
		/**
		 * All tests passed. So execute method on object.
		 */
		call_user_func_array(array($object, $method), $array);
		
		/**
		 * Log the method if logging is enabled.
		 */
		if (FALSE !== $this->_config['logger']['model'])
		{
			$this->_config['logger']['model']->log(array(
				'model' => $object->object_name(),
				'model_id' => $object->id,
				'method' => $method,
				'data' => serialize($object->as_array()),
			));
		}
	}

	/**
	 * Checks it the user is allowed to perform the given method on the given model,
	 * or on models with the given type, without executing the mehtod!
	 * 
	 * @param	mixed	object or object name
	 * @param	string	mthod
	 * @return	boolean	allowed
	 */
	public function is_allowed($mixed, $method)
	{
		$object_name = $mixed;
		if (is_object($mixed))
		{
			$object_name = $mixed->object_name();
		}
		
		$rules = ORM::factory('rule')->where('type', '=', 'model')->and_where('key', '=', $object_name . '.' . $method)->find_all();
		
		/**
		 * If no rules found and whitelsit is set up throw exception.
		 */
		if (sizeof($rules) == 0
			AND $this->_config['whitelist'])
		{
			return FALSE;
		}
		
		/**
		 * Go through all rules.
		 */
		foreach ($rules as $rule)
		{
			if (!$this->high_enough($rule->rule))
			{
				return FALSE;
			}
		}
		
		return TRUE;
	}
}
