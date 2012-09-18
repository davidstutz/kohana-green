# Usage

## Singleton

Green is based on the Singleton pattern:

	Green::instance()->some_method();
	
The current instance can be thought as representing the current user logged in.

## Usage with Models

Green is used to allow and control the access to models. Green follows the following principle: The model and the method to run are given to Green. Green then, following the defined rules, executes the method on the model if the current user is allowed to, or throws an Green_Access_Exception.

Thus following code fragments are obsolet:

	if (user has right to do)
	{
		do action on method
	}
	
And is replaced by the following construct:

	// The model must be a ORM object.
	$model = ORM::factory('model_name'); 
	// The action is a string referencing the method to call.
	// Could be a ORM defined method or a custom one.
	$action = 'create'; 
	// An array of arguments to pass to the method:
	$args = array();

	try
	{
		// Pass model and actions plus arguments to Green.
		// Green will execute it or throw an exception.
		Green::instance()->allow($model, $action, $args);
	}
	catch (Green_Access_Exception $e)
	{
		// User has not the right to do so ...
	}

## Usage for Controllers and Actions

To control access to specific controllers or actions the following code snippets can be used:

	// For example in the template controller:
	try
	{
		Green::instance()->proceed();
	}
	catch (Green_Access_Exception $e)
	{
		// User has not the right to access this page ...
	}

If the current user has not the right to access the controller or action a Green_Access_Exception will be thrown. The use may be redirected or similar.

## Checking Hierarchy

Green allows to manual check if the current user has at minimum a certain level in the hierarchy:

	// If current user is 'high enough' do something.
	// Checks if the current user has at minumum the level of a 'user'.
	// So if the users group value is higher than the one of 'user' the if statement is executed.
	if (Green::instance()->high_enough('user'))
	{
		// Do something...
	}
