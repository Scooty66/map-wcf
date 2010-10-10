<?php
require_once(WBB_DIR.'lib/acp/action/UpdateCounterAction.class.php');
require_once(WCF_DIR.'lib/data/gmap/GmapApi.class.php');

/**
 * Updates the map locations
 * 
 * @package     de.gmap.wbb
 * @author      Torben Brodt
 * @license	GNU General Public License <http://opensource.org/licenses/gpl-3.0.html>
 */
class UpdateMapAction extends UpdateCounterAction {
	public $action = 'UpdateMap';
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		$col = User::getUserOptionID('location');
		$api = new GmapApi();
		
		if(empty($col) || !$api->isActive()) {
			$this->calcProgress();
			$this->finish();
		}
		
		$col = "userOption".$col;
		
		// count board
		$sql = "SELECT		COUNT(*) AS count
			FROM		wcf".WCF_N."_user_option_value
			WHERE		".$col." != ''";
		$row = WCF::getDB()->getFirstRow($sql);
		$count = $row['count'];
		
		$i = 0;
		
		// cleanup disabled users
		$sql = 'DELETE FROM	wcf1_gmap_user
			USING		wcf1_gmap_user
			LEFT JOIN 	wcf1_user ON wcf1_gmap_user.userID = wcf1_user.userID
			WHERE		ISNULL(wcf1_user.userID)';
		WCF::getDB()->sendQuery($sql);
		
		// update
		$sql = "SELECT		userID,
					".$col." AS loc
			FROM		wcf".WCF_N."_user_option_value
			WHERE		".$col." != ''
			ORDER BY	userID ASC";
		$result = WCF::getDB()->sendQuery($sql, $this->limit, ($this->limit * $this->loop));
		while ($row = WCF::getDB()->fetchArray($result)) {
			$point = $api->search($row['loc']);
			
			if($point) {
				$sql = "REPLACE INTO	wcf".WCF_N."_gmap_user
							(userID, pt)
					VALUES		(".$row['userID'].", PointFromText('POINT(".$point['lon']." ".$point['lat'].")'))";
				WCF::getDB()->sendQuery($sql);
			}
			
			$i++;
		}
		
		if ($i == 0) {
			$this->calcProgress();
			$this->finish();
		}
		
		$this->calcProgress(($this->limit * $this->loop), $count);
		$this->nextLoop();
	}
}
?>
