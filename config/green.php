<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Green configuration.
 * 
 * @package     Green
 * @author      David Stutz
 * @copyright   (c) 2013 - 2016 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
return array(
    /**
     * Options concerning the logger.
     * The logger can log each action allowed and denied on all models, controllers and actions.
     */
    'logger' => array(
        'model' => FALSE,
        'controller' => FALSE,
    ),
);