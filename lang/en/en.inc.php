<?php
/**
 * Wikka language file.
 *
 * This file holds all interface language strings for Wikka.
 *
 * @package 		Language
 *
 * @version		$Id:en.inc.php 481 2007-05-17 16:34:24Z DarTar $
 * @license 		http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 *
 * @author 		{@link http://wikkawiki.org/NilsLindenberg Nils Lindenberg}
 * @author 		{@link http://wikkawiki.org/DarTar Dario Taraborelli}
 * @author 		{@link http://wikkawiki.org/JavaWoman Marjolein Katsma}
 * @author 		{@link http://wikkawiki.org/DotMG Mahefa Randimbisoa}
 *
 * @copyright 	Copyright 2007, {@link http://wikkawiki.org/CreditsPage Wikka Development Team}
 *
 * @todo		make sure that punctuation is always part of defined phrase
 *				(check where these constants are used)
 * @todo		use single quotes whenever possible
 * 				(unless	that leads to more than one escaped single quote)
 * @todo		move the rest of the hardcoded texts in here (double-check)
 *
 * @todo		document the use of phpdoc group docblocks to append automatically descriptions to multiple constants.
 *
 * @todo		backlink to constants adding the <tt>uses</tt> tag in the corresponding components
 */

/* ------------------ COMMON ------------------ */

/**#@+
 * Language constant shared among several Wikka components
 */
// NOTE: all common names (used in multiple files) should start with WIKKA_ !
define('WIKKA_ADMIN_ONLY_TITLE', 'Sorry, only wiki administrators can display this information'); //title for elements that are only displayed to admins
define('WIKKA_ERROR_SETUP_FILE_MISSING', 'A file of the installer / upgrader was not found. Please install Wikka again!');
define('WIKKA_ERROR_MYSQL_ERROR', 'MySQL error: %d - %s');	// %d - error number; %s - error text
define('WIKKA_ERROR_CAPTION', 'Error');
define('WIKKA_ERROR_ACL_READ', 'You are not allowed to read this page.');
define('WIKKA_ERROR_ACL_READ_SOURCE', 'You are not allowed to read the source of this page.');
define('WIKKA_ERROR_ACL_READ_INFO', 'You are not allowed to access this information.');
define('WIKKA_ERROR_LABEL', 'Error');
define('WIKKA_ERROR_PAGE_NOT_EXIST', 'Sorry, page %s does not exist.'); // %s (source) page name
define('WIKKA_ERROR_EMPTY_USERNAME', 'Please fill in your username!');
define('WIKKA_DIFF_ADDITIONS_HEADER', 'Additions:');
define('WIKKA_DIFF_DELETIONS_HEADER', 'Deletions:');
define('WIKKA_DIFF_NO_DIFFERENCES', 'No Differences');
define('ERROR_USERNAME_UNAVAILABLE', "Sorry, this user name is unavailable.");
define('ERROR_USER_SUSPENDED', "Sorry, this account has been suspended. Please contact an administrator for further details.");
define('WIKKA_ERROR_INVALID_PAGE_NAME', 'The page name %s is invalid. Valid page names must start with a capital letter, contain only letters and numbers, and be in CamelCase format.'); // %s - page name
define('WIKKA_ERROR_PAGE_ALREADY_EXIST', 'Sorry, the target page already exists');
define('WIKKA_LOGIN_LINK_DESC', 'login');
define('WIKKA_MAINPAGE_LINK_DESC', 'main page');
define('WIKKA_NO_OWNER', 'Nobody');
define('WIKKA_NOT_AVAILABLE', 'n/a');
define('WIKKA_NOT_INSTALLED', 'not installed');
define('WIKKA_ANONYMOUS_USER', 'anonymous'); // 'name' of non-registered user
define('WIKKA_UNREGISTERED_USER', 'unregistered user'); // alternative for 'anonymous' @@@ make one string only?
define('WIKKA_ANONYMOUS_AUTHOR_CAPTION', '('.WIKKA_UNREGISTERED_USER.')'); // @@@ or WIKKA_ANONYMOUS_USER
define('WIKKA_SAMPLE_WIKINAME', 'JohnDoe'); // must be a CamelCase name
define('WIKKA_HISTORY', 'history');
define('WIKKA_REVISIONS', 'revisions');
define('WIKKA_REVISION_NUMBER', 'Revision %s');
define('WIKKA_REV_WHEN_BY_WHO', '%1$s by %2$s'); // %1$s - timestamp; %2$s - user name
define('WIKKA_NO_PAGES_FOUND', 'No pages found.');
define('WIKKA_PAGE_OWNER', 'Owner: %s'); // %s - page owner name or link
define('WIKKA_COMMENT_AUTHOR_DIVIDER', ', comment by '); //TODo check if we can construct a single phrase here
define('WIKKA_PAGE_EDIT_LINK_DESC', 'edit');
define('WIKKA_PAGE_CREATE_LINK_DESC', 'create');
define('WIKKA_PAGE_EDIT_LINK_TITLE', 'Click to edit %s'); // %s page name @@@ 'Edit %s'
define('WIKKA_BACKLINKS_LINK_TITLE', 'Display a list of pages linking to %s'); // %s page name
define('WIKKA_JRE_LINK_DESC', 'Java Runtime Environment');
define('WIKKA_NOTE', 'NOTE:');
define('WIKKA_JAVA_PLUGIN_NEEDED', 'Java 1.4.1 (or later) Plug-in is needed to run this applet,');
/**#@-*/


/*  ------------------ CORE ------------------  */

/**#@+
 * Language constant for the core {@link wikka.php wikka} program
 */
// wikka
define('ERROR_WAKKA_LIBRARY_MISSING','The necessary file "libs/Wakka.class.php" could not be found. To run Wikka, please make sure the file exists and is placed in the right directory!');
define('ERROR_WRONG_PHP_VERSION', 'Wikka requires PHP %s or higher!');  // %s - version numberdefine('MINIMUM_PHP_VERSION', '4.1');
define('ERROR_MYSQL_SUPPORT_MISSING', 'PHP can\'t find MySQL support but Wikka requires MySQL. Please check the output of <tt>phpinfo()</tt> in a php document for MySQL support: it needs to be compiled into PHP, the module itself needs to be present in the expected location, <strong>and</strong> php.ini needs to have it enabled.<br />Also note that you cannot have <tt>mysqli</tt> and <tt>mysql</tt> support both enabled at the same time.<br />Please double-check all of these things, restart your webserver after any fixes, and then try again!');
define('ERROR_SETUP_FILE_MISSING', 'A file of the installer/ upgrader was not found. Please install Wikka again!');
define('ERROR_SETUP_HEADER_MISSING', 'The file "setup/header.php" was not found. Please install Wikka again!');
define('ERROR_SETUP_FOOTER_MISSING', 'The file "setup/footer.php" was not found. Please install Wikka again!');
define('ERROR_HEADER_MISSING', 'A header template could not be found. Please make sure that a file called <code>header.php</code> exists in the templates directory.'); //TODO Make sure this message matches any filename/folder change 
define('ERROR_FOOTER_MISSING', 'A footer template could not be found. Please make sure that a file called <code>footer.php</code> exists in the templates directory.'); //TODO Make sure this message matches any filename/folder change 
define('ERROR_NO_DB_ACCESS', 'The wiki is currently unavailable. <br /><br />Error: Unable to connect to the MySQL database.');
define('PAGE_GENERATION_TIME', 'Page was generated in %.4f seconds'); // %.4f - generation time in seconds with 4 digits after the dot
define('WIKI_UPGRADE_NOTICE', 'This site is currently being upgraded. Please try again later.');
/*

NOTE: These defines are the "new" defines ported from trunk to 1.2.
They will eventually need to be reconciled with updates to wikka.php.
For now, I've commented them out and have simply copied over the 1.2
versions.

define('ERROR_WAKKA_LIBRARY_MISSING', 'The necessary file "%s" could not be found. To run Wikka, please make sure the file exists and is placed in the right directory!');	// %s - configured path to core class
define('ERROR_NO_DB_ACCESS', 'Error: Unable to connect to the database.');
define('ERROR_RETRIEVAL_MYSQL_VERSION', 'Could not determine MySQL version');
define('ERROR_WRONG_MYSQL_VERSION', 'Wikka requires MySQL %s or higher!');	// %s - version number
define('STATUS_WIKI_UPGRADE_NOTICE', 'This site is currently being upgraded. Please try again later.');
define('STATUS_WIKI_UNAVAILABLE', 'The wiki is currently unavailable.');
define('PAGE_GENERATION_TIME', 'Page was generated in %.4f seconds'); // %.4f - page generation time
define('ERROR_HEADER_MISSING', 'A header template could not be found. Please make sure that a file called <code>header.php</code> exists in the templates directory.'); //TODO Make sure this message matches any filename/folder change
define('ERROR_FOOTER_MISSING', 'A footer template could not be found. Please make sure that a file called <code>footer.php</code> exists in the templates directory.'); //TODO Make sure this message matches any filename/folder change

#define('ERROR_WRONG_PHP_VERSION', '$_REQUEST[] not found. Wakka requires PHP 4.1.0 or higher!'); //TODO remove referral to PHP internals; refer only to required version
#define('ERROR_SETUP_HEADER_MISSING', 'The file "setup/header.php" was not found. Please install Wikka again!');
#define('ERROR_SETUP_FOOTER_MISSING', 'The file "setup/footer.php" was not found. Please install Wikka again!');
*/
/**#@-*/

/*  ------------------ TEMPLATE ------------------  */

/**#@+
 * Language constant used by the {@link header.php header} template
 */
// header
define('GENERIC_DOCTITLE', '%1$s: %2$s');	// %1$s - wiki name; %2$s - page title
define('RSS_REVISIONS_TITLE', '%1$s: revisions for %2$s');	// %1$s - wiki name; %2$s - current page name
define('RSS_RECENTCHANGES_TITLE', '%s: recently edited pages');	// %s - wiki name
define('YOU_ARE', 'You are %s'); // %s - name / ip of the user.
/**#@-*/

/**#@+
 * Language constant used by the {@link footer.php footer} template
 */
// footer
define('FOOTER_PAGE_EDIT_LINK_DESC', 'Edit page');
define('PAGE_HISTORY_LINK_TITLE', 'Click to view recent edits to this page'); // @@@ TODO 'View recent edits to this page'
define('PAGE_HISTORY_LINK_DESC', 'Page History');
define('PAGE_REVISION_LINK_TITLE', 'Click to view recent revisions list for this page'); // @@@ TODO 'View recent revisions list for this page'
define('PAGE_REVISION_XML_LINK_TITLE', 'Click to view recent revisions list for this page'); // @@@ TODO 'View recent revisions list for this page'
define('PAGE_ACLS_EDIT_LINK_DESC', 'Edit ACLs');
define('PAGE_ACLS_EDIT_ADMIN_LINK_DESC', '('.PAGE_ACLS_EDIT_LINK_DESC.')');
define('PUBLIC_PAGE', 'Public page');
define('USER_IS_OWNER', 'You own this page.');
define('TAKE_OWNERSHIP', 'Take Ownership');
define('REFERRERS_LINK_TITLE', 'Click to view a list of URLs referring to this page'); // @@@ TODO 'View a list of URLs referring to this page'
define('REFERRERS_LINK_DESC', 'Referrers');
define('QUERY_LOG', 'Query log:');
define('SEARCH_LABEL', 'Search:');
/**#@-*/


/*  ------------------ ACTIONS  ------------------  */

/**#@+
 * Language constants used by the {@link adminpages.php adminpages} action
 */
define('ADMINPAGES_DEFAULT_RECORDS_LIMIT', '20'); # number of records per page
define('ADMINPAGES_DEFAULT_MIN_RECORDS_DISPLAY', '5'); # min number of records
define('ADMINPAGES_DEFAULT_RECORDS_RANGE',serialize(array('10','50','100','500','1000'))); #range array for records pager
define('ADMINPAGES_DEFAULT_SORT_FIELD', 'time'); # sort field
define('ADMINPAGES_DEFAULT_SORT_ORDER', 'desc'); # sort order, ascendant or descendant
define('ADMINPAGES_DEFAULT_START', '0'); # start record
define('ADMINPAGES_DEFAULT_SEARCH', ''); # keyword to restrict page search
define('ADMINPAGES_DEFAULT_TAG_LENGTH', '12'); # max. length of displayed pagename
define('ADMINPAGES_DEFAULT_URL_LENGTH', '15'); # max. length of displayed user host
define('ADMINPAGES_DEFAULT_TERMINATOR', '&#8230;'); # standard symbol replacing truncated text (ellipsis) JW 2005-07-19
define('ADMINPAGES_ALTERNATE_ROW_COLOR', '1'); # switch alternate row color
define('ADMINPAGES_STAT_COLUMN_COLOR', '1'); # switch color for statistics columns
define('ADMINPAGES_DEFAULT_START_YEAR', 'YYYY');
define('ADMINPAGES_DEFAULT_START_MONTH', 'MM');
define('ADMINPAGES_DEFAULT_START_DAY', 'DD');
define('ADMINPAGES_DEFAULT_START_HOUR', 'hh');
define('ADMINPAGES_DEFAULT_START_MINUTE', 'mm');
define('ADMINPAGES_DEFAULT_START_SECOND', 'ss');
define('ADMINPAGES_DEFAULT_END_YEAR', 'YYYY');
define('ADMINPAGES_DEFAULT_END_MONTH', 'MM');
define('ADMINPAGES_DEFAULT_END_DAY', 'DD');
define('ADMINPAGES_DEFAULT_END_HOUR', 'hh');
define('ADMINPAGES_DEFAULT_END_MINUTE', 'mm');
define('ADMINPAGES_DEFAULT_END_SECOND', 'ss');
define('ADMINPAGES_MAX_EDIT_NOTE_LENGTH', '50');
define('ADMINPAGES_REVISIONS_ICON', 'images/icons/edit.png'); 
define('ADMINPAGES_COMMENTS_ICON', 'images/icons/comment.png');
define('ADMINPAGES_HITS_ICON', 'images/icons/star.png'); 
define('ADMINPAGES_BACKLINKS_ICON', 'images/icons/link.png'); 
define('ADMINPAGES_REFERRERS_ICON', 'images/icons/world.png'); 
define('ADMINPAGES_PAGE_TITLE','Page Administration');
define('ADMINPAGES_FORM_LEGEND','Filter view:');
define('ADMINPAGES_FORM_SEARCH_STRING_LABEL','Search page:');
define('ADMINPAGES_FORM_SEARCH_STRING_TITLE','Enter a search string');
define('ADMINPAGES_FORM_SEARCH_SUBMIT','Submit');
define('ADMINPAGES_FORM_DATE_RANGE_STRING_LABEL','Last edit range: Between');
define('ADMINPAGES_FORM_DATE_RANGE_CONNECTOR_LABEL','and');
define('ADMINPAGES_FORM_PAGER_LABEL_BEFORE','Show');
define('ADMINPAGES_FORM_PAGER_TITLE','Select records-per-page limit');
define('ADMINPAGES_FORM_PAGER_LABEL_AFTER','records per page');
define('ADMINPAGES_FORM_PAGER_SUBMIT','Apply');
define('ADMINPAGES_FORM_PAGER_LINK','Show records from %d to %d');
define('ADMINPAGES_FORM_RESULT_INFO','Records');
define('ADMINPAGES_FORM_RESULT_SORTED_BY','Sorted by:');
define('ADMINPAGES_TABLE_HEADING_PAGENAME','Page Name');
define('ADMINPAGES_TABLE_HEADING_PAGENAME_TITLE','Sort by page name');
define('ADMINPAGES_TABLE_HEADING_OWNER','Owner');
define('ADMINPAGES_TABLE_HEADING_OWNER_TITLE','Sort by page owner');
define('ADMINPAGES_TABLE_HEADING_LASTAUTHOR','Last Author');
define('ADMINPAGES_TABLE_HEADING_LASTAUTHOR_TITLE','Sort by last author');
define('ADMINPAGES_TABLE_HEADING_LASTEDIT','Last Edit');
define('ADMINPAGES_TABLE_HEADING_LASTEDIT_TITLE','Sort by edit time');
define('ADMINPAGES_TABLE_SUMMARY','List of pages on this server');
define('ADMINPAGES_TABLE_HEADING_HITS_TITLE','Hits');
define('ADMINPAGES_TABLE_HEADING_REVISIONS_TITLE','Revisions');
define('ADMINPAGES_TABLE_HEADING_COMMENTS_TITLE','Comments');
define('ADMINPAGES_TABLE_HEADING_BACKLINKS_TITLE','Backlinks');
define('ADMINPAGES_TABLE_HEADING_REFERRERS_TITLE','Referrers');
define('ADMINPAGES_TABLE_HEADING_HITS_ALT','Hits');
define('ADMINPAGES_TABLE_HEADING_REVISIONS_ALT','Revisions');
define('ADMINPAGES_TABLE_HEADING_COMMENTS_ALT','Comments');
define('ADMINPAGES_TABLE_HEADING_BACKLINKS_ALT','Backlinks');
define('ADMINPAGES_TABLE_HEADING_REFERRERS_ALT','Referrers');
define('ADMINPAGES_TABLE_HEADING_ACTIONS','Actions');
define('ADMINPAGES_ACTION_EDIT_LINK_TITLE','Edit %s');
define('ADMINPAGES_ACTION_DELETE_LINK_TITLE','Delete %s');
define('ADMINPAGES_ACTION_CLONE_LINK_TITLE','Clone %s');
define('ADMINPAGES_ACTION_RENAME_LINK_TITLE','Rename %s (DISABLED)');
define('ADMINPAGES_ACTION_ACL_LINK_TITLE','Change Access Control List for %s');
define('ADMINPAGES_ACTION_REVERT_LINK_TITLE','Revert %s to previous version');
define('ADMINPAGES_ACTION_EDIT_LINK','edit');
define('ADMINPAGES_ACTION_DELETE_LINK','delete');
define('ADMINPAGES_ACTION_CLONE_LINK','clone');
define('ADMINPAGES_ACTION_RENAME_LINK','rename');
define('ADMINPAGES_ACTION_ACL_LINK','acl');
define('ADMINPAGES_ACTION_INFO_LINK','info');
define('ADMINPAGES_ACTION_REVERT_LINK', 'revert');
define('ADMINPAGES_TAKE_OWNERSHIP_LINK','Take ownership of');
define('ADMINPAGES_NO_OWNER','(Nobody)');
define('ADMINPAGES_TABLE_CELL_HITS_TITLE','Hits for %s (%d)');
define('ADMINPAGES_TABLE_CELL_REVISIONS_TITLE','Display revisions for %s (%d)');
define('ADMINPAGES_TABLE_CELL_COMMENTS_TITLE','Display comments for %s (%d)');
define('ADMINPAGES_TABLE_CELL_BACKLINKS_TITLE','Display pages linking to %s (%d)');
define('ADMINPAGES_TABLE_CELL_REFERRERS_TITLE','Display external sites linking to %s (%d)');
define('ADMINPAGES_SELECT_RECORD_TITLE','Select %s');
define('ADMINPAGES_NO_EDIT_NOTE','(No edit note)');
define('ADMINPAGES_CHECK_ALL_TITLE','Check all records');
define('ADMINPAGES_CHECK_ALL','Check all');
define('ADMINPAGES_UNCHECK_ALL_TITLE','Uncheck all records');
define('ADMINPAGES_UNCHECK_ALL','Uncheck all');
define('ADMINPAGES_FORM_MASSACTION_LEGEND','Mass-action');
define('ADMINPAGES_FORM_MASSACTION_LABEL','With selected');
define('ADMINPAGES_FORM_MASSACTION_SELECT_TITLE','Choose action to apply to selected records (DISABLED)');
define('ADMINPAGES_FORM_MASSACTION_OPT_DELETE','Delete all');
define('ADMINPAGES_FORM_MASSACTION_OPT_CLONE','Clone all');
define('ADMINPAGES_FORM_MASSACTION_OPT_RENAME','Rename all');
define('ADMINPAGES_FORM_MASSACTION_OPT_ACL','Change Access Control List');
define('ADMINPAGES_FORM_MASSACTION_OPT_REVERT','Revert to previous page version');
define('ADMINPAGES_FORM_MASSACTION_REVERT_ERROR','Cannot be reverted');
define('ADMINPAGES_FORM_MASSACTION_SUBMIT','Submit');
define('ADMINPAGES_ERROR_NO_MATCHES','Sorry, there are no pages matching "%s"');
define('ADMINPAGES_LABEL_EDIT_NOTE','Please enter a comment, or leave blank for default');
define('WHEN_BY_WHO', '%1$s by %2$s');
define('ADMINPAGES_CANCEL_LABEL', 'Cancel');
/**#@-*/

/**#@+
 * Language constants used by the {@link adminusers.php adminusers} action
 */
define('ADMINUSERS_DEFAULT_RECORDS_LIMIT', '10'); # number of records per page
define('ADMINUSERS_DEFAULT_MIN_RECORDS_DISPLAY', '5'); # min number of records 
define('ADMINUSERS_DEFAULT_RECORDS_RANGE',serialize(array('10','50','100','500','1000'))); #range array for records pager
define('ADMINUSERS_DEFAULT_SORT_FIELD', 'signuptime'); # sort field
define('ADMINUSERS_DEFAULT_SORT_ORDER', 'desc'); # sort order, ascendant or descendant
define('ADMINUSERS_DEFAULT_START', '0'); # start record
define('ADMINUSERS_DEFAULT_SEARCH', ''); # keyword to restrict search
define('ADMINUSERS_ALTERNATE_ROW_COLOR', '1'); # switch alternate row color
define('ADMINUSERS_STAT_COLUMN_COLOR', '1'); # switch color for statistics columns
define('ADMINUSERS_OWNED_ICON', 'images/icons/keyring.png'); 
define('ADMINUSERS_EDITS_ICON', 'images/icons/edit.png'); 
define('ADMINUSERS_COMMENTS_ICON', 'images/icons/comment.png'); 
define('ADMINUSERS_PAGE_TITLE','User Administration');
define('ADMINUSERS_FORM_LEGEND','Filter view:');
define('ADMINUSERS_FORM_SEARCH_STRING_LABEL','Search user:');
define('ADMINUSERS_FORM_SEARCH_STRING_TITLE','Enter a search string');
define('ADMINUSERS_FORM_SEARCH_SUBMIT','Submit');
define('ADMINUSERS_FORM_PAGER_LABEL_BEFORE','Show');
define('ADMINUSERS_FORM_PAGER_TITLE','Select records-per-page limit');
define('ADMINUSERS_FORM_PAGER_LABEL_AFTER','records per page');
define('ADMINUSERS_FORM_PAGER_SUBMIT','Apply');
define('ADMINUSERS_FORM_PAGER_LINK','Show records from %d to %d');
define('ADMINUSERS_FORM_RESULT_INFO','Records');
define('ADMINUSERS_FORM_RESULT_SORTED_BY','Sorted by:');
define('ADMINUSERS_TABLE_HEADING_USERNAME','User Name');
define('ADMINUSERS_TABLE_HEADING_USERNAME_TITLE','Sort by user name');
define('ADMINUSERS_TABLE_HEADING_EMAIL','Email');
define('ADMINUSERS_TABLE_HEADING_EMAIL_TITLE','Sort by email');
define('ADMINUSERS_TABLE_HEADING_SIGNUPTIME','Signup Time');
define('ADMINUSERS_TABLE_HEADING_SIGNUPTIME_TITLE','Sort by signup time');
define('ADMINUSERS_TABLE_HEADING_SIGNUPIP','Signup IP');
define('ADMINUSERS_TABLE_HEADING_SIGNUPIP_TITLE','Sort by signup IP');
define('ADMINUSERS_TABLE_SUMMARY','List of users registered on this server');
define('ADMINUSERS_TABLE_HEADING_OWNED_TITLE','Owned Pages');
define('ADMINUSERS_TABLE_HEADING_EDITS_TITLE','Edits');
define('ADMINUSERS_TABLE_HEADING_COMMENTS_TITLE','Comments');
define('ADMINUSERS_ACTION_DELETE_LINK_TITLE','Remove user %s');
define('ADMINUSERS_ACTION_DELETE_LINK','delete');
define('ADMINUSERS_TABLE_CELL_OWNED_TITLE','Display pages owned by %s (%d)');
define('ADMINUSERS_TABLE_CELL_EDITS_TITLE','Display page edits by %s (%d)');
define('ADMINUSERS_TABLE_CELL_COMMENTS_TITLE','Display comments by %s (%d)');
define('ADMINUSERS_SELECT_RECORD_TITLE','Select %s');
define('ADMINUSERS_SELECT_ALL_TITLE','Select all records');
define('ADMINUSERS_SELECT_ALL','Select all');
define('ADMINUSERS_DESELECT_ALL_TITLE','Deselect all records');
define('ADMINUSERS_DESELECT_ALL','Deselect all');
define('ADMINUSERS_FORM_MASSACTION_LEGEND','Mass-action');
define('ADMINUSERS_FORM_MASSACTION_LABEL','With selected');
define('ADMINUSERS_FORM_MASSACTION_SELECT_TITLE','Choose an action to apply to the selected records');
define('ADMINUSERS_FORM_MASSACTION_OPT_DELETE','Delete selected');
define('ADMINUSERS_FORM_MASSACTION_DELETE_ERROR', 'Cannot delete admins');
define('ADMINUSERS_FORM_MASSACTION_SUBMIT','Submit');
define('ADMINUSERS_ERROR_NO_MATCHES','Sorry, there are no users matching "%s"');
define('ADMINUSERS_DELETE_USERS_HEADING', 'Delete these users?');
define('ADMINUSERS_DELETE_USERS_BUTTON', 'Delete Users');
define('ADMINUSERS_CANCEL_BUTTON', 'Cancel');
/**#@-*/

/**#@+
 * Language constant used by the {@link calendar.php calendar} action
 */
// calendar
define('FMT_SUMMARY', 'Calendar for %s');	// %s - ???@@@
define('TODAY', 'today');
define('MIN_DATETIME', strtotime('1970-01-01 00:00:00 GMT')); # earliest timestamp PHP can handle (Windows and some others - to be safe)
define('MAX_DATETIME', strtotime('2038-01-19 03:04:07 GMT')); # latest timestamp PHP can handle
define('MIN_YEAR', date('Y',MIN_DATETIME));
define('MAX_YEAR', date('Y',MAX_DATETIME)-1); # don't include partial January 2038
define('CUR_YEAR', date('Y',mktime()));
define('CUR_MONTH', date('n',mktime()));
define('LOC_MON_YEAR', "%B %Y"); # i18n
/**#@-*/

/**#@+
 * Language constant used by the {@link category.php category} action
 */
// category
define('ERROR_NO_PAGES', 'Sorry, No items found for %s');	// %s - ???@@@
define('PAGES_BELONGING_TO', 'The following %1$d page(s) belong to %2$s'); // %1$d number found; %2$s category
/**#@-*/

/**#@+
 * Language constant used by the {@link checkversion.php checkversion} action
 */
define('CHECKVERSION_HOST', 'wikkawiki.org');
define('CHECKVERSION_RELEASE_FILE', '/downloads/latest_wikka_version.txt');
define('CHECKVERSION_DOWNLOAD_URL', 'http://docs.wikkawiki.org/WhatsNew');
define('CHECKVERSION_CONNECTION_TIMEOUT', 5);
/**#@-*/

/**#@+
 * Language constant used by the {@link clonelink.php clonelink} action
 */
define('CLONELINK_TEXT', '[Clone]');
define('CLONELINK_TITLE', 'Duplicate this page');
/**#@-*/

/**#@+
 * Language constant used by the {@link color.php color} action
 */
// color
define('ERROR_NO_TEXT_GIVEN', 'There is no text to highlight!');
define('ERROR_NO_COLOR_SPECIFIED', 'Sorry, but you did not specify a color for highlighting!');
define('PATTERN_VALID_HEX_COLOR', '#(?>[\da-f]{3}){1,2}');
define('PATTERN_VALID_RGB_COLOR', 'rgb\(\s*\d+((?>\.\d*)?%)?\s*(?>,\s*\d+(?(1)(\.\d*)?%)\s*){2}\)');
/**#@-*/

/**#@+
 * Language constant used by the {@link contact.php contact} action
 */
// contact
define('SEND_FEEDBACK_LINK_TITLE', 'Send us your feedback');
define('SEND_FEEDBACK_LINK_TEXT', 'Contact');
/**#@-*/

/**#@+
 * Language constant used by the {@link countowned.php countowned} action
 */
// countowned
define('DISPLAY_MYPAGES_LINK_TITLE', 'Display a list of the pages you currently own');
/**#@-*/

/**#@+
 * Language constant used by the {@link countpages.php countpages} action
 */
// countpages
define('INDEX_LINK_TITLE', 'Display an alphabetical page index');
/**#@-*/

/**#@+
 * Language constant used by the {@link dbinfo.php dbinfo} action
 */
// dbinfo
define('HD_DBINFO','Database Information');
define('HD_DBINFO_DB','Database');
define('HD_DBINFO_TABLES','Tables');
define('HD_DB_CREATE_DDL','DDL to create database %s:');				# %s will hold database name
define('HD_TABLE_CREATE_DDL','DDL to create table %s:');				# %s will hold table name
define('TXT_INFO_1','This utility provides some information about the database(s) and tables in your system.');
define('TXT_INFO_2',' Depending on permissions for the Wikka database user, not all databases or tables may be visible.');
define('TXT_INFO_3',' Where creation DDL is given, this reflects everything that would be needed to exactly recreate the same database and table definitions,');
define('TXT_INFO_4',' including defaults that may not have been specified explicitly.');
define('FORM_SELDB_LEGEND','Databases');
define('FORM_SELTABLE_LEGEND','Tables');
define('FORM_SELDB_OPT_LABEL','Select a database:');
define('FORM_SELTABLE_OPT_LABEL','Select a table:');
define('FORM_SUBMIT_SELDB','Select');
define('FORM_SUBMIT_SELTABLE','Select');
define('MSG_ONLY_ADMIN','Sorry, only administrators can view database information.');
define('MSG_SINGLE_DB','Information for the <tt>%s</tt> database.');			# %s will hold database name
define('MSG_NO_TABLES','No tables found in the <tt>%s</tt> database. Your MySQL user may not have sufficient privileges to access this database.');		# %s will hold database name
define('MSG_NO_DB_DDL','Creation DDL for <tt>%s</tt> could not be retrieved.');	# %s will hold database name
define('MSG_NO_TABLE_DDL','Creation DDL for <tt>%s</tt> could not be retrieved.');# %s will hold table name
/**#@-*/

/**#@+
 * Language constant used by the {@link deletelink.php deletelink} action
 */
define('DELETELINK_TEXT', '[Delete]');
define('DELETELINK_TITLE', 'Delete this page (requires confirmation)');
/**#@-*/

/**#@+
 * Language constant used by the {@link editlink.php editlink} action
 */
define('EDITLINK_TEXT', '[Edit]');
define('SHOWLINK_TEXT', '[Show]');
define('SHOWCODELINK_TEXT', '[Source]');
define('EDITLINK_TITLE', 'Click to edit this page');
define('SHOWLINK_TITLE', 'Displayed the formatted version of this page');
define('SHOWCODELINK_TITLE', 'Display the markup for this page');
/**#@-*/

/**#@+
 * Language constant used by the {@link emailpassword.php emailpassword} action
 */
// emailpassword
define('PW_FORGOTTEN_HEADING', 'Password reminder');
define('PW_CHK_SENT', 'A password reminder has been sent to %s\'s registered email address.'); // %s - username
define('PW_FORGOTTEN_MAIL', 'Hello, %1$s\n\n\nSomeone requested that we send to this email address a password reminder to login at %2$s. If you did not request this reminder, disregard this email. -- No action is necessary. -- Your password will stay the same.\n\nYour wikiname: %1$s \nPassword reminder: %3$s \nURL: %4$s \n\nDo not forget to change the password immediately after logging in.'); // %1$s - username; %2$s - wiki name; %3$s - md5 sum of pw; %4$s - login url of the wiki
define('PW_FORGOTTEN_MAIL_REF', 'Password reminder for %s'); // %s - wiki name
define('PW_FORM_TEXT', 'Enter your WikiName and a password reminder will be sent to your registered email address.');
define('PW_FORM_FIELDSET_LEGEND', 'Your WikiName:');
define('ERROR_UNKNOWN_USER', 'You have entered a non-existent user!');
#define('ERROR_MAIL_NOT_SENT', 'An error occurred while trying to send the password. Outgoing mail might be disabled. Please contact your server administrator.');
define('ERROR_MAIL_NOT_SENT', 'An error occurred while trying to send the password. Outgoing mail might be disabled. Please try to contact your wiki administrator by posting a page comment.');
define('BUTTON_SEND_PW', 'Send reminder');
define('USERSETTINGS_REF', 'Return to the %s page.'); // %s - UserSettings link
define('INPUT_ERROR_STYLE', 'class="highlight"');
define('ERROR_EMPTY_USER', 'Please fill in your username!');
define('BUTTON_SEND_PW_LABEL', 'Send reminder');
define('USERSETTINGS_LINK', 'Return to the [[UserSettings login]] screen.');
/**#@-*/

/**#@+
 * Language constant used by the {@link feedback.php feedback} action
 */
// feedback
define('ERROR_EMPTY_NAME', 'Please enter your name');
define('ERROR_INVALID_EMAIL', 'Please enter a valid email address');
define('ERROR_EMPTY_MESSAGE', 'Please enter some text');
define('ERROR_FEEDBACK_MAIL_NOT_SENT', 'Sorry, An error occurred while trying to send your email. Outgoing mail might be disabled. Please try another method to contact %s, for instance by posting a page comment'); // %s - name of the recipient
define('FEEDBACK_FORM_LEGEND', 'Contact %s'); //%s - wikiname of the recipient
define('FEEDBACK_NAME_LABEL', 'Your name:');
define('FEEDBACK_EMAIL_LABEL', 'Your email:');
define('FEEDBACK_MESSAGE_LABEL', 'Your message:');
define('FEEDBACK_SEND_BUTTON', 'Send');
define('FEEDBACK_SUBJECT', 'Feedback from %s'); // %s - name of the wiki
define('SUCCESS_FEEDBACK_SENT', 'Thanks for your feedback, %s! Your message has been sent'); //%s - name of the sender
/**#@-*/

/**#@+
 * Language constant used by the {@link files.php files action} and {@link handlers/files.xml/files.xml.php files.xml handler}
 */
// files
define('ERROR_UPLOAD_DIRECTORY_NOT_WRITABLE', 'Please make sure that the server has write access to a folder named %s.'); // %s Upload folder ref #89
define('ERROR_UPLOAD_DIRECTORY_NOT_READABLE', 'Please make sure that the server has read access to a folder named %s.'); // %s Upload folder ref #89
define('ERROR_NONEXISTENT_FILE', 'Sorry, a file named %s does not exist.'); // %s - file name ref
define('ERROR_FILE_UPLOAD_INCOMPLETE', 'File upload incomplete! Please try again.');
define('ERROR_UPLOADING_FILE', 'There was an error uploading your file');
define('ERROR_FILE_ALREADY_EXISTS', 'Sorry, a file named %s already exists.'); // %s - file name ref
define('ERROR_EXTENSION_NOT_ALLOWED', 'Sorry, files with this extension are not allowed.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Sorry, files of this type are not allowed.');
define('ERROR_FILE_NOT_DELETED', 'Sorry, the file could not be deleted!');
define('ERROR_FILE_TOO_BIG', 'Attempted file upload was too big. Maximum allowed size is %s.'); // %s - allowed filesize
define('ERROR_NO_FILE_SELECTED', 'No file selected.');
define('ERROR_FILE_UPLOAD_IMPOSSIBLE', 'File upload impossible due to misconfigured server.');
define('SUCCESS_FILE_UPLOADED', 'File was successfully uploaded.');
define('FILE_TABLE_CAPTION', 'Attachments');
define('FILE_TABLE_HEADER_NAME', 'File');
define('FILE_TABLE_HEADER_SIZE', 'Size');
define('FILE_TABLE_HEADER_DATE', 'Last modified');
define('FILE_UPLOAD_FORM_LEGEND', 'Add new attachment:');
define('FILE_UPLOAD_FORM_LABEL', 'File:');
define('FILE_UPLOAD_FORM_BUTTON', 'Upload');
define('DOWNLOAD_LINK_TITLE', 'Download %s'); // %s - file name
define('DELETE_LINK_TITLE', 'Remove %s'); // %s - file name
define('NO_ATTACHMENTS', 'This page contains no attachment.');
define('FILES_DELETE_FILE', 'Delete this file?');
define('FILES_DELETE_FILE_BUTTON', 'Delete File');
define('FILES_CANCEL_BUTTON', 'Cancel');
define('FILE_DELETED', 'File deleted');
define ('NO_FILE_UPLOADS', "<em class='error'>File uploads are disallowed on this server</em>");
define ('NO_FILE_UPLOADED', "<em class='error'>No file uploaded</em>");
define ('ERROR_DURING_FILE_UPLOAD', "<em class='error'>There was an error uploading your file.  Please try again.</em>");
define('ERROR_MAX_FILESIZE_EXCEEDED', "<em class='error'>Attempted file upload was too big.  Maximum allowed size is %d MB.</em>"); 
define('ERROR_FILE_EXISTS', "<em class='error'>There is already a file named <tt>%s</tt>. Please rename before uploading or delete the existing file first.</em>");
/**#@-*/

/**#@+
 * Language constant used by the {@link geshiversion.php geshiversion} action
 */
define('NOT_AVAILABLE', 'n/a');
define('NOT_INSTALLED', 'not installed');
/**#@-*/

/**#@+
 * Language constant used by the {@link googleform.php googleform} action
 */
// googleform
define('GOOGLE_BUTTON', 'Google');
/**#@-*/

/**#@+
 * Language constant used by the {@link highscores.php highscores} action
 */
// include
define('HIGHSCORES_LABEL_EDITS', 'edits');
define('HIGHSCORES_LABEL_COMMENTS', 'comments');
define('HIGHSCORES_LABEL_PAGES', 'pages owned');
define('HIGHSCORES_CAPTION', 'Top %1$s contributor(s) by number of %2$s'); 
define('HIGHSCORES_HEADER_RANK', 'rank');
define('HIGHSCORES_HEADER_USER', 'user');
define('HIGHSCORES_HEADER_PERCENTAGE', 'percentage');
define('HIGHSCORES_DISPLAY_TOP', 10); //limit output to top n users
define('HIGHSCORES_DEFAULT_STYLE', 'complex'); //set default layout style
/**#@-*/

/**#@+
 * Language constants used by the {@link include.php include} action
 */
define('HISTORYLINK_TEXT', '[History]');
define('HISTORYLINK_TITLE', 'Click to view recent edits to this page');
/**#@-*/

/**#@+
 * Language constants used by the {@link include.php include} action
 */
// include
define('ERROR_CIRCULAR_REFERENCE', 'Circular reference detected!');
define('ERROR_TARGET_ACL', "You aren't allowed to read included page <tt>%s</tt>");
define('ERROR_CIRCULAR_REF', 'Circular reference detected');
/**#@-*/

/**#@+
 * Language constant used by the {@link lastedit.php lastedit} action
 */
// lastedit
define('LASTEDIT_DESC', 'Last edited by %s'); // %s user name
define('LASTEDIT_DIFF_LINK_TITLE', 'Show differences from last revision');
define('DEFAULT_SHOW', '3');
define('DATE_FORMAT', 'D, d M Y'); #TODO make this system-configurable
define('TIME_FORMAT', 'H:i T'); #TODO make this system-configurable
define('LASTEDIT_BOX', 'lastedit');
define('LASTEDIT_NOTES', 'lastedit_notes');
define('ANONYMOUS_USER', 'anonymous');
define('LASTEDIT_MESSAGE', 'Last edited by %s');
define('DIFF_LINK_TITLE', 'Show differences from last revision');
/**#@-*/

/**#@+
 * Language constant used by the {@link lastusers.php lastusers} action
 */
// lastusers
define('LASTUSERS_CAPTION', 'Recently registered users');
define('SIGNUP_DATE_TIME', 'Signup Date/Time');
define('NAME_TH', 'Username');
define('OWNED_PAGES_TH', 'Owned pages');
define('SIGNUP_DATE_TIME_TH', 'Signup date/time');
define('LASTUSERS_DEFAULT_STYLE', 'complex'); # consistent parameter naming with HighScores action
define('LASTUSERS_MAX_USERS_DISPLAY', 10);
/**#@-*/

/**#@+
 * Language constant used by the {@link mindmap.php mindmap} action
 */
// mindmap
define('MM_JRE_INSTALL_REQ', 'Please install a %s on your computer.'); // %s - JRE install link
define('MM_DOWNLOAD_LINK_DESC', 'Download this mind map');
define('MM_EDIT', 'Use %s to edit it'); // %s - link to freemind project
define('MM_FULLSCREEN_LINK_DESC', 'Open fullscreen');
define('ERROR_INVALID_MM_SYNTAX', 'Error: Invalid MindMap action syntax.');
define('PROPER_USAGE_MM_SYNTAX', 'Proper usage: %1$s or %2$s'); // %1$s - syntax sample 1; %2$s - syntax sample 2
define('FREEMIND_PROJECT_URL', 'http://freemind.sourceforge.net/');
/**#@-*/

/**#@+
 * Language constant used by the {@link mychanges.php mychanges} action
 */
// mychanges
define('NO_PAGES_EDITED', 'You have not edited any pages yet.');
define('MYCHANGES_ALPHA_LIST', "This is a list of pages edited by %s, along with the time of the last change.");
define('MYCHANGES_DATE_LIST', "This is a list of pages edited by %s, ordered by the time of the last change.");
define('ORDER_DATE_LINK_DESC', 'order by date');
define('ORDER_ALPHA_LINK_DESC', 'order alphabetically');
define('MYCHANGES_NOT_LOGGED_IN', "You're not logged in, thus the list of pages you've edited couldn't be retrieved.");
define('REVISION_DATE_FORMAT', 'D, d M Y');
define('REVISION_TIME_FORMAT', 'H:i T');
define('TITLE_REVISION_LINK', 'View recent revisions list for %s');
/**#@-*/

/**#@+
 * Language constant used by the {@link mypages.php mypages} action
 */
// mypages
define('OWNED_PAGES_TXT', "This is the list of pages owned by %s.");
define('OWNED_NO_PAGES', 'You don\'t own any pages.');
define('OWNED_NONE_FOUND', 'No pages found.');
define('OWNED_NOT_LOGGED_IN', "You're not logged in, thus the list of your pages couldn't be retrieved.");
define('MYPAGES_HEADER', "This is the list of pages owned by %s");
define ('MYPAGES_NONE_OWNED', "You don't own any pages.");
define ('MYPAGES_NONE_FOUND', "No pages found");
define ('MYPAGES_NOT_LOGGED_IN', "You're not logged in, thus the list of your pages couldn't be retrieved.");
/**#@-*/

/**#@+
 * Language constant used by the {@link mysqlversion.php mysqlversion} action
 */
define('NOT_AVAILABLE', 'n/a');
/**#@-*/

/**#@+
 * Language constant used by the {@link newpage.php newpage} action
 */
// newpage
define('NEWPAGE_CREATE_LEGEND', 'Create a new page');
define('NEWPAGE_CREATE_BUTTON', 'Create');
/**#@-*/

/**#@+
 * Language constant used by the {@link orphanedpages.php orphanedpages} action
 */
// orphanedpages
define('NO_ORPHANED_PAGES', 'No orphaned pages. Good!');

/**#@+
 * Language constant used by the {@link ownedpages.php ownedpages} action
 */
// ownedpages
define('OWNEDPAGES_COUNTS', 'You own %1$s pages out of the %2$s pages on this Wiki.'); // %1$s - number of pages owned; %2$s - total number of pages
define('OWNEDPAGES_PERCENTAGE', 'That means you own %s of the total.'); // %s percentage of pages owned
/**#@-*/

/**#@+
 * Language constant used by the {@link ownerlink.php ownerlink} action
 */
define('OWNERLINK_PUBLIC_PAGE', 'Public page');
define('OWNERLINK_NOBODY', 'Nobody');
define('OWNERLINK_OWNER', 'Owner:');
define('OWNERLINK_SELF', 'You own this page');
define('EDITACLLINK_TEXT', '[Edit ACLs]');
define('EDITACLLINK_TITLE', 'Change the Access Control List for this page');
define('CLAIMLINK_TEXT', '[Take Ownership]');
define('CLAIMLINK_TITLE', 'Click to become the owner of this page');
/**#@-*/

/**#@+
 * Language constant used by the {@link pageindex.php pageindex} action
 */
// pageindex
define('PAGEINDEX_HEADING', 'Page Index');
define('PAGEINDEX_CAPTION', 'This is an alphabetical list of pages you can read on this server.');
define('PAGEINDEX_OWNED_PAGES_CAPTION', 'Items marked with a * indicate pages that you own.');
define('PAGEINDEX_ALL_PAGES', 'All');
define('PAGE_HEADING',"Page Index");
define('INDEX_CAPTION',"This is an alphabetical list of pages you can read on this server.");
define('ALL_PAGES',"All");
define('PAGE_OWNER'," . . . . Owner: %s");
define('OWNED_PAGES_CAPTION',"Items marked with a * indicate pages that you own.");
define('ERROR_NO_PAGES_FOUND', "No pages found.");
/**#@-*/

/**#@+
 * Language constant used by the {@link phpversion.php phpversion} action
 */
define('NOT_AVAILABLE', 'n/a');
/**#@-*/

/**#@+
 * Language constant used by the {@link recentchanges.php recentchanges} action
 */
// recentchanges
define('RECENTCHANGES_HEADING', 'Recently changed pages');
define('REVISIONS_LINK_TITLE', 'View recent revisions list for %s'); // %s - page name
define('HISTORY_LINK_TITLE', 'View edit history of %s'); // %s - page name
define('WIKIPING_ENABLED', 'WikiPing enabled: Changes on this wiki are broadcast to %s'); // %s - link to wikiping server
define('RECENTCHANGES_NONE_FOUND', 'There are no recently changed pages.');
define('RECENTCHANGES_NONE_ACCESSIBLE', 'There are no recently changed pages you have access to.');
define('REVISION_DATE_FORMAT', 'D, d M Y');
define('REVISION_TIME_FORMAT', 'H:i T');
define ('PAGE_EDITOR_DIVIDER', '&#8594;');
define ('MAX_REVISION_NUMBER', '50');
define('RECENT_CHANGES_HEADING', '=====Recently changed pages=====');
define('UNREGISTERED_USER', 'unregistered user');
define('LABEL_HISTORY', 'history');
define('TITLE_REVISION_LINK', 'View recent revisions list for %s');
define('TITLE_HISTORY_LINK', 'View edit history of %s');
define ('NO_RECENTLY_CHANGED_PAGES', 'There are no recently changed pages.');
define ('NO_READABLE_RECENTLY_CHANGED_PAGES', 'There are no recently changed pages you have access to.');
/**#@-*/

/**#@+
 * Language constant used by the {@link recentcomments.php recentcomments} action
 */
// recentcomments
define('RECENTCOMMENTS_HEADING', 'Recent comments');
define('RECENTCOMMENTS_TIMESTAMP_CAPTION', '%s'); // %s - timestamp
define('RECENTCOMMENTS_NONE_FOUND', 'There are no recent comments.');
define('RECENTCOMMENTS_NONE_ACCESSIBLE', 'There are no recent comments you have access to.');
define('COMMENT_DATE_FORMAT', 'D, d M Y');
define('COMMENT_TIME_FORMAT', 'H:i T');
define('COMMENT_SNIPPET_LENGTH', 120);
define('RECENT_COMMENTS_HEADING', '=====Recent comments=====');
define ('COMMENT_AUTHOR_DIVIDER', ', comment by ');
define ('NO_RECENT_COMMENTS', 'There are no recent comments%s');
define ('NO_READABLE_RECENT_COMMENTS', 'There are no recent comments you can read.');
define('COMMENT_DATE_FORMAT', 'D, d M Y');
define('COMMENT_TIME_FORMAT', 'H:i T');
define('COMMENT_SNIPPET_LENGTH', 120);
define('RECENTLY_COMMENTED_HEADING', '=====Recently commented pages=====');
define('ANONYMOUS_COMMENT_AUTHOR', '(unregistered user)');
define ('NO_RECENTLY_COMMENTED', 'There are no recently commented pages%s');
define ('NO_READABLE_RECENTLY_COMMENTED', 'There are no recently commented pages you can read.');
/**#@-*/

/**#@+
 * Language constant used by the {@link recentlycommented.php recentlycommented} action
 */
// recentlycommented
define('RECENTLYCOMMENTED_HEADING', 'Recently commented pages');
define('RECENTLYCOMMENTED_NONE_FOUND', 'There are no recently commented pages.');
define('RECENTLYCOMMENTED_NONE_ACCESSIBLE', 'There are no recently commented pages you have access to.');
/**#@-*/

/**#@+
 * Language constant used by the {@link revert.php revert} action
 */
define('ERROR_NO_REVERT_PRIVS', "Sorry, you don't have privileges to revert this page");
/**#@-*/

/**#@+
 * Language constant used by the {@link revertlink.php revertlink} action
 */
define('REVERTLINK_TEXT', '[Revert]');
define('REVERTLINK_TITLE', 'Click to revert this page to the previous revision');
define('REVERTLINK_OLDEST_TITLE', 'This is the oldest known version for this page');
/**#@-*/

/**#@+
 * Language constant used by the {@link revisionlink.php revisionlink} action
 */
define('REVISIONLINK_TITLE', 'Click to view recent revisions list for this page');
define('REVISIONFEEDLINK_TITLE', 'Click to display a feed with the latest revisions to this page');
/**#@-*/

/**#@+
 * Language constant used by the {@link searchform.php searchform} action
 */
define('SEARCHFORM_LABEL', 'Search: ');
/**#@-*/

/**#@+
 * Language constant used by the {@link system.php system} action
 */
// system
define('SYSTEM_HOST_CAPTION', '(%s)'); // %s - host name
define('WIKKA_STATUS_NOT_AVAILABLE', 'n/a');
define('NOT_AVAILABLE', 'n/a');
/**#@-*/

/**#@+
 * Language constant shared by the {@link textsearch.php textsearch} and {@link textsearchexpanded.php textsearchexpanded} actions
 */
// textsearch & textsearchexpanded
define('SEARCH_FOR', 'Search for');
define('SEARCH_ZERO_MATCH', 'No matches');
define('SEARCH_ONE_MATCH', 'One match found');
define('SEARCH_N_MATCH', '%d matches found'); // %d - number of hits
define('SEARCH_RESULTS', 'Search results: <strong>%1$s</strong> for <strong>%2$s</strong>'); # %1$s: n matches for | %2$s: search term
define('SEARCH_NOT_SURE_CHOICE', 'Not sure which page to choose?');
define('SEARCH_EXPANDED_LINK_DESC', 'Expanded Text Search'); // search link description
define('SEARCH_TRY_EXPANDED', 'Try the %s which shows surrounding text.'); // %s expanded search link
/*
define('SEARCH_TIPS', "<br /><br /><hr /><br /><strong>Search Tips:</strong><br /><br />"
	."<div class=\"indent\">apple banana</div>"
	."Find pages that contain at least one of the two words. <br />"
	."<br />"
	."<div class=\"indent\">+apple +juice</div>"
	."Find pages that contain both words. <br />"
	."<br />"
	."<div class=\"indent\">+apple -macintosh</div>"
	."Find pages that contain the word 'apple' but not 'macintosh'. <br />"
	."<br />"
	."<div class=\"indent\">apple*</div>"
	."Find pages that contain words such as apple, apples, applesauce, or applet. <br />"
	."<br />"
	."<div class=\"indent\">\"some words\"</div>"
	."Find pages that contain the exact phrase 'some words' (for example, pages that contain 'some words of wisdom' <br />"
	."but not 'some noise words'). <br />");
*/
define('SEARCH_TIPS', 'Search Tips:');
define('SEARCH_WORD_1', 'apple');
define('SEARCH_WORD_2', 'banana');
define('SEARCH_WORD_3', 'juice');
define('SEARCH_WORD_4', 'macintosh');
define('SEARCH_WORD_5', 'some');
define('SEARCH_WORD_6', 'words');
define('SEARCH_PHRASE',sprintf('%s %s',SEARCH_WORD_5,SEARCH_WORD_6));
define('SEARCH_TARGET_1', 'Find pages that contain at least one of the two words.');
define('SEARCH_TARGET_2', 'Find pages that contain both words.');
define('SEARCH_TARGET_3',sprintf("Find pages that contain the word '%1\$s' but not '%2\$s'.",SEARCH_WORD_1,SEARCH_WORD_4));
define('SEARCH_TARGET_4',"Find pages that contain words such as 'apple', 'apples', 'applesauce', or 'applet'."); // make sure target words all *start* with SEARCH_WORD_1
define('SEARCH_TARGET_5',sprintf("Find pages that contain the exact phrase '%1\$s' (for example, pages that contain '%1\$s of wisdom' but not '%2\$s noise %3\$s').",SEARCH_PHRASE,SEARCH_WORD_5,SEARCH_WORD_6));
define('SEARCH_MAX_SNIPPETS', 3);
/**#@-*/

/**#@+
 * Language constant used by the {@link usersettings.php usersettings} action
 */
// usersettings
// - error messages
define('ERROR_EMPTY_USERNAME', 'Please fill in your user name.');
define('ERROR_NONEXISTENT_USERNAME', 'Sorry, this user name doesn\'t exist.'); // @@@ too specific
define('ERROR_RESERVED_PAGENAME', 'Sorry, this name is reserved for a page. Please choose a different name.');
define('ERROR_WIKINAME', 'Username must be formatted as a %1$s, e.g. %2$s.'); // %1$s - identifier WikiName; %2$s - sample WikiName
define('ERROR_EMPTY_EMAIL_ADDRESS', 'Please specify an email address.');
define('ERROR_INVALID_EMAIL_ADDRESS', 'That doesn\'t quite look like an email address.');
define('ERROR_INVALID_PASSWORD', 'Sorry, you entered the wrong password.');	// @@@ too specific
define('ERROR_INVALID_HASH', 'Sorry, you entered a wrong password reminder.');
define('ERROR_INVALID_OLD_PASSWORD', 'The old password you entered is wrong.');
define('ERROR_EMPTY_PASSWORD', 'Please fill in a password.');
define('ERROR_EMPTY_PASSWORD_OR_HASH', 'Please fill your password or password reminder.');
define('ERROR_EMPTY_CONFIRMATION_PASSWORD', 'Please confirm your password in order to register a new account.');
define('ERROR_EMPTY_NEW_CONFIRMATION_PASSWORD', 'Please confirm your new password in order to update your account.');
define('ERROR_EMPTY_NEW_PASSWORD', 'You must also fill in a new password.');
define('ERROR_PASSWORD_MATCH', 'Passwords don\'t match.');
define('ERROR_PASSWORD_NO_BLANK', 'Sorry, blanks are not permitted in the password.');
define('ERROR_PASSWORD_TOO_SHORT', 'Sorry, the password must contain at least %d characters.'); // %d - minimum password length
define('ERROR_INVALID_REVISION_DISPLAY_LIMIT', 'The number of page revisions should not exceed %d.'); // %d - maximum revisions to view
define('ERROR_INVALID_RECENTCHANGES_DISPLAY_LIMIT', 'The number of recently changed pages should not exceed %d.'); // %d - maximum changed pages to view
if(!defined('ERROR_VALIDATION_FAILED')) define('ERROR_VALIDATION_FAILED', "Registration validation failed, please try again!");
// - success messages
define('SUCCESS_USER_LOGGED_OUT', 'You have successfully logged out.');
define('SUCCESS_USER_REGISTERED', 'You have successfully registered!');
define('SUCCESS_USER_SETTINGS_STORED', 'User settings stored!');
define('SUCCESS_USER_PASSWORD_CHANGED', 'Password successfully changed!');
// - captions
define('NEW_USER_REGISTER_CAPTION', 'If you are signing up as a new user:');
define('REGISTERED_USER_LOGIN_CAPTION', 'If you already have a login, sign in here:');
define('RETRIEVE_PASSWORD_CAPTION', 'Log in with your [[%s password reminder]]:'); //%s PasswordForgotten link
define('USER_LOGGED_IN_AS_CAPTION', 'You are logged in as %s'); // %s user name
// - form legends
define('USER_ACCOUNT_LEGEND', 'Your account');
define('USER_SETTINGS_LEGEND', 'Settings');
define('LOGIN_REGISTER_LEGEND', 'Login/Register');
define('LOGIN_LEGEND', 'Login');
#define('REGISTER_LEGEND', 'Register'); // @@@ TODO to be used later for register-action
define('CHANGE_PASSWORD_LEGEND', 'Change your password');
define('RETRIEVE_PASSWORD_LEGEND', 'Password forgotten');
// - form field labels (should end in ':' _unless_ it's a checkbox or radio button option)
define('USERSETTINGS_REDIRECT_AFTER_LOGIN_LABEL', 'Redirect to %s after login');	// %s page to redirect to
define('USER_EMAIL_LABEL', 'Your email address:');
define('DOUBLECLICK_LABEL', 'Doubleclick editing:');
define('SHOW_COMMENTS_LABEL', 'Show comments by default:');
define('DEFAULT_COMMENT_STYLE_LABEL', 'Default comment style');
define('COMMENT_ASC_LABEL', 'Flat (oldest first)');
define('COMMENT_DEC_LABEL', 'Flat (newest first)');
define('COMMENT_THREADED_LABEL', 'Threaded');
define('COMMENT_DELETED_LABEL', '[Comment deleted]');
define('COMMENT_BY_LABEL', 'Comment by ');
define('RECENTCHANGES_DISPLAY_LIMIT_LABEL', 'RecentChanges display limit:');
define('PAGEREVISION_LIST_LIMIT_LABEL', 'Page revisions list limit:');
define('NEW_PASSWORD_LABEL', 'Your new password:');
define('NEW_PASSWORD_CONFIRM_LABEL', 'Confirm new password:');
define('NO_REGISTRATION', 'Registration on this wiki is disabled.');
define('PASSWORD_LABEL', 'Password (%s+ chars):'); // %s minimum number of characters
define('CONFIRM_PASSWORD_LABEL', 'Confirm password:');
define('TEMP_PASSWORD_LABEL', 'Password reminder:');
define('INVITATION_CODE_SHORT', 'Invitation Code');
define('INVITATION_CODE_LONG', 'In order to register, you must fill in the invitation code sent by this website\'s administrator.');
define('INVITATION_CODE_LABEL', 'Your %s:'); // %s - expanded short invitation code prompt
define('WIKINAME_SHORT', 'WikiName');
define('WIKINAME_LONG',sprintf('A WikiName is formed by two or more capitalized words without space, e.g. %s',WIKKA_SAMPLE_WIKINAME));
define('WIKINAME_LABEL', 'Your %s:'); // %s - expanded short wiki name prompt
// - form options
define('CURRENT_PASSWORD_OPTION', 'Your current password');
define('PASSWORD_REMINDER_OPTION', 'Password reminder');
// - form buttons
define('UPDATE_SETTINGS_BUTTON', 'Update Settings');
define('LOGIN_BUTTON', 'Login');
define('LOGOUT_BUTTON', 'Logout');
define('CHANGE_PASSWORD_BUTTON', 'Change password');
define('REGISTER_BUTTON', 'Register');
define('PASSWORD_MIN_LENGTH', "5");
define('VALID_EMAIL_PATTERN', "/^.+?\@.+?\..+$/"); //TODO: Use central regex library
define('REVISION_DISPLAY_LIMIT_MIN', "0"); // 0 means no limit, 1 is the minimum number of revisions
define('REVISION_DISPLAY_LIMIT_MAX', "20"); // keep this value within a reasonable limit to avoid an unnecessary long lists
define('RECENTCHANGES_DISPLAY_LIMIT_MIN', "0"); // 0 means no limit, 1 is the minimum number of changes
define('RECENTCHANGES_DISPLAY_LIMIT_MAX', "50"); // keep this value within a reasonable limit to avoid an unnecessary long list
define('INPUT_ERROR_STYLE', 'class="highlight"');
define('USER_SETTINGS_HEADING', "User settings");
define('USER_LOGGED_OUT', "You have successfully logged out.");
define('USER_SETTINGS_STORED', "User settings stored!");
define('ERROR_NO_BLANK', "Sorry, blanks are not permitted in the password.");
define('PASSWORD_CHANGED', "Password successfully changed!");
define('ERROR_OLD_PASSWORD_WRONG', "The old password you entered is wrong.");
define('UPDATE_SETTINGS_INPUT', "Update Settings");
define('CHANGE_PASSWORD_HEADING', "Change your password:");
define('CURRENT_PASSWORD_LABEL', "Your current password:");
define('PASSWORD_REMINDER_LABEL', "Password reminder:");
define('CHANGE_BUTTON_LABEL', "Change password");
define('REGISTER_BUTTON_LABEL', "Register");
define('QUICK_LINKS_HEADING', "Quick links");
define('QUICK_LINKS', "See a list of pages you own (MyPages) and pages you've edited (MyChanges).");
define('ERROR_WRONG_PASSWORD', "Sorry, you entered the wrong password.");
define('ERROR_WRONG_HASH', "Sorry, you entered a wrong password reminder.");
define('ERROR_NON_EXISTENT_USERNAME', "Sorry, this user name doesn't exist.");
define('ERROR_USERNAME_EXISTS', "Sorry, this user name already exists.");
define('ERROR_EMAIL_ADDRESS_REQUIRED', "Please specify an email address.");
define('REGISTRATION_SUCCEEDED', "You have successfully registered!");
define('REGISTERED_USER_LOGIN_LABEL', "If you're already a registered user, log in here!");
define('LOGIN_HEADING', "===Login===");
define('LOGIN_REGISTER_HEADING', "===Login/Register===");
define('LOGIN_BUTTON_LABEL', "Login");
define('LOGOUT_BUTTON_LABEL', "Logout");
define('NEW_USER_REGISTER_LABEL', "Fields you only need to fill in when you're logging in for the first time (and thus signing up as a new user on this site).");
define('RETRIEVE_PASSWORD_HEADING', "===Forgot your password?===");
define('RETRIEVE_PASSWORD_MESSAGE', "If you need a password reminder, click [[PasswordForgotten here]]. --- You can login here using your password reminder.");
define('THEME_LABEL', 'Theme:');
/**#@-*/

/**#@+
 * Language constant used by the {@link wantedpages.php wantedpages} action
 */
// wantedpages
define('SORTING_LEGEND', 'Sorting ...');
define('SORTING_NUMBER_LABEL', 'Sorting #%d:');
define('SORTING_DESC_LABEL', 'desc');
define('OK_BUTTON', '   OK   ');
define('NO_WANTED_PAGES', 'No wanted pages. Good!');
/**#@-*/

/**#@+
 * Language constant used by the {@link wikkaconfig.php wikkaconfig} action
 */
//wikkaconfig
define('WIKKACONFIG_CAPTION', "Wikka Configuration Settings [%s]"); // %s link to Wikka Config options documentation
define('WIKKACONFIG_DOCS_URL', "http://docs.wikkawiki.org/ConfigurationOptions");
define('WIKKACONFIG_DOCS_TITLE', "Read the documentation on Wikka Configuration Settings");
define('WIKKACONFIG_TH_OPTION', "Option");
define('WIKKACONFIG_TH_VALUE', "Value");

/**#@+
 * Language constant used by the {@link wikkapatchlevel.php wikkapatchlevel} action
 */
define('NOT_AVAILABLE', 'n/a');
/**#@-*/

/**#@+
 * Language constant used by the {@link wikkaversion.php wikkaversion} action
 */
define('NOT_AVAILABLE', 'n/a');
/**#@-*/

/* ------------------ 3RD PARTY ------------------ */

/**#@+
 * Language constant used by the {@link fullscreen.php fullscreen} 3rd party MindMap display utility
 */
// fullscreen
define('CLOSE_WINDOW', 'Close Window');
define('MM_GET_JAVA_PLUGIN_LINK_DESC', 'get the latest Java Plug-in here'); // used in MM_GET_JAVA_PLUGIN
define('MM_GET_JAVA_PLUGIN', 'so if it does not work, %s.'); // %s - plugin download link
/**#@-*/


/* ------------------ FORMATTERS ------------------ */

/**#@+
 * Language constant used by the {@link wakka.php wakka} formatter
 */
// wakka
define('GRABCODE_BUTTON', 'Grab');
define('GRABCODE_BUTTON_TITLE', 'Download %s'); // %s download filename
/**#@-*/


/* ------------------ HANDLERS (PAGE) ------------------ */

/**#@+
 * Language constant used by the {@link acls.php acls} (page) handler
 */
// acls
// TODO: 'translate' DB value '(Public)' when displaying it!
define('ACLS_UPDATED', 'Access control lists updated.');
define('NO_PAGE_OWNER', '(Nobody)');
define('NOT_PAGE_OWNER', 'You are not the owner of this page.');
define('PAGE_OWNERSHIP_CHANGED', 'Ownership changed to %s'); // %s - name of new owner
define('ACLS_LEGEND', 'Access Control Lists for %s'); // %s - name of current page
define('ACLS_READ_LABEL', 'Read ACL:');
define('ACLS_WRITE_LABEL', 'Write ACL:');
define('ACLS_COMMENT_READ_LABEL', 'Comment Read ACL:');
define('ACLS_COMMENT_POST_LABEL', 'Comment Post ACL:');
define('SET_OWNER_LABEL', 'Set Page Owner:');
define('SET_OWNER_CURRENT_OPTION', '(Current Owner)');
define('SET_OWNER_PUBLIC_OPTION', '(Public)'); // actual DB value will remain '(Public)' even if this option text is translated!
define('SET_NO_OWNER_OPTION', '(Nobody - Set free)');
define('ACLS_STORE_BUTTON', 'Store ACLs');
define('CANCEL_BUTTON', 'Cancel');
// - syntax
define('ACLS_SYNTAX_HEADING', 'Syntax:');
define('ACLS_EVERYONE', 'Everyone');
define('ACLS_REGISTERED_USERS', 'Registered users');
define('ACLS_NONE_BUT_ADMINS', 'No one (except admins)');
define('ACLS_ANON_ONLY', 'Anonymous users only');
define('ACLS_LIST_USERNAMES', 'the user called %s; enter as many users as you want, one per line'); // %s - sample user name
define('ACLS_NEGATION', 'Any of these items can be negated with a %s:'); // %s - 'negation' mark
define('ACLS_DENY_USER_ACCESS', '%s will be denied access'); // %s - sample user name
define('ACLS_AFTER', 'after');
define('ACLS_TESTING_ORDER1', 'ACLs are tested in the order they are specified:');
define('ACLS_TESTING_ORDER2', 'So be sure to specify %1$s on a separate line %2$s negating any users, not before.'); // %1$s - 'all' mark; %2$s - emphasised 'after'
define('ACLS_DEFAULT_ACLS', 'Any lists that are left empty will be set to the defaults as specified in %s.');
define('ACL_HEADING', '====Access Control Lists for %s===='); // %s - name of current page
define('READ_ACL_LABEL', 'Read ACL:');
define('WRITE_ACL_LABEL', 'Write ACL:');
define('COMMENT_ACL_LABEL', 'Comment ACL:');
define('CANCEL_ACL_LABEL', 'Cancel');
define('STORE_ACL_LABEL', 'Store ACLs');
define('SET_OWNER_CURRENT_LABEL', '(Current Owner)');
define('SET_OWNER_PUBLIC_LABEL','(Public)');
define('SET_NO_OWNER_LABEL', '(Nobody - Set free)');
define('ACL_SYNTAX_HELP', '===Syntax:=== ---##*## = Everyone ---##+## = Registered users ---##""JohnDoe""## = the user called ""JohnDoe"", enter as many users as you want, one per line --- --- Any of these items can be negated with a ##!##: ---##!*## = No one (except admins) ---##!+## = Anonymous users only ---##""!JohnDoe""## = ""JohnDoe"" will be denied access --- --- //ACLs are tested in the order they are specified:// --- So be sure to specify ##*## on a separate line //after// negating any users, not before.');
/**#@-*/

/**#@+
 * Language constant used by the {@link backlinks.php backlinks} (page) handler
 */
// backlinks
define('PAGE_TITLE','Pages linking to %s');
define('MESSAGE_NO_BACKLINKS','There are no backlinks to this page.');
define('MESSAGE_MISSING_PAGE','Sorry, page %s does not exist.');
define('MESSAGE_PAGE_INACCESSIBLE', 'You are not allowed to read this page');
/**#@-*/

/**#@+
 * Language constant used by the {@link claim.php claim} (page) handler
 */
// claim
define('USER_IS_NOW_OWNER', 'You are now the owner of this page.');
/**#@-*/

/**#@+
 * Language constant used by the {@link clone.php clone} (page) handler
 */
// clone
define('ERROR_ACL_WRITE', 'Sorry! You don\'t have write-access to %s');
define('CLONE_VALID_TARGET', 'Please fill in a valid target page name and an (optional) edit note.');
define('CLONE_LEGEND', 'Clone %s'); // %s source page name
define('CLONED_FROM', 'Cloned from %s'); // %s source page name
define('SUCCESS_CLONE_CREATED', '%s was succesfully created!'); // %s new page name
define('CLONE_X_TO_LABEL', 'Clone as:');
define('CLONE_EDIT_NOTE_LABEL', 'Edit note:');
define('CLONE_EDIT_OPTION_LABEL', ' Edit after creation');
define('CLONE_ACL_OPTION_LABEL', ' Clone ACL');
define('CLONE_BUTTON', 'Clone');
define('CLONE_HEADER', 'Clone current page');
define('CLONE_SUCCESSFUL', '%s was succesfully created!');
define('CLONE_X_TO', 'Clone %s to:');
define('EDIT_NOTE', 'Edit note:');
define('ERROR_ACL_READ', 'You are not allowed to read the source of this page.');
define('ERROR_INVALID_PAGENAME', 'This page name is invalid. Valid page names must start with a letter and contain only letters and numbers.');
define('ERROR_PAGE_ALREADY_EXIST', 'Sorry, the destination page already exists');
define('ERROR_PAGE_NOT_EXIST', ' Sorry, page %s does not exist.');
define('LABEL_CLONE', 'Clone');
define('LABEL_EDIT_OPTION', ' Edit after creation ');
define('PLEASE_FILL_VALID_TARGET', 'Please fill in a valid target <tt>PageName</tt> and an (optional) edit note.');
/**#@-*/

/**#@+
 * Language constant used by the {@link delete.php delete} (page) handler
 */
// delete
define('ERROR_NO_PAGE_DEL_ACCESS', 'You are not allowed to delete this page.');
define('PAGE_DELETION_HEADER', 'Delete %s'); // %s - name of the page
define('SUCCESS_PAGE_DELETED', 'Page has been deleted!');
define('PAGE_DELETION_CAPTION', 'Completely delete this page, including all comments?');
define('PAGE_DELETION_DELETE_BUTTON', 'Delete Page');
define('PAGE_DELETION_CANCEL_BUTTON', 'Cancel');
define('CANCEL_ACL_LABEL', 'Cancel');
/**#@-*/

/**#@+
 * Language constant used by the {@link diff.php diff} (page) handler
 */
// diff
define('ERROR_DIFF_LIBRARY_MISSING', 'The file <tt>'.WIKKA_LIBRARY_PATH.DIRECTORY_SEPARATOR.'diff.lib.php</tt> could not be found. You may want to notify the wiki administrator');
define('ERROR_BAD_PARAMETERS', 'The parameters you supplied are incorrect, one of the two revisions may have been removed.');
define('DIFF_COMPARISON_HEADER', 'Comparing %1$s for %2$s'); // %1$s - link to revision list; %2$s - link to page
define('DIFF_REVISION_LINK_TITLE', 'Display the revision list for %s'); // %s page name
define('DIFF_PAGE_LINK_TITLE', 'Return to the latest version of this page');
define('DIFF_SAMPLE_ADDITION', 'addition');
define('DIFF_SAMPLE_DELETION', 'deletion');
define('DIFF_SIMPLE_BUTTON', 'Simple Diff');
define('DIFF_FULL_BUTTON', 'Full Diff');
define('HIGHLIGHTING_LEGEND', 'Highlighting Guide:');
define ('ERROR_DIV_LIBRARY_MISSING', 'The necessary file "libs/diff.lib.php" could not be found. Please make sure the file exists and is placed in the right directory!');
define ('ERROR_NO_PAGE_ACCESS', 'You are not authorized to view this page.');
define ('CONTENT_ADDITIONS_HEADER', 'Additions:');
define ('CONTENT_DELETIONS_HEADER', 'Deletions:');
define ('CONTENT_NO_DIFFERENCES', 'No Differences');
define('WHEN_BY_WHO', '%1$s by %2$s');
define('UNREGISTERED_USER', 'unregistered user');
/**#@-*/

/**#@+
 * Language constant used by the {@link edit.php edit} (page) handler
 */
// edit
define('ERROR_OVERWRITE_ALERT1', 'OVERWRITE ALERT: This page was modified by someone else while you were editing it.');
define('ERROR_OVERWRITE_ALERT2', 'Please copy your changes and re-edit this page.');
define('ERROR_MISSING_EDIT_NOTE', 'MISSING EDIT NOTE: Please fill in an edit note!');
define('ERROR_TAG_TOO_LONG', 'Page name too long! %d characters max.'); // %d - maximum page name length
define('ERROR_NO_WRITE_ACCESS', 'You don\'t have write access to this page. You might need to [[UserSettings login]] or [[UserSettings register an account]] to be able to edit this page.'); //TODO Distinct links for login and register actions
define('EDIT_STORE_PAGE_LEGEND', 'Store page');
define('EDIT_PREVIEW_HEADER', 'Preview');
define('EDIT_NOTE_LABEL', 'Please add a note on your edit'); // label after field, so no colon!
define('MESSAGE_AUTO_RESIZE', 'Clicking on %s will automatically truncate the page name to the correct size'); // %s - rename button text
define('EDIT_PREVIEW_BUTTON', 'Preview');
define('EDIT_STORE_BUTTON', 'Store');
define('EDIT_REEDIT_BUTTON', 'Re-edit');
define('EDIT_CANCEL_BUTTON', 'Cancel');
define('EDIT_RENAME_BUTTON', 'Rename');
define('ACCESSKEY_PREVIEW', 'p'); // ideally, should match EDIT_PREVIEW_BUTTON
define('ACCESSKEY_STORE', 's'); // ideally, should match EDIT_STORE_BUTTON
define('ACCESSKEY_REEDIT', 'r'); // ideally, should match EDIT_REEDIT_BUTTON
define('SHOWCODE_LINK', 'View formatting code for this page');
define('SHOWCODE_LINK_TITLE', 'Click to view page formatting code'); // @@@ TODO 'View page formatting code'
define('EDIT_COMMENT_TIMESTAMP_CAPTION', '(%s)'); // %s timestamp
define('ERROR_INVALID_PAGEID', 'The revision id does not exist for the requested page');
define ('MAX_TAG_LENGTH', 75);
define ('MAX_EDIT_NOTE_LENGTH', 50);
define('INPUT_ERROR_STYLE', 'class="highlight"');
define('PREVIEW_HEADER', 'Preview');
define('LABEL_EDIT_NOTE', 'Please add a note on your edit');
define('ERROR_OVERWRITE_ALERT', 'OVERWRITE ALERT: This page was modified by someone else while you were editing it.<br /> Please copy your changes and re-edit this page.');
define('INPUT_SUBMIT_PREVIEW', 'Preview');
define('INPUT_SUBMIT_STORE', 'Store');
define('INPUT_SUBMIT_REEDIT', 'Re-edit');
define('INPUT_BUTTON_CANCEL', 'Cancel');
define('INPUT_SUBMIT_RENAME', 'Rename');
/**#@-*/

/**#@+
 * Language constant used by the {@link grabcode.php grabcode} (page) handler
 */
// grabcode
define('ERROR_NO_CODE', 'Sorry, there is no code to download.');
define('DEFAULT_FILENAME', 'codeblock.txt'); # default name for code blocks
define('FILE_EXTENSION', '.txt'); # extension appended to code block name
/**#@-*/

/**#@+
 * Language constant used by the {@link history.php history} (page) handler
 */
// history
define('EDITED_ON', 'Edited on %1$s by %2$s'); // %1$s - time; %2$s - user name
define('HISTORY_PAGE_VIEW', 'History of recent changes for %s'); // %s pagename
define('OLDEST_VERSION_EDITED_ON_BY', 'The oldest known version of this page was created on %1$s by %2$s'); // %1$s - time; %2$s - user name
define('MOST_RECENT_EDIT', 'Last edited on %1$s by %2$s');
define('HISTORY_MORE_LINK_DESC', 'here'); // used for alternative history link in HISTORY_MORE
define('HISTORY_MORE', 'Full history for this page cannot be displayed within a single page, click %s to view more.'); // %s alternative history link # @@@ TODO avoid using 'here' ^
define('DIFF_ADDITIONS', 'Additions:');
define('DIFF_DELETIONS', 'Deletions:');
define('DIFF_NO_DIFFERENCES', 'No differences.');
define('REVISION_NUMBER', 'Revision %s');
define('UNREGISTERED_USER', 'unregistered user');
/**#@-*/

/**#@+
 * Language constant shared by the {@link processcomment.php processcomment} and {@link show.php show} (page) handlers
 */
// processcomment & show
// - comment buttons
define('COMMENT_DELETE_BUTTON', 'Delete');
define('COMMENT_REPLY_BUTTON', 'Reply');
define('COMMENT_ADD_BUTTON', 'Add Comment');
define('COMMENT_NEW_BUTTON', 'New Comment');
/**#@-*/

/**#@+
 * Language constant used by the {@link processcomment.php processcomment} (page) handler
 */
// processcomment
define('ERROR_NO_COMMENT_DEL_ACCESS', 'Sorry, you\'re not allowed to delete this comment!');
define('ERROR_NO_COMMENT_WRITE_ACCESS', 'Sorry, you\'re not allowed to post comments to this page');
define('ERROR_EMPTY_COMMENT', 'Comment body was empty -- not saved!');
define('ERROR_COMMENT_NO_KEY', "Your comment cannot be saved. Please contact the wiki administrator.");
define('ERROR_COMMENT_INVALID_KEY', "Your comment cannot be saved. Please contact the wiki administrator.");
define('ADD_COMMENT_LABEL', 'In reply to %s:');
define('NEW_COMMENT_LABEL', 'Post a new comment:');
/**#@-*/

/**#@+
 * Language constant used by the {@link recentchanges_simple.xml.mm.php recentchanges_simple.xml.mm} (page) handler
 */
// recentchanges_simple.xml.mm
define('FIRST_NODE_LABEL', 'Recent Changes');
/**#@-*/

/**#@+
 * Language constant used by the {@link recentchanges.xml.php recentchanges.xml} (page) handler
 */
// recentchanges.xml
define('RECENTCHANGES_DESC', 'Recent changes of %s'); // %s - page name
define('ERROR_ACL_READ_INFO', 'You\'re not allowed to access this information.');
define('LABEL_ERROR', 'Error');
define('I18N_LANG', 'en-us');
/**#@-*/

/**#@+
 * Language constant shared by the {@link referrers_sites.php referrers_sites}, {@link referrers.php referrers} and {@link review_blacklist.php review_blacklist} (page) handlers
 */
// referrers_sites + referrers + review_blacklist
define('REFERRERS_PURGE_24_HOURS', 'last 24 hours');
define('REFERRERS_PURGE_N_DAYS', 'last %d days'); // %d number of days
define('REFERRERS_NO_SPAM', 'Note to spammers: This page is not indexed by search engines, so don\'t waste your time.');
define('REFERRERS_DOMAINS_TO_WIKI_LINK_DESC', 'View global referring sites');
define('REFERRERS_DOMAINS_TO_PAGE_LINK_DESC', 'View referring sites for %s only'); // %s - page name
define('REFERRERS_URLS_TO_WIKI_LINK_DESC', 'View global referrers');
define('REFERRERS_URLS_TO_PAGE_LINK_DESC', 'View referrers for %s only'); // %s - page name
define('REFERRER_BLACKLIST_LINK_DESC', 'View referrer blacklist');
define('BLACKLIST_LINK_DESC', 'Blacklist');
define('NONE_CAPTION', 'None');
define('PLEASE_LOGIN_CAPTION', 'You need to login to see referring sites');
/**#@-*/

/**#@+
 * Language constant used by the {@link referrers_sites.php referrers_sites} (page) handler
 */
// referrers_sites
define('REFERRERS_URLS_LINK_DESC', 'see list of different URLs');
define('REFERRERS_DOMAINS_TO_WIKI', 'Domains/sites linking to this wiki (%s)'); // %s - link to referrers handler
define('REFERRERS_DOMAINS_TO_PAGE', 'Domains/sites linking to %1$s %2$s (%3$s)'); // %1$s - page link; %2$s - purge time; %3$s - link to referrers handler
/**#@-*/

/**#@+
 * Language constant used by the {@link referrers.php referrers} (page) handler
 */
// referrers
define('REFERRERS_DOMAINS_LINK_DESC', 'see list of domains');
define('REFERRERS_URLS_TO_WIKI', 'External pages linking to this wiki (%s)'); // %s - link to referrers_sites handler
define('REFERRERS_URLS_TO_PAGE', 'External pages linking to %1$s %2$s (%3$s)'); // %1$s - page link; %2$s - purge time; %3$s - link to referrers_sites handler
/**#@-*/

/**#@+
 * Language constant used by the {@link review_blacklist.php review_blacklist} (page) handler
 */
// review_blacklist
define('BLACKLIST_HEADING', 'Referrer Blacklist');
define('BLACKLIST_REMOVE_LINK_DESC', 'Remove');
define('STATUS_BLACKLIST_EMPTY', 'Blacklist is empty.');
/**#@-*/

/**#@+
 * Language constant used by the {@link revisions.php revisions} (page) handler
 */
// revisions
define('REVISIONS_CAPTION', 'Revisions for %s'); // %s pagename
define('REVISIONS_NO_REVISIONS_YET', 'There are no revisions for this page yet');
define('REVISIONS_SIMPLE_DIFF', 'Simple Diff');
define('REVISIONS_MORE_CAPTION', 'There are more revisions that were not shown here, click the button labelled %s below to view these entries'); // %S - text of REVISIONS_MORE_BUTTON
define('REVISIONS_RETURN_TO_NODE_BUTTON', 'Return To Node / Cancel');
define('REVISIONS_SHOW_DIFFERENCES_BUTTON', 'Show Differences');
define('REVISIONS_MORE_BUTTON', 'Next...');
/**#@-*/

/**#@+
 * Language constant used by the {@link revisions.xml.php revisions.xml} (page) handler
 */
// revisions.xml
define('REVISIONS_EDITED_BY', 'Edited by %s'); // %s user name
define('HISTORY_REVISIONS_OF', 'History/revisions of %s'); // %s - page name
define('EDITED_BY', 'Edited by %s');
define('ERROR_ACL_READ_INFO', 'You\'re not allowed to access this information.');
define('I18N_LANG', 'en-US');
define('I18N_ENCODING_UTF8', 'UTF-8');
define('RSS_REVISIONS_VERSION','2.0');
define('RSS_RECENTCHANGES_VERSION','0.92');
/**#@-*/

/**#@+
 * Language constant used by the {@link revisions.php revisions} (page) handler
 */
define('BUTTON_RETURN_TO_NODE', 'Return To Node / Cancel');
define('BUTTON_SHOW_DIFFERENCES', 'Show Differences');
define('SIMPLE_DIFF', 'Simple Diff');
define('WHEN_BY_WHO', '%1$s by %2$s');
define('UNREGISTERED_USER', 'unregistered user');
/**#@-*/

/**#@+
 * Language constant used by the {@link show.php show} (page) handler
 */
// show
define('SHOW_RE_EDIT_BUTTON', 'Re-edit this old revision');
define('SHOW_FORMATTED_BUTTON', 'Show formatted');
define('SHOW_SOURCE_BUTTON', 'Show source');
define('SHOW_ASK_CREATE_PAGE_CAPTION', 'This page doesn\'t exist yet. Maybe you want to %s it?'); // %s - page create link
define('SHOW_OLD_REVISION_CAPTION', 'This is an old revision of %1$s made by %2$s on %3$s.'); // %1$s - page link; %2$s - username; %3$s - timestamp; 
define('COMMENTS_CAPTION', 'Comments');
define('DISPLAY_COMMENTS_LABEL', 'Show comments');
define('DISPLAY_COMMENT_LINK_DESC', 'Display comment');
define('DISPLAY_COMMENTS_EARLIEST_LINK_DESC', 'Earliest first');
define('DISPLAY_COMMENTS_LATEST_LINK_DESC', 'Latest first');
define('DISPLAY_COMMENTS_THREADED_LINK_DESC', 'Threaded');
define('HIDE_COMMENTS_LINK_DESC', 'Hide comments');
define('STATUS_NO_COMMENTS', 'There are no comments on this page.');
define('STATUS_ONE_COMMENT', 'There is one comment on this page.');
define('STATUS_SOME_COMMENTS', 'There are %d comments on this page.'); // %d - number of comments
define('COMMENT_TIME_CAPTION', '%s'); // %s comment time
define('SHOW_OLD_REVISION_SOURCE', 0); # if set to 1 shows by default the source of an old revision instead of the rendered version
/**#@-*/

/**#@+
 * Language constant used by the {@link showcode.php showcode} (page) handler
 */
// showcode
define('SOURCE_HEADING', 'Wiki source for %s'); // %s - page link
define('SHOW_RAW_LINK_DESC', 'Show raw source');
define('RAW_LINK_DESC', 'show source only');
define('ERROR_NOT_EXISTING_PAGE', 'Sorry, this page doesn\'t exist.');
define('ERROR_NO_READ_ACCESS', 'Sorry, you aren\'t allowed to read this page.');
/**#@-*/

/* ------------------ LIBS ------------------*/

/**#@+
 * Language constant used by the {@link Wakka.class.php Wakka class} (the Wikka core containing most methods)
 */
// Wakka.class
define('QUERY_FAILED', 'Query failed:');
define('REDIR_DOCTITLE', 'Redirected to %s'); // %s - target page
define('REDIR_LINK_DESC', 'this link'); // used in REDIR_MANUAL_CAPTION
define('REDIR_MANUAL_CAPTION', 'If your browser does not redirect you, please follow %s'); // %s target page link
define('CREATE_THIS_PAGE_LINK_TITLE', 'Create this page');
define('ACTION_UNKNOWN_SPECCHARS', 'Unknown action; the action name must not contain special characters.');
define('ACTION_UNKNOWN', 'Unknown action "%s"'); // %s - action name
define('HANDLER_UNKNOWN_SPECCHARS', 'Unknown handler; the handler name must not contain special characters.');
define('HANDLER_UNKNOWN', 'Sorry, %s is an unknown handler.'); // %s handler name
define('FORMATTER_UNKNOWN_SPECCHARS', 'Unknown formatter; the formatter name must not contain special characters.');
define('FORMATTER_UNKNOWN', 'Formatter "%s" not found'); // %s formatter name
/**#@-*/

/* ------------------ SETUP ------------------ */
/**#@+
 * Language constant used by the {@link index.php setup} program (and several included files)
 */
// @@@ later....
/**#@-*/

?>
