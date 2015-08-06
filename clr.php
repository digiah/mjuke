<?php

include("dbheader.php");

if ($dberror == 0) {
	$r = $mysqli->query("truncate table `tthwQ`") or die($mysqli->error);
	$r = $mysqli->query("truncate table `tthwCMD`") or die($mysqli->error);
	header("location:q.php?u=".$_GET["u"]);
}
else {
	$status = array("error" => "1", "text" => "Could not connect to database." );
}

?>