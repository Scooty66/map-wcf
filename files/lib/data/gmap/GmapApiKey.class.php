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
		$lines = defined('GMAP_API_KEY') ? GMAP_API_KEY : '';
		// @todo unify new lines, explode by line, strip leading domain
		return $line;
	}
}
