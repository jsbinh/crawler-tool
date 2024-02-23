<?php

/**
 * Licensed under The GPL V3 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @package       GoogleAPI.Controller.Component
 * @license       http://opensource.org/licenses/GPL-3.0 GPL V3 License
 */

App::uses('Component', 'Controller');
Configure::load('GoogleAPI.core');

class GoogleAPIComponent extends Component
{
	public $Service = array();

	public $Auth = array();
	
	public $settings = array();

	/**
	 * Default options
	 */
	private $_default = array(
		'client' => array(
			'ApplicationName' => 'Browser key 1',
			'DeveloperKey' => 'AIzaSyBM-yfyBNQ4AgnJjGZMgpQ3DBlY1O8g6Go'
		)
	);
	
	public function __construct($collection, $settings = array()) {
		// Merge settings
		$this->settings = array_merge($this->_default, Configure::read('GoogleAPI') , $settings);
		// Create a new Google Client
		$this->googleClient = $this->new_Google_Client();
		// If success
		if (isset($this->googleClient)) {
			// For each Service found in settings
			foreach ($this->settings['Service'] as $service_name) {
				// Import Service API
				self::import("Google/Service/$service_name");
				// Create a new instance of Service
				$className = "Google_Service_$service_name";
				$this->Service[$service_name] = new $className($this->googleClient);
			}
		}
	}
	
	/**
	 * Create a new Google Client
	 * 
	 * @param boolean $useConfig If he use the config (core.php) for define clients options or not
	 * 
	 * @return Google_Client or null
	 */
	public function new_Google_Client($useConfig = true) {
		// Import Google/Client API and if success...
		if (self::import('Google/Client')) {
			// Create a new Google_Client
			$googleClient = new Google_Client();
			// If I use config
			if ($useConfig and is_array($this->settings['client'])) {
				// For each client.options
				foreach ($this->settings['client'] as $key => $value) {
					$method = "set$key";
					// Check if the option is valid and then define it
					if (method_exists($googleClient, $method)) {
						$googleClient->$method($value);
					}
				}
			}
			return $googleClient;
		}
		return null;
	}
	
	/**
	 * Import a file from GoogleAPI
	 *
	 * Import a file from GoogleAPI and redefine include path temporarily to avoid problems when it included other files
	 * 
	 * @param string $file The name of file, for example import('Google/Client') will load GoogleAPI/src/Google/Client.php
	 * 
	 * @return boolean success
	 */
	public static function import($file) {
		// Windows Case...
		if (DS != '/') {
			$file = str_replace('/', DS, $file);
		}
		// TODO Calculate the include_path dynamically.
		$include_path = APP . 'Plugin' . DS . 'GoogleAPI' . DS . 'Vendor' . DS . 'GoogleAPI' . DS . 'src';
		// Save current include path
		$old_include_path = get_include_path();
		// Set new include path
		set_include_path($include_path);
		// Load api file
		$return = App::import('Vendor', "GoogleAPI.$file", array(
			'file' => 'GoogleAPI' . DS . 'src' . DS . $file . '.php'
		));
		// Restore include path
		set_include_path($old_include_path);
		// Return App::import return
		return $return;
	}
}
