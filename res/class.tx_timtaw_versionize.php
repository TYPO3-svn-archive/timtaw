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
 * Plugin 'Versioning' for the 'timtaw' extension.
 *
 * @author	Sebastian Kurfuerst <sebastian@garbage-group.de>
 */



class tx_timtaw_versionize {
	var $prefixId = "tx_timtaw_versionize";		// Same as class name
	var $scriptRelPath = "res/class.tx_timtaw_versionize.php";	// Path to this script relative to the extension dir.
	var $extKey = "timtaw";	// The extension key.

	/**
	 * Main function, registering itself as a hook
	 * Process datamap is only called when records are saved, and not moved or deleted. => we create new version of content record
	 */
	function processDatamap_preProcessFieldArray ($incomingFieldArray, $table, $id, $tcemain)	{
		
		if(t3lib_div::_GP('id')) { // only set in frontend
			$pid = t3lib_div::_GP('id');
			$record = t3lib_BEfunc::getRecordRaw('pages','uid='.intval($pid),'tx_timtaw_enable,tx_timtaw_backenduser');
		
			if($record['tx_timtaw_enable']) {
				$GLOBALS['TYPO3_DB']->debugOutput = 1;

					// check if record changed
				$oldRecord = t3lib_BEfunc::getRecordRaw($table,'pid='.intval($pid).' AND uid='.intval($id), '*');
		
				$recordChanged = 0;
				foreach($incomingFieldArray as $key => $value) {
					if($oldRecord[$key] != $value) {
						$recordChanged = 1;
						break;
					}
				}
				
				if($recordChanged == 1) {
					$cmd[$table][$id]['version']['action'] = 'new';
					$cmd[$table][$id]['version']['label'] = time();
					$tcemain->cmdmap = $cmd;
					$tcemain->process_cmdmap();
					
						// swap both versions around
					unset($cmd);
					
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('MAX(t3ver_id)', $table, 't3ver_oid='.$id);
					$result = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', $table, 't3ver_oid='.$id.' AND t3ver_id='.$result['MAX(t3ver_id)']);
					$result = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
					
					$cmd[$table][$id]['version']['action'] = 'swap';
					$cmd[$table][$id]['version']['swapWith'] = $result['uid'];

					$tcemain->cmdmap = $cmd;
					$tcemain->process_cmdmap();
					
 				} // if: record changed
			} // if: timtaw enabled
		} // if: page id submitted
	} // function: processDatamap_preProcessFieldArray
}


if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/timtaw/res/class.tx_timtaw_versionize.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/timtaw/res/class.tx_timtaw_versionize.php"]);
}

?>