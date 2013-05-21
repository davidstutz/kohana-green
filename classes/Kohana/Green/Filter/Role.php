<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Green filter for checking whether the user has the appropriate group.
 *
 * @package     Green
 * @author      David Stutz
 * @copyright   (c) 2013 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Kohana_Green_Filter_Role extends Green_Filter {
    
    /**
     * Check the condition represented by the filter on the saved user.
     * 
     * @return boolean  true on success
     */
    public function check() {
        $role = ORM::factory('user_role', array('name' => $this->_constraint));
        
        if (!$role->loaded()) {
            throw new Green_Exception('Filter_Role: Constraint \'' . $this->_constraint . '\' invalid. This role was not found.');
        }
        
        return Red::instance()->get_user()->has('roles', $role);
    }
}
