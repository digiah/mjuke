<?php 

include("dbheader.php");

if ($dberror == 0) {
	# get the status of all items in the playlist // where `requestor` = '".$_POST["requestor"]."' and `playing` = 1
	$q = "select `id`, `divid`,`shown`,`playing`, `type`, `muted` from `tthwQ` order by incept";
	$r = $mysqli->query($q) or die($mysqli->error);
	if ($r->num_rows > 0) {
		$items = array();
		while ($i = mysqli_fetch_array($r)) {
			$items[] = $i;
		}
		$status = array("error" => "0", "items" => $items);
	}
	else {
		$status = array("error" => "0", "playlist" => "null");
	}
	
}
else {
	$status = array("error" => "1", "text" => "Could not connect to database." );
}

echo json_encode($status);

?>