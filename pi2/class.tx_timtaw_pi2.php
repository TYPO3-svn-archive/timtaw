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
 * @author	Ingmar Schlecht <ingmar@typo3.org>
 */


require_once(PATH_tslib."class.tslib_pibase.php");

class tx_timtaw_pi2 extends tslib_pibase {
	var $prefixId = "tx_timtaw_pi2";		// Same as class name
	var $scriptRelPath = "pi2/class.tx_timtaw_pi2.php";	// Path to this script relative to the extension dir.
	var $extKey = "timtaw";	// The extension key.

	/**
	 * Main function, called by TS

	 Array
(
    [TSFE_ADMIN_PANEL] => Array
        (
            [display_top] => 1
            [display_preview] =>
            [display_cache] =>
            [display_publish] =>
            [display_edit] => 1
            [edit_displayFieldIcons] => 0
            [edit_displayIcons] => 1
            [edit_editFormsOnPage] => 1
            [edit_editNoPopup] => 0
            [display_tsdebug] =>
            [display_info] =>
        )
Array
(
    [TSFE_EDIT] => Array
        (
            [cmd] => edit
            [record] => tt_content:2
        )

)
)

	 */


	
	
	function generateEditIcons($content,$conf) {
		if($GLOBALS['TSFE']->page['tx_timtaw_enable']) {
			
			$editIcons= '<form method="POST" action="index.php?wikiLogin=1&id='.$this->cObj->data['pid'].'&pageID='.$this->cObj->data['pid'].'">
			<input type="hidden" name="TSFE_ADMIN_PANEL[display_top]" value="1">
			<input type="hidden" name="TSFE_ADMIN_PANEL[display_preview]" value="">
			<input type="hidden" name="TSFE_ADMIN_PANEL[display_cache]" value="">
			<input type="hidden" name="TSFE_ADMIN_PANEL[display_publish]" value="">
			<input type="hidden" name="TSFE_ADMIN_PANEL[display_tsdebug]" value="">
			<input type="hidden" name="TSFE_ADMIN_PANEL[display_info]" value="">
			<input type="hidden" name="TSFE_ADMIN_PANEL[display_edit]" value="1">
			<input type="hidden" name="TSFE_ADMIN_PANEL[edit_displayFieldIcons]" value="0">
			<input type="hidden" name="TSFE_ADMIN_PANEL[edit_displayIcons]" value="1">
			<input type="hidden" name="TSFE_ADMIN_PANEL[edit_editFormsOnPage]" value="1">
			<input type="hidden" name="TSFE_ADMIN_PANEL[edit_editNoPopup]" value="0">
			
			<input type="hidden" name="TSFE_EDIT[cmd]" value="edit">
			<input type="hidden" name="TSFE_EDIT[record]" value="tt_content:'.$this->cObj->data['uid'].'"><input type="submit"></form>';
			
			#$editIcons = $this->cObj->editPanel('', '');
			/*$editIcons = '
									<table border="1" cellpadding="0" cellspacing="0" bordercolor="black" class="typo3-editPanel">
										<tr>
											<td nowrap="nowrap" bgcolor="#ABBBB4" class="typo3-editPanel-controls"><a href="index.php?id=1&wikiLogin=1&pageID=1&ADMCMD_editIcons=1&TSFE_EDIT[cmd]=edit&TSFE_EDIT[record]=tt_content:2&TSFE_ADMIN_PANEL[display_top]=1&TSFE_ADMIN_PANEL[display_edit]=1&TSFE_ADMIN_PANEL[edit_displayFieldIcons]=0&TSFE_ADMIN_PANEL[edit_displayIcons]=1&TSFE_ADMIN_PANEL[edit_editFormsOnPage]=1&TSFE_ADMIN_PANEL[edit_editNoPopup]=0" ><img src="t3lib/gfx/edit2.gif" width="11" height="12" hspace="2" border="0" title="" align="top" alt="" /></a><a href="#up"><img src="t3lib/gfx/button_up.gif" width="11" height="10" vspace="1" hspace="2" border="0" title="" align="top" alt="" /></a><a href="#down"><img src="t3lib/gfx/button_down.gif" width="11" height="10" vspace="1" hspace="2" border="0" title="" align="top" alt="" /></a><a href="#inv"><img src="t3lib/gfx/button_hide.gif" width="11" height="10" vspace="1" hspace="2" border="0" title="" align="top" alt="" /></a><a href="#new"><img src="t3lib/gfx/new_record.gif" width="16" height="12" vspace="1" hspace="2" border="0" title="" align="top" alt="" /></a><a href="#" onClick="if (confirm(unescape(\'Are you sure you want to delete this record%3F\'))){document.location.href="#del1"} return false;"><img src="t3lib/gfx/delete_record.gif" width="12" height="12" vspace="1" hspace="2" border="0" title="" align="top" alt="" /></a></td>

											<td nowrap="nowrap" bgcolor="#F6F2E6" class="typo3-editPanel-label"><font face="verdana" size="1" color="black">&nbsp;'.$this->cObj->data['header'].'&nbsp;</font></td>
										</tr>
									</table>
								';*/
			$content = '<div style="border:1px dotted black; padding: 5px;">'.$content.' </div><div style="width: 100%; text-align:right;">'.$editIcons.'</div>';
		}
		return $content;
	}
}


if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/timtaw/pi2/class.tx_timtaw_pi2.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/timtaw/pi2/class.tx_timtaw_pi2.php"]);
}

?>
