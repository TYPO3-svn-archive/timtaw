<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Robert Lemke (robert@typo3.org)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'Diff view' for the 'timtaw' extension.
 *
 * @author	Sebastian Kurfuerst <sebastian@garbage-group.de>
 */


require_once(PATH_tslib."class.tslib_pibase.php");

class tx_timtaw_pi1 extends tslib_pibase {
	var $prefixId = "tx_timtaw_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_timtaw_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "timtaw";	// The extension key.

	/**
	 * Main function of the script generating the diff view for a page
	 *
	 * @param	[type]		$content: ...
	 * @param	array		$conf: ...
	 * @return	string		HTML Content
	 */
	function main($content,$conf)	{
		$content = '';
		switch($this->piVars['cmd']) {
			case 'single':
				if($this->piVars['table'])
					$table = $this->piVars['table'];
				else
					$table = 'tt_content';
				if(!$this->piVars['uid']) {
					return 'error';
				}
				$history = $this->createRecordHistory(intval($this->piVars['uid']), $table);
				break;
			default:
				$history = $this->createPageHistory($this->cObj->data['uid']);
				break;
		}

		$content = $this->formatHistory($history);

		return $this->pi_wrapInBaseClass($content);
	}

	/**
	 * Creates the "history" for a single record, parsing the versions of this record
	 *
	 * @param	integer		$uid: ID of record to parse
	 * @param	string		$table: database table of the record
	 * @return	array		Array with history information
	 */
	function createRecordHistory($uid, $table) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('t3ver_oid,t3ver_id,t3ver_label,uid',$table,'t3ver_oid='.intval($uid).' AND deleted!=1');
		while($record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$label = explode(', ', $record['t3ver_label']);
			$timestamp = $label[0];

			$out[$timestamp]['uid'] = $record['uid'];
			$out[$timestamp]['table'] = $table;
			$out[$timestamp]['oid'] = $record['t3ver_oid'];
			$out[$timestamp]['version'] = $record['t3ver_id'];			$out[$timestamp]['action'] = $label[1];
			$out[$timestamp]['ip'] = $label[2];
			$out[$timestamp]['time'] = $label[0];
		}

		return $out;
	}

	/**
	 * creates the history of a whole page. At first, the history of the page itself is generated,  and afterwards the history of content elements is created and merged afterwards
	 *
	 * @param	integer		$pid: Page ID
	 * @return	array		array with sorted history
	 */
	function createPageHistory($pid) {
		$pid = intval($pid);
			// create history of page
		$history[] = $this->createRecordHistory($pid, 'pages');
			// create history of CEs
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid','tt_content','pid='.$pid.' AND deleted!=1');
		while($record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$history[] = $this->createRecordHistory($record['uid'], 'tt_content');
		}
			// merge historys
		$historyCombined = $this->mergeHistory($history);
		return $historyCombined;
	}

	/**
	 * Merges multiple historys into one, based on the timestamp
	 *
	 * @param	array		$historys: multi-dimensional history array
	 * @return	array		Array with one history ready to parse
	 */
	function mergeHistory($historys) {
		$mergedHistory = $historys[0];
		for($i = 1; is_array($historys[$i]); $i++) {
			foreach($historys[$i] as $key => $value) {
				while(isset($mergedHistory[$key])) {
					$key++;
				}
				$mergedHistory[$key] = $value;
			}
		}

		ksort($mergedHistory);
		reset($mergedHistory);

		$i = 0;
		foreach($mergedHistory as $key => $value) {
			$mergedHistory_unified[$i] = $value;
			$i++;
		}
		return $mergedHistory_unified;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$inputData: ...
	 * @return	[type]		...
	 */
	function formatHistory($inputData) {
		debug($inputData);
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/timtaw/pi1/class.tx_timtaw_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/timtaw/pi1/class.tx_timtaw_pi1.php"]);
}

?>