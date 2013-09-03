<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Green filter for checking whether the model is the current user.
 *
 * @package     Green
 * @author      David Stutz
 * @copyright   (c) 2013 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Kohana_Green_Filter_Self extends Green_Filter {
    
    /**
     * Check the condition represented by the filter on the saved user.
     * 
     * @return boolean  true on success
     */
    public function check() {
        return $this->_item instanceof Model_User AND $this->_item->id == Red::instance()->get_user()->id;
    }
}
