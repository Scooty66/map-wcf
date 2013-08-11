<?php

/**
 * model to access api key
 *
 * @author	Torben Brodt
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 */
class GmapApiKey extends DatabaseObject {
	
	/**
	 * empty constructor, its not a real write model
	 */
	public function __construct() {
		
	}

	/**
	 * 
	 * @param $url string
	 * @return string
	 */
	public function getKey($domain = '') {
		$apikey = defined('GMAP_API_KEY') ? GMAP_API_KEY : '';
		$apikey = explode("\n", StringUtil::unifyNewlines($apikey));

		// this is the way, almost all of the woltlab users really enter their api key
		$apikey = $apikey[0];

		// this is the way, woltlab wants the users to enter their api key
		$apikey = explode(":", $apikey);
		if(count($apikey) == 2) {
			$apikey = $apikey[1];
		}

		return $apikey;
	}
}
