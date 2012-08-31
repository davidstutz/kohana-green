# Rules

A rule consists of the following keys:

* type
* key
* rule

## Rule Types

There are three types of rules:

* controller: To control access to given controller.
* action: To control access to given controller/action combination.
* model: To control access to models.

## Rule Key

The rule key defines the object on which the rule is added:

* For 'controller' rules: The controller in lower case like Kohana expects it for routes.
* For 'action' rules: The controller/action combination, meaning the controller followed by '/' as separator and the action. Controller and action both like Kohana would expect it for routes.
* For 'model' rules: The complete model name like ORM::factory() would expect it followed by '.' as separator and the method name to add the rule on.

## Rule

The rule itself references a group existing in the configured hierarchy or some of the following keywords (only possible for 'model' typed rules):

* ':user': Access is granted to the user which added the model the rule is added on.
* ':group': Access is granted to the group of the user which added the model the rule is added on.

## Adding Rules

Rules can be added either manually through SQL statements or using ORM:

	$rule = ORM::factory('rule');
	$rule->values(array(
		'type' => $type,
		'key' => $key,
		'rule' => $rule,
	));
	$rule->save();

This example expects you to extend the provided model the following way:

	class Model_Rule extends Model_Green_Rule