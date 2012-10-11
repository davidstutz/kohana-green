# Logging

The access of models, controllers and actions can be logged. Currently only logging model access is possible. Support for logging controllers and actions will be added in future updates.

To enable logging:

	'logger' => array(
		'model' => TRUE,
		'controller' => FALSE,
		'action' => FALSE,
	),
	
Now model logging will be enabled. For more documentation see the documentation of the [Yellow](https://github.com/Phrax1337/kohana-yellow) module.