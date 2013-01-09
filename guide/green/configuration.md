# Configuration

See the configuration file, following configuration options are given:

## Hierarchy

	'hierarchy' => array(
		'login' => 0,
		'user' => 10,
		'admin' => 20,
		'super' => 30,
	),
	
The hierarchy contains all within Green used groups as keys with their associated hierarchy value as value. The higher the value the higher the rank within the hierarchy, meaning a group with higher value is superior to groups with lower values.

## Whitelist

	'whitelist' => TRUE,
	
This configuration key defines what happens if rules for specific actions do not exist. If TRUE all actions of which there is no rule defined are allowed. if FALSE all actions where there is no rule defined are **not** allowed.

## Session

	'session' => array(
		'type' => 'database',
		'key'  => 'red_user',
	),
	
* type: The type for the Kohana session driver. See the Kohana documentation for more information about session drivers.
* key: The session key.

## Logging

	'logger' => array(
		'model' => new Yellow('log_model'), // Init a logger object.
		'controller' => FALSE, // No controller logging.
	),
	
* model: Log model actions.
* controller: Log controller access.

Logging requires the [Yellow](https://github.com/Phrax1337/kohana-yellow) module to be enabled or an other compatible module.