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
 * @author  Sebastian Kurfuerst <sebastian@garbage-group.de>
 */



class tx_timtaw_login {
	var $prefixId = 'tx_timtaw_login';		// Same as class name
	var $scriptRelPath = 'res/class.tx_timtaw_login.php';	// Path to this script relative to the extension dir.
	var $extKey = 'timtaw';	// The extension key.

	
	function getFEUserID() {
		#debug($GLOBALS['TSFE']->fe_user->user['uid']);
		#debug($GLOBALS['TSFE']);
		#debug($GLOBALS);
	}
	
	
	function userLookup ($params, $parent = '') {

		if($params['pObj']->session_table == 'be_sessions' && $GLOBALS['TSFE']->fe_user->user['tx_timtaw_begroup']) {

			$user = array (
				'uid' => 1000+$GLOBALS['TSFE']->fe_user->user['uid'], 
				'pid' => '0', 
				'usergroup' => $GLOBALS['TSFE']->fe_user->user['tx_timtaw_begroup'],
				'admin' => '1', // muss natürlich noch geändert werden
			);
			
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','be_sessions','ses_id='."'".$params['pObj']->id."'");
			$sessionRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			
			$user = array_merge($GLOBALS['TSFE']->fe_user->user,$user,$sessionRow);

			$user['ses_id'] = $params['pObj']->id;
			$params['pObj']->user = $user;
			
			#echo serialize($params['pObj']->user);
			#$unser = 'a:36:{s:6:"ses_id";s:32:"22474d8b5e173176a7cc05b1a1d4935b";s:8:"ses_name";s:12:"be_typo_user";s:10:"ses_userid";s:1:"1";s:10:"ses_tstamp";s:10:"1110325845";s:8:"ses_data";s:0:"";s:10:"ses_iplock";s:9:"127.0.0.1";s:12:"ses_hashlock";s:9:"135010625";s:3:"uid";s:1:"1";s:3:"pid";s:1:"0";s:6:"tstamp";s:10:"1049192920";s:8:"username";s:5:"admin";s:8:"password";s:32:"5f4dcc3b5aa765d61d8327deb882cf99";s:5:"admin";s:1:"1";s:9:"usergroup";s:0:"";s:7:"disable";s:1:"0";s:9:"starttime";s:1:"0";s:7:"endtime";s:1:"0";s:4:"lang";s:0:"";s:5:"email";s:14:"your@email.com";s:14:"db_mountpoints";s:0:"";s:7:"options";s:1:"0";s:6:"crdate";s:1:"0";s:9:"cruser_id";s:1:"0";s:8:"realName";s:9:"Your Name";s:8:"userMods";s:0:"";s:2:"uc";s:5156:"a:23:{s:14:"interfaceSetup";s:0:"";s:10:"moduleData";a:19:{s:8:"tools_em";a:6:{s:8:"function";s:1:"1";s:9:"listOrder";s:3:"cat";s:15:"display_details";s:1:"1";s:13:"singleDetails";s:4:"info";s:4:"fe_u";s:7:"ingmars";s:4:"fe_p";s:6:"snowww";}s:8:"web_list";a:0:{}s:16:"xMOD_alt_doc.php";a:1:{s:12:"showPalettes";s:2:"on";}s:11:"alt_doc.php";a:2:{i:0;a:3:{s:32:"d0476293dcc1f60fdc9703142ee2f7ca";a:3:{i:0;s:8:"Trainers";i:1;a:7:{s:4:"edit";a:1:{s:5:"pages";a:1:{i:8;s:4:"edit";}}s:7:"defVals";N;s:12:"overrideVals";N;s:11:"columnsOnly";N;s:7:"disHelp";N;s:6:"noView";N;s:24:"editRegularContentFromId";N;}i:2;s:99:"&edit[pages][8]=edit&defVals=&overrideVals=&columnsOnly=&disHelp=&noView=&editRegularContentFromId=";}s:32:"b9911952c7c90e154ebfab0ceae7f21e";a:3:{i:0;s:9:"Last week";i:1;a:7:{s:4:"edit";a:1:{s:5:"pages";a:1:{i:12;s:4:"edit";}}s:7:"defVals";N;s:12:"overrideVals";N;s:11:"columnsOnly";N;s:7:"disHelp";N;s:6:"noView";N;s:24:"editRegularContentFromId";N;}i:2;s:100:"&edit[pages][12]=edit&defVals=&overrideVals=&columnsOnly=&disHelp=&noView=&editRegularContentFromId=";}s:32:"3bb749418fb1aeb44d65857e7c39f50f";a:3:{i:0;s:6:"Log in";i:1;a:7:{s:4:"edit";a:1:{s:5:"pages";a:1:{i:19;s:4:"edit";}}s:7:"defVals";N;s:12:"overrideVals";N;s:11:"columnsOnly";N;s:7:"disHelp";N;s:6:"noView";N;s:24:"editRegularContentFromId";N;}i:2;s:100:"&edit[pages][19]=edit&defVals=&overrideVals=&columnsOnly=&disHelp=&noView=&editRegularContentFromId=";}}i:1;s:32:"f4c24c6e05918202720fec0632c8f8e2";}s:32:"t3lib_BEfunc::getSetUpdateSignal";a:1:{s:3:"set";s:0:"";}s:10:"web_layout";a:8:{s:8:"tt_board";s:1:"0";s:10:"tt_address";s:1:"0";s:8:"tt_links";s:1:"0";s:11:"tt_calender";s:1:"0";s:11:"tt_products";s:1:"0";s:8:"function";s:1:"1";s:8:"language";s:1:"0";s:21:"tt_content_showHidden";s:1:"1";}s:6:"web_ts";a:6:{s:8:"function";s:17:"tx_tstemplateinfo";s:15:"ts_browser_type";s:5:"setup";s:25:"ts_browser_toplevel_setup";s:1:"0";s:25:"ts_browser_toplevel_const";s:1:"0";s:16:"ts_browser_const";s:1:"0";s:25:"tsbrowser_depthKeys_setup";a:12:{s:25:"plugin.tx_newloginbox_pi3";i:1;s:36:"plugin.tx_newloginbox_pi3.singleView";i:1;s:53:"plugin.tx_newloginbox_pi3.singleView.customProcessing";i:1;s:59:"plugin.tx_newloginbox_pi3.singleView.customProcessing.image";i:1;s:64:"plugin.tx_newloginbox_pi3.singleView.customProcessing.image.file";i:1;s:34:"plugin.tx_newloginbox_pi3.listView";i:1;s:18:"tt_content.stdWrap";i:1;s:4:"page";i:1;s:7:"page.10";i:1;s:10:"page.10.15";i:1;s:10:"page.10.20";i:1;s:12:"page.10.20.c";i:1;}}s:9:"xMod_test";a:1:{s:19:"constant_editor_cat";s:5:"basic";}s:9:"tools_log";a:4:{s:5:"users";s:1:"0";s:4:"time";s:1:"0";s:3:"max";s:2:"20";s:6:"action";s:1:"0";}s:31:"xMOD_tx_rlmpofficedocuments_cm1";a:1:{s:8:"function";s:1:"1";}s:9:"file_list";a:0:{}s:16:"browse_links.php";a:1:{s:12:"expandFolder";s:45:"F:/apache/htdocs/quickstart/fileadmin/_temp_/";}s:9:"user_task";a:1:{s:8:"function";s:13:"tx_sysnotepad";}s:20:"tools_txextdevevalM1";a:1:{s:8:"function";s:1:"1";}s:19:"web_txtemplavoilaM2";a:0:{}s:12:"tools_config";a:1:{s:8:"function";s:1:"0";}s:19:"web_txcmwlinklistM1";a:1:{s:8:"function";s:1:"1";}s:14:"web_txtimtawM1";a:1:{s:8:"function";s:1:"1";}s:18:"xMOD_tx_timtaw_cm1";a:1:{s:8:"function";s:0:"";}}s:19:"thumbnailsByDefault";i:0;s:14:"emailMeAtLogin";i:0;s:13:"condensedMode";i:0;s:10:"noMenuMode";i:0;s:17:"startInTaskCenter";i:0;s:14:"localFrameEdit";i:0;s:20:"dontEditInPageModule";i:0;s:18:"hideSubmoduleIcons";i:0;s:8:"helpText";i:1;s:8:"titleLen";i:30;s:17:"edit_wideDocument";s:1:"0";s:18:"edit_showFieldHelp";s:4:"icon";s:8:"edit_RTE";s:1:"1";s:20:"edit_docModuleUpload";s:1:"1";s:15:"disableCMlayers";i:0;s:13:"navFrameWidth";s:0:"";s:17:"navFrameResizable";i:0;s:20:"deleteCmdInClipboard";s:1:"1";s:4:"lang";s:0:"";s:15:"moduleSessionID";a:19:{s:8:"tools_em";s:32:"aaf7f51450f424656b894892852fc4e4";s:8:"web_list";s:32:"26bc6f4ab1761edecc59c475816f4ddf";s:16:"xMOD_alt_doc.php";s:32:"567f3b7fefab0cce195ce527863479f3";s:11:"alt_doc.php";s:32:"aaf7f51450f424656b894892852fc4e4";s:32:"t3lib_BEfunc::getSetUpdateSignal";s:32:"aaf7f51450f424656b894892852fc4e4";s:10:"web_layout";s:32:"d4b34d9222cd7c0d13a349b3b09a595e";s:6:"web_ts";s:32:"aaf7f51450f424656b894892852fc4e4";s:9:"xMod_test";s:32:"6c725da0b3c36ba8f046abac7dfeb25d";s:9:"tools_log";s:32:"f259ebc7ef65f149882c594055d39c9a";s:31:"xMOD_tx_rlmpofficedocuments_cm1";s:32:"6ab2dfa654d5eafd8dcd727b9ccfccbc";s:9:"file_list";s:32:"6ab2dfa654d5eafd8dcd727b9ccfccbc";s:16:"browse_links.php";s:32:"567f3b7fefab0cce195ce527863479f3";s:9:"user_task";s:32:"567f3b7fefab0cce195ce527863479f3";s:20:"tools_txextdevevalM1";s:32:"f07eb84ac51b4dfad10f00b2303804dd";s:19:"web_txtemplavoilaM2";s:32:"842a68604ff011a7257aec165ca8fa61";s:12:"tools_config";s:32:"4aee13463560f97d4e8bb1425a995713";s:19:"web_txcmwlinklistM1";s:32:"52d2d6b8652ba3c951067cd0323c1b9d";s:14:"web_txtimtawM1";s:32:"aaf7f51450f424656b894892852fc4e4";s:18:"xMOD_tx_timtaw_cm1";s:32:"aaf7f51450f424656b894892852fc4e4";}s:11:"browseTrees";a:2:{s:11:"browsePages";s:48:"a:1:{i:0;a:4:{i:0;i:1;i:1;i:1;i:5;i:1;i:4;i:1;}}";s:6:"folder";s:79:"a:2:{i:45718;a:1:{i:10577375;i:1;}i:52827;a:2:{i:13995935;i:1;i:11028677;i:1;}}";}}";s:16:"file_mountpoints";s:0:"";s:14:"fileoper_perms";s:1:"7";s:12:"lockToDomain";s:0:"";s:7:"deleted";s:1:"0";s:8:"TSconfig";s:0:"";s:9:"lastlogin";s:10:"1110325845";s:15:"createdByAction";s:1:"0";s:21:"usergroup_cached_list";s:0:"";s:17:"allowed_languages";s:0:"";s:13:"disableIPlock";s:1:"0";}';
			#$test = unserialize($unser);
			#$params['pObj']->user = $test;
			#debug($resultat);
			 
		}

	}
	
	
	/**
	 * Main function, registering itself as a hook
	 */
	function loginBackendUser($params, $parent = '')	{
		require_once (PATH_t3lib.'class.t3lib_befunc.php');

			// login user when set
		if(t3lib_div::_GP('tx_timtaw_login') && $GLOBALS['TSFE']->fe_user->user['tx_timtaw_begroup']) {
			if($_COOKIE['be_typo_user']) {
				$sesId = $_COOKIE['be_typo_user'];
			} else {
				$sesId = substr(md5(uniqid('')),0,32);		// New random sesseion id $sesId is made
				setcookie('be_typo_user', $sesId, time()+3600, '/');
				$_COOKIE['be_typo_user'] = $sesId; // change cookie superglobal, else we need a refresh
			}

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('ses_id','be_sessions','ses_id='."'".$sesId."'");

			if(!$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

				$insertFields = array(
					'ses_id' => $sesId,
					'ses_name' => 'be_typo_user',
					'ses_iplock' => '[DISABLED]',
					'ses_hashlock' => t3lib_div::md5int(':'.t3lib_div::getIndpEnv('HTTP_USER_AGENT')),
					'ses_userid' => $GLOBALS['TSFE']->fe_user->user['uid']+1000,
					'ses_tstamp' => time()
				);

				$GLOBALS['TYPO3_DB']->exec_INSERTquery('be_sessions', $insertFields);

			}
		}

		if(t3lib_div::_GP('tx_timtaw_logout')) {
			if($_COOKIE['be_typo_user']) {
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('ses_id, ses_userid','be_sessions','ses_id='."'".$_COOKIE['be_typo_user']."'");
				if($record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$GLOBALS['TYPO3_DB']->exec_DELETEquery('be_sessions', "ses_id='".$_COOKIE['be_typo_user']."'");
				}
				setcookie('be_typo_user', $_COOKIE['be_typo_user'], time() - 3600, '/');
				unset($_COOKIE['be_typo_user']);
			}
		}

		/*
			// log out user if logged in when needed
		if($_COOKIE['be_typo_user']) {
				// select user id
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('ses_id, ses_userid','be_sessions','ses_id='."'".$_COOKIE['be_typo_user']."'");
			if($record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$uid = $record['ses_userid'];
					// select group id
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,usergroup','be_users','uid='.intval($record['ses_userid']));
				if($record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
						// check group whether it is a wiki group
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('tx_timtaw_enable','be_groups','uid='.intval($record['usergroup']));
					if($record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
						if($record['tx_timtaw_enable'] == 1) {
							$pid = t3lib_div::_GP('id'); // should be $GLOBALS['TSFE']->id, shouldn't it?
							$record = t3lib_BEfunc::getRecordRaw('pages','uid='.intval($pid),'tx_timtaw_enable,tx_timtaw_backenduser');
							if(!$record['tx_timtaw_enable'] || $record['tx_timtaw_backenduser'] != $uid) {
									// log out
								$GLOBALS['TYPO3_DB']->exec_DELETEquery('be_sessions', "ses_id='".$_COOKIE['be_typo_user']."'");
								setcookie('be_typo_user', $_COOKIE['be_typo_user'], time() - 3600, '/');
								unset($_COOKIE['be_typo_user']);
								
							}
						}
					}
				}
			}
		}
		*/

		
		
		/*
			// login user when set
		if(t3lib_div::_GP('wikiLogin')) {
			$pid = t3lib_div::_GP('id'); // should be $GLOBALS['TSFE']->id, shouldn't it?
			$record = t3lib_BEfunc::getRecordRaw('pages','uid='.intval($pid),'tx_timtaw_enable,tx_timtaw_backenduser');
		
			if($record['tx_timtaw_enable']) {
				if($_COOKIE['be_typo_user']) {
					$id = $_COOKIE['be_typo_user'];
				} else {
					$id = substr(md5(uniqid('')),0,32);		// New random session-$id is made
					setcookie('be_typo_user', $id, time()+3600, '/');
					$_COOKIE['be_typo_user'] = $id; // change cookie superglobal, else we need a refresh
				}

				$userId = $record['tx_timtaw_backenduser'];

				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('ses_id','be_sessions','ses_id='."'".$id."'");

				if(!$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

					$insertFields = array(
						'ses_id' => $id,
						'ses_name' => 'be_typo_user',
						'ses_iplock' => '[DISABLED]',
						'ses_hashlock' => t3lib_div::md5int(':'.t3lib_div::getIndpEnv('HTTP_USER_AGENT')),
						'ses_userid' => $userId,
						'ses_tstamp' => time()
					);

					$GLOBALS['TYPO3_DB']->exec_INSERTquery('be_sessions', $insertFields);
					
					// we have to set the user's start- and endtime as well.
					
					#return 'Timtaw Backenduser '.$userId.' wurde eingeloggt.';
				}
			} else {
			#	return 'Flag tx_timtaw_enable ist nicht gesetzt.';
			}
		} else {
			#return 'GPVar.wikiLogin ist nicht gesetzt.';
		}
		*/
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/timtaw/res/class.tx_timtaw_login.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/timtaw/res/class.tx_timtaw_login.php']);
}

?>