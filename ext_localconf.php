<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
t3lib_extMgm::addPageTSConfig('
	tx_timtaw.backendUser =
');

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_timtaw_pi1.php','_pi1','',1);
t3lib_extMgm::addPItoST43($_EXTKEY,'pi2/class.tx_timtaw_pi2.php','_pi2','',0);

require_once(t3lib_extMgm::extPath('timtaw').'res/class.tx_timtaw_login.php');
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/index_ts.php']['preBeUser'][]='tx_timtaw_login->loginBackendUser';

?>