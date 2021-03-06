<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Green filter for checking whether the user owns the model.
 *
 * @package     Green
 * @author      David Stutz
 * @copyright   (c) 2013 - 2016 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Kohana_Green_Filter_Owner extends Green_Filter {
    
    /**
     * Check the condition represented by the filter on the saved user.
     * 
     * @return boolean  true on success
     */
    public function check() {
        if (isset($this->_item->user) AND $this->_item->user->loaded()) {
            return $this->_item->user->id == Red::instance()->get_user();
        }
        
        return TRUE;
    }
}
