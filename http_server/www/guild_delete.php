<?php

require_once( '../fns/all_fns.php' );

try {

	//--- import data
	$guild_id = find( 'guild_id' );


	//--- connect to the db
	$db = new DB();


	//--- check their login and make some rad variables
	$mod = check_moderator($db);
	$mod_name = $mod->name;
	$mod_id = $mod->user_id;
	$ip = $mod->ip;
	
	//--- check if the guild exists and make some rad variables
	$guild = $db->grab_row( 'guild_select', array($guild_id), 'Could not find a guild with that id.' );
	$guild_name = $guild->guild_name;
	$guild_note = $guild->note;
	$guild_owner = $guild->owner_id;

	//--- edit guild in db
	$db->call( 'guild_delete', array($guild_id), 'Could not delete the guild.' );
	
	//record the deletion in the action log
	$db->call('mod_action_insert', array($mod_id, "$mod_name deleted guild $guild_id from $ip {guild_name: $guild_name, guild_prose: $guild_note, owner_id: $guild_owner}", $mod_id, $ip));


	//--- tell it to the world
	$reply = new stdClass();
	$reply->success = true;
	$reply->message = 'Guild deleted.';
	echo json_encode( $reply );
}


catch(Exception $e){
	$reply = new stdClass();
	$reply->error = $e->getMessage();
	echo json_encode( $reply );
}

?>
