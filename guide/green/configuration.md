# Configuration

See the configuration file, following configuration options are given:

## Whitelist

	'whitelist' => TRUE,
	
This configuration key defines what happens if rules for specific actions do not exist. If TRUE all actions of which there is no rule defined are allowed - if FALSE all actions where there is no rule defined are **not** allowed.

## Session

	'session' => array(
		'type' => 'database',
		'key'  => 'red_user',
	),
	
* type: The type for the Kohana session driver. See the Kohana documentation for more information about session drivers.
* key: The session key.

## Logging

	'logger' => array(
		'model' => new Yellow(Yellow::MODEL), // Init a logger object.
		'controller' => FALSE, // No controller logging.
	),
	
* model: Log model actions.
* controller: Log controller access.

Logging requires the [Yellow](https://github.com/davidstutz/kohana-yellow) module to be enabled or an other compatible module.