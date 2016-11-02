# Rules

A rule consists of the following fields:

* type
* key
* rule

## Rule Types

There are two types of rules:

* controller: To control access to a controller.
* model: To control access to a model.

## Rule Key

The rule key defines the object on which the rule is added:

* For 'controller' rules: The controller in lower case like Kohana expects it for routes.
* For 'model' rules: The complete model name like `ORM::factory()` would expect it followed by `.` as separator and the method name to add the rule on.

## Filters

The rule itself is defined by a filter. A filter is given the current context - meaning the controller or model/method we want to grant access to - and decides whether access can be granted or not (a filter evaluates to `TRUE` or `FALSE`). If Green is asked to grant access on a given context it simply fetches all rules for this context and evaluates the corresponding filters. To grant access _one_ filter has to evaluate to `TRUE`. If no rules are found access will be denied. Beneath the context filters can rely on one additional parameter, which is given by the rule the following way:

    filter:parameter

Let's have a look on some basic rules:

    type        key             rule
    controller  administration  role:admin
    model       user.create     role:admin

The `role` filter expects as additional parameter a role name. The filter simply checks whether the current user has the given role. So the user only has access to the authentication controller if he is an admin.

## Custom Filters

For defining custom rules it is necessary to implement custom filters. Each filter has to implement the interface `Green_Filter` and thus provide the method `check()` which has to evaluate to `FALSE` or `TRUE`.

As example have a look at the `role` filter:

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

The current context  - the model as object or controller as string - is accessible by `$this->_item`. `$this->_constraint` holds the additional parameter or is `NULL` if no parameter was passed.

## Adding Rules

Rules can be added either manually through SQL statements or using ORM:

    $rule = ORM::factory('rule');
    $rule->values(array(
        'type' => $type,
        'key' => $key,
        'rule' => $rule,
    ));
    $rule->save();

This example expects that the provided model has been extended the following way:

    class Model_Rule extends Model_Green_Rule