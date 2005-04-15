<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Robert Lemke (robert@typo3.org)
*  (c) 2005 Sebastian Kurfuerst (sebastian@garbage-group.de)
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
 * OBSOLETE!!!
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
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
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
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/timtaw/pi1/class.tx_timtaw_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/timtaw/pi1/class.tx_timtaw_pi1.php"]);
}

?>