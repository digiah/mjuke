<?php
include("dbheader.php");

if ($dberror == 0) {
	
	if ($_POST["eject"] == 1) {
		# delete the item from the playlist
		$q = "delete from `tthwQ` where `id` = ".$_POST["id"];
	}
	else {
		# set the playback item to "shown"
		$q = "update `tthwQ` set `shown` = 1, `playing` = 0 where `id` = ".$_POST["id"];
	}
	$r = $mysqli->query($q);
	
	# delete any commands related to this item
	$q = "delete from `tthwCMD` where `itemid` = ".$_POST["id"];
	$r = $mysqli->query($q);
	
	if (strlen($mysqli->error)) {
		#$status = array("error" => 1, "text" => "THEFUCK? ".$q." ".$mysqli->error);
		$status = array("error" => 1, "text" => "fuck you from kq.php");
	}
	else {
		$status = array("error" => 0);
	}
	
}
else {
	$status = array("error" => "1", "text" => "Could not connect to database." );
}

echo json_encode($status);
