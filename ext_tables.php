<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');



$tempColumns= array(
	'tx_timtaw_begroup' => Array (
		'label' => 'Backend group (for granting edit rights):',
		'config' => Array (
			'type' => 'select',
			'foreign_table' => 'be_groups',
			'foreign_table_where' => 'AND be_groups.tx_timtaw_enable=1 ORDER BY be_groups.title',
			'size' => '5',
			'maxitems' => '20',
	#				'renderMode' => $GLOBALS['TYPO3_CONF_VARS']['BE']['accessListRenderMode'],
			'iconsInOptionTags' => 1,
			'wizards' => Array(
				'_PADDING' => 1,
				'_VERTICAL' => 1,
				'edit' => Array(
					'type' => 'popup',
					'title' => 'Edit usergroup',
					'script' => 'wizard_edit.php',
					'popup_onlyOpenIfSelected' => 1,
					'icon' => 'edit2.gif',
					'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
				),
				'add' => Array(
					'type' => 'script',
					'title' => 'Create new group',
					'icon' => 'add.gif',
					'params' => Array(
						'table'=>'be_groups',
						'pid' => '0',
						'setValue' => 'prepend'
					),
					'script' => 'wizard_add.php',
				),
				'list' => Array(
					'type' => 'script',
					'title' => 'List groups',
					'icon' => 'list.gif',
					'params' => Array(
						'table'=>'be_groups',
						'pid' => '0',
					),
					'script' => 'wizard_list.php',
				)
			)
		)
	)
);

t3lib_div::loadTCA('fe_users');
t3lib_extMgm::addTCAcolumns('fe_users',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('fe_users','tx_timtaw_begroup');

t3lib_div::loadTCA('fe_groups');
t3lib_extMgm::addTCAcolumns('fe_groups',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('fe_groups','tx_timtaw_begroup');




$tempColumns = Array (
	'tx_timtaw_enable' => Array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:timtaw/locallang_db.php:be_groups.tx_timtaw_enable',		
		'config' => Array (
			'type' => 'check',
		)
	)
);
t3lib_div::loadTCA('be_groups');
t3lib_extMgm::addTCAcolumns('be_groups',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('be_groups','tx_timtaw_enable;;;;1-1-1');

?>