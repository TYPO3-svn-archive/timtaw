<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004 Robert Lemke (robert@typo3.org)
*       and Ingmar Schlecht (ingmar@typo3.org)
*
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
 * timtaw click module 1
 *
 * $Id$
 *
 * @author		Robert Lemke <robert@typo3.org>
 * @coauthor	Ingmar Schlecht <ingmar@typo3.org>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   67: class tx_timtaw_cm1 extends t3lib_SCbase
 *   74:     function main()
 *
 *              SECTION: Render functions
 *  129:     function getBEgroups()
 *  142:     function renderModuleContent()
 *
 *              SECTION: Processing functions
 *  235:     function doWikify ()
 *  333:     function getListOfSubpages_onedimensional($twodimensional)
 *  356:     function getListOfSubpages($id, $depth, $recursionLevel=0)
 *  403:     function getVersionsOfPage($id)
 *  421:     function printContent()
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

unset($MCONF);
require ('conf.php');
require ($BACK_PATH.'init.php');
require ($BACK_PATH.'template.php');
$LANG->includeLLFile('EXT:timtaw/cm1/locallang.xml');
require_once (PATH_t3lib.'class.t3lib_scbase.php');
require_once (PATH_t3lib.'class.t3lib_page.php');


class tx_timtaw_cm1 extends t3lib_SCbase {

	/**
	 * Main function of the module. Write the content to $this->content
	 *
	 * @return	[type]		...
	 */
	function main()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

		$this->pageSelectObj = t3lib_div::makeInstance('t3lib_pageSelect');

			// Draw the header.
		$this->doc = t3lib_div::makeInstance('mediumDoc');
		$this->doc->backPath = $BACK_PATH;
		$this->doc->form = '<form action="" method="POST">';

		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id, $this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;

		if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id))	{
			if ($BE_USER->user['admin'] && !$this->id)	{
				$this->pageinfo=array('title' => '[root-level]','uid'=>0,'pid'=>0);
			}

			$this->content .= $this->doc->startPage($LANG->getLL('title'));
			$this->content .= $this->doc->header($LANG->getLL('title'));
			$this->content .= $this->doc->spacer(5);
			$this->content .= $this->doc->section('',$LANG->sL('LLL:EXT:lang/locallang_core.php:labels.path').': '.t3lib_div::fixed_lgd_pre($this->pageinfo['_thePath'],50));
			$this->content .= $this->doc->divider(5);

			switch (t3lib_div::GPvar('action')) {
				case 'wikify':
					$this->content .= $this->doWikify();
				break;
				case 'dewikify':
					$this->content .= $this->doDeWikify();
				break;
				default:
					$this->content .= $this->renderModuleContent();
			}
		}
		$this->content .= $this->doc->spacer(10);
	}





	/*******************************************
	 *
	 * Render functions
	 *
	 *******************************************/



	/**
	 * get backend groups enabled for timtaw
	 *
	 * @return	array		returns names and UIDs of all backend groups: uid => name
	 */
	function getBEgroups() {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,title', 'be_groups', 'deleted=0 AND hidden=0 AND tx_timtaw_enable=1', '' ,'');

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
			$groups[$row['uid']] = $row['title'];
		}
		return $groups;
	}
	/**
	 * Renders module content
	 *
	 * @return	void
	 */
	function renderModuleContent()	{
		global $LANG, $BE_USER;
		$content = '';

			// render BE group list as group for wiki users
		$beGroups = $this->getBEgroups();

		$beGroupOptions = '';
		if(is_array($beGroups)) {
			foreach($beGroups as $uid =>$title) {
				$beGroupOptions .= '<option value="'.$uid.'">'.$title.'</option>';
			}
			$beGroupOptions = '<select name="beGroup">'.$beGroupOptions.'</select>';
		}

			// look if page is already wikified to unwikify it.
		$record = t3lib_BEfunc::getRecordRaw('pages','uid='.intval($this->id),'tx_timtaw_enable');
		$alreadyWikified = $record['tx_timtaw_enable'];

			// Create an array of sub page uids from the current page:
		$affectedPagesArr = $this->getListOfSubpages($this->id, 100);
		#$affectedPagesArr[$this->id] = $this->id;
			// Prepare a table header:
		$tableRows = array();
		$tableRows[] = '
			<tr class="bgColor5 tableheader">
				<td>UID</td>
				<td>Title</td>
				<td>Version</td>
			</tr>
		';
			// Traverse the pages found and list them in a table:
		foreach ($affectedPagesArr as $page) {
			if(!is_array($page)) {
				$row = t3lib_beFunc::readPageAccess($page, $BE_USER->getPagePermsClause(1));

				$tableRows[] = '
					<tr class="bgColor4">
						<td>'.$row['uid'].'</td>
						<td colspan="2">'.htmlspecialchars($row['title']).'</td>
					</tr>
				';
			} else { // if there are versions...
				$subContent = '';
				foreach($page as $uid) {
					$row = t3lib_beFunc::readPageAccess($uid, $BE_USER->getPagePermsClause(1));

					$subContent .= 'Version #'.$row['t3ver_id'].': '.$row['t3ver_label'].'<br>';

					if ($row['pid'] != -1) {
						$activeRecord = $row;
					}

				}
				$tableRows[] = '
							<tr class="bgColor4">
								<td>'.$activeRecord['uid'].'</td>
								<td>'.htmlspecialchars($activeRecord['title']).'</td>
								<td>'.$subContent.'</td>
							</tr>
						';
			}

		}

			// Assemble the whole output:
		$content .= '
			So, you want to TimTa'.($alreadyWikified?'w<b>de</b>':'').'Wikify this page?<br /><br />
			'.$beGroupOptions.'<input type="submit" value="'.($alreadyWikified?'Dewikify':'Wikify').'!" />
			<input type="checkbox" name="includesubpages" value="1" '.(t3lib_div::GPvar('includesubpages') ? ' checked="checked"' : '').'/> Include subpages<br />
			<input type="hidden" name="action" value="'.($alreadyWikified?'dewikify':'wikify').'" />
		';

		$content .= $this->doc->section('Affected pages', '<table border="0" cellpadding="1" cellspacing="1" class="lrPadding">'.implode('',$tableRows).'</table>',0,1);

		return $content;
	}






	/*******************************************
	 *
	 * Processing functions
	 *
	 *******************************************/
	/**
	 * Wikifies a page and all subpages
	 *
	 * @return	void
	 */
	function doWikify () {
		global $TYPO3_DB, $BE_USER;

		$content = '';
		$subLevelsToInclude = (intval (t3lib_div::GPvar('includesubpages')) > 0) ? 100 : 0;

			// Create a new wiki backend user:
		$username = 'timtaw_' . md5(microtime().t3lib_div::getIndpEnv('REMOTE_ADDR'));
		$password = 'timtaw_' . md5(t3lib_div::getIndpEnv('REMOTE_ADDR').'pw'.microtime());

			// check groups if they are wiki-enabled groups
		$beGroup = intval(t3lib_div::_GP('beGroup'));
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'be_groups', 'deleted=0 AND hidden=0 AND tx_timtaw_enable=1 AND uid='.$beGroup, '' ,'');

			// if group is not valid or empty, output an error message
		if(!$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res) || empty($beGroup))	{
			return 'Please create a wiki-enabled backend user group!';
		}

			// set UC to the correct admin panel options
		$uc_new['TSFE_adminConfig'] = Array(
			'display_top'	=> 1,
			'display_edit' => 1,
			'edit_displayFieldIcons' => 0,
			'edit_displayIcons' => 1,
			'edit_editFormsOnPage' => 1,
			'edit_editNoPopup' => 0
		);
		$uc=serialize($uc_new);

		$res = $TYPO3_DB->exec_insertQuery (
			'be_users',
			array(
				'username' => $username,
				'password' => $password,
				'db_mountpoints' => $this->id,
				'realName' => 'TimTaw Backend User',
				'disableIPlock' => 1,
				'usergroup' => $beGroup,
				'uc' => $uc
			)
		);

		if (!$res) return 'Error while creating backend user';

		$newBackendUserId = $TYPO3_DB->sql_insert_id();
		$content .= 'Created a new backend user "'.$username.'" (UID: '.$newBackendUserId.')<br />';

			// Switch ownership of all requested pages to the new backend user if the currently logged in backend user is the owner
		if ($subLevelsToInclude) {
			$affectedPagesArr = $this->getListOfSubpages_onedimensional($this->getListOfSubpages($this->id, $subLevelsToInclude));
		}
		$affectedPagesArr[$this->id] = $this->id;

		foreach ($affectedPagesArr as $uid) {
			$row = $BE_USER->user['admin'] ? t3lib_beFunc::getRecord('pages', $uid) : t3lib_beFunc::readPageAccess($uid, '(pages.perms_userid = '.$BE_USER->user['uid'].' AND pages.perms_user & 1 = 1)');
			if (is_array ($row)) {
				$TYPO3_DB->exec_updateQuery (
					'pages',
					'uid='.$uid,
					array (
						'perms_userid' => $newBackendUserId,
						'tx_timtaw_enable' => 1,
						'tx_timtaw_backenduser' => $newBackendUserId,
						'tx_timtaw_includesubpages' => ($subLevelsToInclude?1:0)
					)
				);
			}
		}

			// Make main page ($this->id) wiki editable and set "include subpages" and "wiki backend user" flags:
		$fieldsAndValues = array (
			'tx_timtaw_enable' => 1,
			'tx_timtaw_includesubpages' => ($subLevelsToInclude > 0) ? 1 : 0,
			'tx_timtaw_backenduser' => $newBackendUserId,
		);
		$res = $TYPO3_DB->exec_UPDATEquery (
			'pages',
			'uid='.$this->id,
			$fieldsAndValues
		);

		return $content;
	}




	/**
	 * ****************************************
	 *
	 * Helper functions
	 *
	 * ******************************************/
	 *
	 * @param	[type]		$twodimensional: ...
	 * @return	[type]		...
	 */
	 function getListOfSubpages_onedimensional($twodimensional) {
		 foreach($twodimensional as $value1) {
			 if(is_array($value1)) {
				 foreach($value1 as $value2) {
					 $onedimensional[] = $value2;
				 }
			 } else {
				 $onedimensional[] = $value1;
			 }
		 }
	 }
	/**
	 * Generates a list of Page-uids from $id. List does include $id itself
	 * The only pages WHICH PREVENTS DECENDING in a branch are
	 *    - deleted pages,
	 *    - pages of an unsupported page type
	 *
	 * @param	integer		$id: The id of the start page from which point in the page tree to decend.
	 * @param	integer		$depth: The number of levels to decend. If you want to decend infinitely, just set this to 100 or so, because 100 is almost infinity, eh?
	 * @param	array		Array of IDs from previous recursions. In order to prevent infinite loops with mount pages.
	 * @param	integer		Internal: Zero for the first recursion, incremented for each recursive call.
	 * @return	array		Returns an array of page uids
	 */
	function getListOfSubpages($id, $depth, $recursionLevel=0)	{
		global $TYPO3_DB;

		$depth = intval($depth);
		$id = intval($id);
		$subPagesArr = array();

		if ($id)	{
			if ($recursionLevel == 0) {
					// Check start page and return blank if the start page was NOT found at all:
				if (!$this->pageSelectObj->getRawRecord('pages',$id,'uid'))	return '';
			}

			if($versions = $this->getVersionsOfPage($id)) {
				$subPagesArr[$id] = $versions;
				$subPagesArr[$id][$id] = $id; // add current page, too
			} else {
					// Add the current ID to the array of IDs:
				$subPagesArr[$id] = $id;
			}

				// Find subpages:
			if ($depth > $recursionLevel)	{
				$res = $TYPO3_DB->exec_SELECTquery('uid,doktype,php_tree_stop', 'pages', 'pid='.$id.' AND doktype IN (1,2,5) AND deleted=0', '' ,'sorting');

				while ($row = $TYPO3_DB->sql_fetch_assoc($res))	{
					$next_id = $row['uid'];

						// Add ID to list:
					$subPagesArr[$next_id] = $next_id;

						// Next level:
					if ($depth>$recursionLevel+1 && !$row['php_tree_stop'])	{
							// Call recursively
						$subPagesArr = t3lib_div::array_merge ($subPagesArr, $this->getListOfSubpages ($next_id, $depth, $recursionLevel+1));
					}
				}
			}
		}
			// Return list of subpages:
		return $subPagesArr;
	}

	/**
	 * @param	[type]		$id: ...
	 * @return	[type]		...
	 */
	function getVersionsOfPage($id) {
		$id = intval($id);
		# $GLOBALS['TYPO3_DB']->debugOutput = TRUE;

		if($id > 0) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,doktype,php_tree_stop', 'pages', ' t3ver_oid = '.$id.' AND doktype IN (1,2,5) AND deleted=0');
			while ($rowVersion = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
				$subPagesArr[$rowVersion['uid']] = $rowVersion['uid'];
			}
			return $subPagesArr?$subPagesArr:array();
		} else return array();
	}

	/**
	 * The classic print-my-content function, echoing the collected content.
	 *
	 * @return	void
	 */
	function printContent()	{
		$this->content.=$this->doc->endPage();
		echo $this->content;
	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/timtaw/cm1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/timtaw/cm1/index.php']);
}

	// Make instance:
$SOBE = t3lib_div::makeInstance('tx_timtaw_cm1');
$SOBE->init();


$SOBE->main();
$SOBE->printContent();

?>