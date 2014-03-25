<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Green - Access Control List implementation.
 *
 * @package     Green
 * @author      David Stutz
 * @copyright   (c) 2013 - 2014 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Kohana_Green {

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
    public static function instance() {
        if (!is_object(Green::$_instance)) {
            Green::$_instance = new Green();
        }

        return Green::$_instance;
    }

    /**
     * Loads configuration and rules.
     */
    private function __construct() {
        $this->_config = Kohana::$config->load('green');
    }

    /**
     * Compares the given group with the group of current user logged in.
     *
     * @param	string	group name
     * @return	boolean	true if current group is higher or equal to given one.
     */
    public function has_role($role_name) {
        $user = Red::instance()->get_user();

        if (!$user) {
            return FALSE;
        }

        $role = ORM::factory('user_role', array('name' => $role_name));

        if (!$role->loaded()) {
            throw new Green_Exception('Role \'' . $role_name . '\' not found.');
        }

        return $user->has('roles', $role);
    }

    /**
     * Allow (so continue) a controller execution for currently logged in user and currently requested controller.
     * If there are not any limitations on the controller execution will be continued.
     *
     * @throws Green_Exception
     */
    public function proceed() {
        $controller = strtolower(Request::current()->controller());
        $rules = ORM::factory('rule')->where('type', '=', 'controller')->and_where('key', '=', $controller)->find_all();

        /**
         * Will go through all rules.
         * If one of the rules matches access is granted.
         * If no rules are found access is denied.
         */
        $allowed = FALSE;
        foreach ($rules as $rule) {
            if (Green_Filter::factory($rule->rule, $controller)->check()) {
                $allowed = TRUE;
            }
        }
        
        if (!$allowed) {
            throw new Green_Exception('Access denied.');
        }

        /**
         * Log the controller if logging is enabled.
         */
        if (FALSE !== $this->_config['logger']['controller']) {
            $this->_config['logger']['controller']->log(array(
                'controller' => $controller,
                'uri' => Request::current()->uri(),
            ));
        }
    }

    /**
     * Allow and thus execute given method on given object for current user.
     *
     * @throws Green_Exception
     * @param	object	object to call method on
     * @param	string	method to call
     * @param	array 	additional parameters for method
     * @return	boolean	allowed and executed.
     */
    public function allow($object, $method, $array = array()) {
        $rules = ORM::factory('rule')->where('type', '=', 'model')->and_where('key', '=', $object->object_name() . '.' . $method)->find_all();

        /**
         * Will go through all rules.
         * If one of the rules matches access is granted.
         * If no rules are found access is denied.
         */
        $allowed = FALSE;
        foreach ($rules as $rule) {
            if (Green_Filter::factory($rule->rule, $object)->check()) {
                $allowed = TRUE;
            }
        }
        
        if (!$allowed) {
            throw new Green_Exception('Access denied.');
        }
        
        /**
         * Log the method if logging is enabled.
         */
        if (FALSE !== $this->_config['logger']['model']) {
            $this->_config['logger']['model']->log(array(
                'model' => $object->object_name(),
                'model_id' => $object->id,
                'method' => $method,
                'data' => serialize($object->as_array()),
            ));
        }

        /**
         * All tests passed. So execute method on object.
         */
        call_user_func_array(array(
            $object,
            $method
        ), $array);
    }

    /**
     * Checks it the user is allowed to perform the given method on the given model
     * or on models with the given type, without executing the method!
     *
     * @param	mixed	object or object name
     * @param	string	mthod
     * @return	boolean	allowed
     */
    public function is_allowed($object, $method) {
        $rules = ORM::factory('rule')->where('type', '=', 'model')->and_where('key', '=', $object->object_name() . '.' . $method)->find_all();

        /**
         * If no rules found throw exception.
         */
        if (sizeof($rules) <= 0) {
            return FALSE;
        }

        /**
         * Go through all rules.
         */
        foreach ($rules as $rule) {
            if (!Green_Filter::factory($rule->rule, $object->object_name())->check()) {
                return FALSE;
            }
        }

        return TRUE;
    }
}
