#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_timtaw_enable tinyint(3) unsigned DEFAULT '0' NOT NULL,
	tx_timtaw_includesubpages tinyint(3) unsigned DEFAULT '0' NOT NULL,
	tx_timtaw_backenduser int(11) unsigned DEFAULT '0' NOT NULL
);


#
# Table structure for table 'be_groups'
#
CREATE TABLE be_groups (
	tx_timtaw_enable tinyint(3) unsigned DEFAULT '0' NOT NULL
);

#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (
	tx_timtaw_begroup tinyblob NOT NULL,
);

#
# Table structure for table 'fe_groups'
#
CREATE TABLE fe_groups (
	tx_timtaw_begroup tinyblob NOT NULL,
);

#
# Table structure for table 'be_sessions'
#
CREATE TABLE be_sessions (
	ses_userid int(11) DEFAULT '0' NOT NULL,
);