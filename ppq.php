<?php

# play-pause queue item

include("dbheader.php");

if ($dberror == 0) {
	
	if ($_POST["mode"] == "play") {
		$q = "update `tthwQ` set `playing` = 1 where `id` = ".$_POST["id"];
		$r = $mysqli->query($q);
	}
	
	if ($_POST["mode"] == "pause") {
		$q = "update `tthwQ` set `playing` = 0 where `id` = ".$_POST["id"];
		$r = $mysqli->query($q);
	}
	
	if ($_POST["mode"] == "mute") {
		$q = "update `tthwQ` set `muted` = 1 where `id` = ".$_POST["id"];
		$r = $mysqli->query($q);
	}
	
	if ($_POST["mode"] == "volume") {
		$q = "update `tthwQ` set `muted` = 0 where `id` = ".$_POST["id"];
		$r = $mysqli->query($q);
	}
	
	# delete any commands related to this item
	$q = "delete from `tthwCMD` where `itemid` = ".$_POST["id"];
	$r = $mysqli->query($q);
	
	if (strlen($mysqli->error)) {
		#$status = array("error" => 1, "text" => "THEFUCK? ".$q." ".$mysqli->error);
		$status = array("error" => 1, "text" => "fuck you from ppq.php");
	}
	else {
		$status = array("error" => 0);
	}
	
}
else {
	$status = array("error" => "1", "text" => "Could not connect to database." );
}

echo json_encode($status);
