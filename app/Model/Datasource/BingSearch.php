<?php
/**
 * Datasource for Bing Search
 *
 * Used for saving, selecting and deleting Subscribers
 *
 * Example URL:
 * http://api.bing.net/json.aspx?AppId=[AppId]&Query=[msn moneycentral]&Sources=[News]&Version=2.0&Market=[en-us]&Options=[EnableHighlighting]&News.Offset=0
 * 
 * The following request fields are mutually exclusive.
 * &News.LocationOverride=US.WA"
 * &News.Category=rt_Political"
 * &News.SortBy=Relevance
 * 
 * 
 * Use the $_schema in the model to supply custom values. The names must coincide with any POST variables added to the Bing API
 */

App::import('Core', 'HttpSocket');
App::uses('HttpSocket', 'Network/Http');

class BingSearch extends DataSource {

	/**
	 * The key that is needed by Bing to connect to the API.
	 * Keys can be requested in Bing's developer center.
	 * This value is populated from the database.php config file.
	 * @var <string> $apiKey
	 */
	var $appId;

	/**
	 * The url that is used for connecting to the Bing API.
	 * This depends on the Bing API version that is used.
	 * This value is populated from the database.php config file.
	 * @var <string> $baseUrl
	 */
	var $baseUrl = 'http://api.bing.net/';

	/**
	 * Construct our Datasource Class
	 * @param <type> $config
	 */
	public function __construct($config) {
		
		//set AppID
		$this->appId = 'qo4P91c/jPgrBy3AWR5yLIbmJKOPH11Dd2XGenvidMU=';
		
		//create socket connection
		$this->connection = new HttpSocket();
		parent::__construct($config);
	}

	/**
	 * ListSources()
	 *
	 * Required by CakePHP
	 * @return <type>
	 */
	public function listSources() {
		return array('BingSearch');
	}

	/**
	 * describe()
	 *
	 * Required by CakePHP
	 * @param <type> $model
	 * @return <type>
	 */
	function describe($model) {
		return $this->_schema['Bing'];
	}

	/**
	 * Search (Find)
	 *
	 * @param object $model
	 * @param array $queryData
	 * @return boolean
	 */
	function read($model, $queryData = array()) {
		
		//set the baseUrl from the class
		$url  = $this->baseUrl;
		
		// set the output type, default is json
		$url .= 'json';
		
		// Fucking extension.
		$url .= '.aspx';
		
		$get_params = array();
		
		//Set the AppID
		$queryData['conditions']['AppId'] = $this->appId;
		
		// Set our market & Version, we'll stick with 2.0 and en-us
		$queryData['conditions']['Version'] = '2.0';
		$queryData['conditions']['Market'] = 'en-us';
		
		//for custom values ...
		foreach($queryData['conditions'] as $key => $value) {
			if($key=='Sources' && is_array($value)) {
				$queryData['conditions'][$key] = $value[0];
			} else if($key=='Query') {
				// nothing?
				$queryData['conditions'][$key] = $value;
			} else {
				
			}
		}
		
		$response = json_decode($this->connection->get($url, $queryData['conditions']), true);
		
		if(isset($response['error'])) {
			return false;
		}
		
		return $response;
	}

	/**
	 * Override del method from model.php class, because it would block deleting when useTable = false and no records exists
	 * @param <type> $id
	 * @param <type> $cascade
	 * @return <type>
	 */
	function del($id = null, $cascade = true) {

		if (!empty($id)) {
			$this->id = $id;
		}
		$id = $this->id;

		if ( $this->beforeDelete($cascade)) {
			$db =& ConnectionManager::getDataSource($this->useDbConfig);
			if (!$this->Behaviors->trigger($this, 'beforeDelete', array($cascade), array('break' => true, 'breakOn' => false))) {
				return false;
			}
			$this->_deleteDependent($id, $cascade);
			$this->_deleteLinks($id);
			$this->id = $id;

			if (!empty($this->belongsTo)) {
				$keys = $this->find('first', array('fields' => $this->__collectForeignKeys()));
			}

			if ($db->delete($this)) {
				if (!empty($this->belongsTo)) {
					$this->updateCounterCache($keys[$this->alias]);
				}
				$this->Behaviors->trigger($this, 'afterDelete');
				$this->afterDelete();
				$this->_clearCache();
				$this->id = false;
				$this->__exists = null;
				return true;
			}
		}
		return false;
	}
}
?>