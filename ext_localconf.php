<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
t3lib_extMgm::addPageTSConfig('
	tx_timtaw.backendUser =
');

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_timtaw_pi1.php','_pi1','',0);
t3lib_extMgm::addPItoST43($_EXTKEY,'pi2/class.tx_timtaw_pi2.php','_pi2','',0);

	// automatically log in user
require_once(t3lib_extMgm::extPath('timtaw').'res/class.tx_timtaw_login.php');
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/index_ts.php']['preBeUser'][]='tx_timtaw_login->setBackendUserCookie';
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp'][]='tx_timtaw_login->createVirtualBackendUser';

	// versionize page
require_once(t3lib_extMgm::extPath('timtaw').'res/class.tx_timtaw_versionize.php');
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tsfebeuserauth.php']['extEditAction'][]='tx_timtaw_versionize->editAction';
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][]='tx_timtaw_versionize';



?>