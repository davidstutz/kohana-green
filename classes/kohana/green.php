<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Green - Access Control List implementation.
 * 
 * @package		Green
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://www.gnu.org/licenses/gpl-3.0
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
		
		return $this->_config['hierarchy'][$user->group->name] >= $this->_config['hierarchy'][$group];
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
	 * 		$this->request->redirect('no/access');
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
		$rule = ORM::factory('rule')->where('type', '=', 'controller')->and_where('key', '=', Request::current()->controller())->find();
		if ($rule->loaded()
			AND !$this->high_enough($rule->rule))
		{
			throw new Green_Access_Exception("Access denied.");
		}
		
		if (!$rule->loaded()
			AND $this->_config['whitelist'])
		{
			throw new Green_Access_Exception("Access denied.");
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
		$rules = ORM::factory('rule')->where('type', '=', 'controller')->and_where('key', '=', $object->object_name() . '.' . $method)->find_all();
		
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
		
		$model_access = TRUE;
		
		foreach ($rules as $rule)
		{
			if (preg_match('^:group$', $rule->rule))
			{
				$model_access = $model_access AND ($object->user->group->id === Red::instance()->get_user()->group->id);
			}
			elseif (preg_match('^:user$', $rule->rule))
			{
				$model_access = $model_access AND ($object->user->id === Red::instance()->get_user()->id);
			}
			else
			{
				$model_access = $model_access AND $this->high_enough($rule->rule);
			}
		}
		
		if (!$model_access)
		{
			throw new Green_Access_Exception('access denied.');
		}
		
		/**
		 * All tests passed. So execute method on object.
		 */
		call_user_func_array(array($object, $method), $array);
		
		/**
		 * Log the aciton if logging is enabled.
		 */
		if (FALSE !== $this->_config['logger']['model'])
		{
			Yellow::log(Yellow::MODEL, array(
				'model' => $object->object_name(),
				'model_id' => $object->id,
				'method' => $method,
			));
		}
	}
}
