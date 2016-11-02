# Usage

## Usage with Models

Green is given a method and a model to execute the method on and executes it if the user is allowed to do so or throws a `Green_Exception`.

    // The model must be a ORM object.
    $model = ORM::factory('model_name'); 
    // The action is a string referencing the method to call.
    // Could be a ORM defined method or a custom one.
    $action = 'create'; 
    // An array of arguments to pass to the method:
    $args = array();

    try {
        // Pass model and actions plus arguments to Green.
        // Green will execute it or throw an exception.
        Green::instance()->allow($model, $action, $args);
    }
    catch (Green_Exception $e) {
        // User has not the right to do so ...
    }
    
As alternative:

    // Model can be an instance of the model or the object name of the model.
    if (Green::instance()->is_allowed($model, $method)) {
        // User is allowed to execute $method on $model.
    }

## Usage for Controllers

To control access to specific controllers the following code snippet can be used (for example within the template controller):

    // For example in the template controller's before method:
    try {
        Green::instance()->proceed();
    }
    catch (Green_Exception $e) {
        // User has not the right to access this page ...
    }

If the current user has not the right to access the requested controller a `Green_Exception` will be thrown. The use may be redirected or similar.
