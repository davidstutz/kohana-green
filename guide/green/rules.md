# Rules

A rule consists of the following keys:

* type
* key
* rule

## Rule Types

There are two types of rules:

* controller: To control access to given controller.
* model: To control access to models.

## Rule Key

The rule key defines the object on which the rule is added:

* For 'controller' rules: The controller in lower case like Kohana expects it for routes.
* For 'model' rules: The complete model name like ORM::factory() would expect it followed by '.' as separator and the method name to add the rule on.

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