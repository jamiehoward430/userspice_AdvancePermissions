<?php
require_once("init.php");
//For security purposes, it is MANDATORY that this page be wrapped in the following
//if statement. This prevents remote execution of this code.
if (in_array($user->data()->id, $master_account)){


$db = DB::getInstance();
include "plugin_info.php";

//all actions should be performed here.
$check = $db->query("SELECT * FROM us_plugins WHERE plugin = ?",array($plugin_name))->count();
if($check > 0){
	err($plugin_name.' has already been installed!');
}else{
 $fields = array(
	 'plugin'=>$plugin_name,
	 'status'=>'installed',
 );
 $db->insert('us_plugins',$fields);
 if(!$db->error()) {
	 	err($plugin_name.' installed');
		logger($user->data()->id,"USPlugins",$plugin_name." installed");
 } else {
	 	err($plugin_name.' was not installed');
		logger($user->data()->id,"USPlugins","Failed to to install plugin, Error: ".$db->errorString());
 }
}


$db->query("CREATE TABLE `plg_advanced_perm` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `description` varchar(255),
  `group_id` int(11),
  `type` int(11) DEFAULT 0

) ENGINE=InnoDB DEFAULT CHARSET=latin1");

$db->query("CREATE TABLE `plg_advanced_perm_groups` (
	`id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`name` varchar(255)
  
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1");

$db->query("CREATE TABLE `plg_advanced_perm_matches` (
	`id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`userid` int(11) DEFAULT -1,
	`user_group_id` int(11) DEFAULT 0,
	`perm_id` int(11),
	`can_view` int(11) DEFAULT 0,
	`can_edit` int(11) DEFAULT 0,
	`can_delete` int(11) DEFAULT 0,
	`general` int(11) DEFAULT 0
  
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1");

$db->query("ALTER TABLE users ADD COLUMN plg_advperm_group int(11) default 1");
$db->query("ALTER TABLE settings ADD COLUMN plg_advperm_groups int(11) default 0");

$pcheck = $db->query("SELECT * FROM plg_advanced_perm_groups WHERE name = ?",array('Plugin Test Permissions'))->count();
if($pcheck == 0){

$db->insert('plg_advanced_perm_groups',array('name'=>'Plugin Test Permissions'));
$db->insert('plg_advanced_perm',array('name'=>'Test Label','description'=>'View Test Label on plugin configuration page','group_id'=>1));
$db->insert('plg_advanced_perm',array('name'=>'Test Input','description'=>'View\Edit Test Input on plugin configuration page','group_id'=>1));
$db->insert('plg_advanced_perm',array('name'=>'Test FE','description'=>'View\Edit Test FE on plugin configuration page','group_id'=>1));
$db->insert('plg_advanced_perm',array('name'=>'Test Section','description'=>'View Test Section on plugin configuration page','group_id'=>1));
$db->insert('plg_advanced_perm',array('name'=>'Test Perm','description'=>'Test Permission for the example text on the plugin configuration page, must have view permission on Test Section','group_id'=>1));
//do you want to inject your plugin in the middle of core UserSpice pages?
}
} //do not perform actions outside of this statement
