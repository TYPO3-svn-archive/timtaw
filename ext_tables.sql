#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_timtaw_enable tinyint(3) unsigned DEFAULT '0' NOT NULL,
	tx_timtaw_includesubpages tinyint(3) unsigned DEFAULT '0' NOT NULL,
	tx_timtaw_backenduser int(11) unsigned DEFAULT '0' NOT NULL
);


#
# Table structure for table 'be_users'
#
CREATE TABLE be_groups (
	tx_timtaw_enable tinyint(3) unsigned DEFAULT '0' NOT NULL
);