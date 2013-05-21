# Configuration

See the configuration file, following configuration options are given:

## Logging

	/**
	 * Options concerning the logger.
	 * The logger can log each action allowed and denied on all models, controllers and actions.
	 */
	'logger' => array(
		'model' => new Yellow(Yellow::MODEL), // ENable logging by providing a logger.
		'controller' => FALSE,
	),
	
* model: Log model actions using the given logger.
* controller: Log controller access using the given logger.

Logging requires the [Yellow](https://github.com/davidstutz/kohana-yellow) module to be enabled or an other compatible module.