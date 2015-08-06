<?php
include("dbheader.php");
if ($dberror == 0) {
	// look for items that are cued
	$q = "select * from `tthwQ` where `playing` = -1 and `shown` = 0 and `type` = '".$_POST["type"]."' order by `incept` asc limit 1";
	
	$r = $mysqli->query($q) or die($mysqli->error);
	
	if ($r->num_rows > 0) {
		$item = mysqli_fetch_array($r);
		$status = array("error" => "0", "type" => $item["type"], "source" => $item["src"], "id" => $item["id"], "theoptions" => $item["options"]);
		$q = "update `tthwQ` set `playing` = '1' where `id` = ".$item["id"];
		$r = $mysqli->query($q) or die($mysqli->error);
	}
	else {
		$status = array("error" => "0", "source" => "null", "type" => $item["type"]);
	}
	
}
else {
	$status = array("error" => "1", "text" => "Could not connect to database." );
}

echo json_encode($status);
