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
 * Plugin 'Timtawtoolbar' for the 'timtaw' extension.
 *
 * @author	Sebastian Kurfuerst  <sebastian@garbage-group.de>
 * @author  Ingmar Schlecht <ingmar@typo3.org>
 */


require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_timtaw_pi2 extends tslib_pibase {
	var $prefixId = 'tx_timtaw_pi2';		// Same as class name
	var $scriptRelPath = 'pi2/class.tx_timtaw_pi2.php';	// Path to this script relative to the extension dir.
	var $extKey = 'timtaw';	// The extension key.

	/**
	 * Main function, called by TS
	 *
	 * @param	[type]		$content: ...
	 * @param	[type]		$conf: ...
	 * @return	string	content
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		if($this->timtawEnabled()) {

			switch($this->piVars['cmd']) {
				case 'revision':
					$content = $this->generateRevisionList();
					break;
				default:
					$content = $this->generatePagePanel();
					break;
			}

			return $this->pi_wrapInBaseClass($content);
		} else {
			return '';
		}
	}

	
	/*************************
	 *
	 * Revision view
	 *
	 *************************/
	 
	/**
	 * generates revision list of records or whole pages. can be configured by setting uid
	 *
	 * @return	string		HTML
	 */
	function generateRevisionList() {
		$content = '';
		if($this->piVars['uid']) {
			if($this->piVars['table']) {
				$table = $this->piVars['table'];
			} else {
				$table = 'tt_content';
			}
			$history = $this->createRecordHistory(intval($this->piVars['uid']), $table);
		} else {
			$history = $this->createPageHistory($this->cObj->data['uid']);
		}

		$content = $this->formatHistory($history);
		return $content;
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
			$out[$timestamp]['pid'] = $label[2];
			$out[$timestamp]['ip'] = $label[3];
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
			$history[] = $this->createRecordHistory($record['uid'], 'tt_content');;
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
		foreach($historys as $i => $singleHistory) {
			if(is_array($singleHistory)) {
				foreach($singleHistory as $key => $value) {
					while(isset($mergedHistory[$key])) {
						$key++;
					}
					$mergedHistory[$key] = $value;
				} // inner foreach
			} // is_array singleHistory
		} // outer foreach
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
	 * Formats a history array with a template and outputs it.
	 *
	 * @param	array		$inputData: multidimensional array with rows and cols
	 * @return	string		HTML output
	 */
	function formatHistory($inputData) {

		$this->templateCode =  $this->cObj->fileResource($this->conf["templateFile"]);

			// get subparts from template
    $t = array();
    $t['total'] = $this->cObj->getSubpart($this->templateCode,
      '###REVISION###');
    $t['tableline'] = $this->cObj->getSubpart($this->templateCode,
      '###TABLELINE###');
		$content_row = '';

		foreach($inputData as $singleRecord) {
			if(empty($singleRecord['time'])) {
				continue;
			}

			$markerArray['###TIME###'] = strftime($this->pi_getLL('timeFormat'), $singleRecord['time']);
			$markerArray['###ACTION###'] = $this->pi_getLL('action_'.$singleRecord['action']);
			$markerArray['###IP###'] = $singleRecord['ip'];

			if($singleRecord['table'] == 'pages') {
				$id = $singleRecord['uid'];
				$params = array('wikiLogin' => 1);

			} else {
				$id = $GLOBALS['TSFE']->id;
				$params = array(
						'ADMCMD_vPrev['.$singleRecord['table'].'%3a'.$singleRecord['oid'].']' => $singleRecord['uid'],
						'wikiLogin' => 1
				);
			}
			$markerArray['###SHOW###'] = $this->pi_linkToPage($this->pi_getLL('showVersion'),$id, '',$params);
			$content_row .=
          $this->cObj->substituteMarkerArrayCached($t['tableline'],
          $markerArray, array(), array());
		}

		$output = $this->cObj->substituteMarkerArrayCached($t['total'], Array(), Array('###TABLELINE###' => $content_row), Array() );


		return $output;
	}

	
	/*************************
	 *
	 * helper functions
	 *
	 *************************/
	 
	 
	/**
	 * Removes certain content elements on the page when needed, is calles by tt_content.stdWrap.postUserFunc
	 *
	 * @param	string		$content: the content element
	 * @param	array		$conf: the configuration array
	 * @return	string		returns modified content
	 */
	function removeContentElements($content, $conf) {
		if($this->timtawEnabled()) {
			$GPvars = t3lib_div::_GP($this->prefixId);
			
				// no content elements are outputted on the revision view
			if($GPvars['cmd'] == 'revision') {
			#	return '';
			}
			
			return $content;
		} else {
			return $content;
		}
	}
	
	
	/**
	 * Checks if TimTaw is enabled at the current page
	 *
	 * @return	boolean		1 if enabled, 0 if not enabled
	 */
	function timtawEnabled() {
		$pid = t3lib_div::_GP('id');
		$record = t3lib_BEfunc::getRecordRaw('pages','uid='.intval($pid),'tx_timtaw_enable,tx_timtaw_backenduser');

		if($record['tx_timtaw_enable'] && !$_COOKIE['be_typo_user']) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * Generates panel for page. At the moment, just the "edit this page" link is generated here, but other links are supposed to be generated here as well (revision view, ...)
	 *
	 * @return	string		HTML
	 */
	function generatePagePanel() {
		return  $this->pi_linkTP(
			$this->pi_getLL('editPage'),
			array('wikiLogin' => 1)
			);
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/timtaw/pi2/class.tx_timtaw_pi2.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/timtaw/pi2/class.tx_timtaw_pi2.php']);
}

?>
