<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Green filter abstract.
 *
 * @package     Green
 * @author      David Stutz
 * @copyright   (c) 2013 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Kohana_Green_Filter {
    
    /**
     * @var mixed   item to check for
     */
    protected $_item;
    
    /**
     * @var  string  contraint
     */
    protected $_constraint;
    
    /**
     * Get a filter for the specified rule on the current context.
     * 
     * @param   object  rule
     * @param   object  user
     * @return  object  filter
     */
    public static function factory($rule, $item) {
        
        $constraint = NULL;
        if (preg_match('#^[a-zA-Z]+:[a-zA-Z]+$#', $rule)) {
            $rule = explode(':', $rule);
            $constraint = array_pop($rule);
            $rule = array_shift($rule);
        }
        
        $filter = 'Green_Filter_' . ucfirst($rule);
        
        if (!class_exists($filter)) {
            throw new Green_Exception('Filter \'' . $rule . '\' not found.');
        }
        
        return new $filter($item, $constraint);
    }
    
    /**
     * The constructor expects a user for whom it will check the conditions.
     * 
     * @param   object  user
     * @param   mixed   item
     * @param   string  constraint
     */
    private function __construct($item, $constraint) {
        $this->_item = $item;
        $this->_constraint = $constraint;
    }
    
    /**
     * Check the condition represented by the filter on the saved user.
     * 
     * @return boolean  true on success
     */
    public function check() {
        return TRUE; // Should pretend being abstract.
    }
}
