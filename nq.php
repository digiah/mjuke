<?php
	# connect to database
	include("dbheader.php");
	
	if ($dberror == 0) {
		# insert into database
		$status = array("error" => "0", "text" => "Sent ".$_POST["source"]." to queue." );
		list($name,$type) = explode(".",$_POST["source"]);
		$incept = time();
		$requestor = $_POST["requestor"];
		$shown = 0;
		$playing = -1;
		if ($_POST["theoption"] != "") {
			$options = json_encode(array("theoption" => $_POST["theoption"], "thevalue" => $_POST["thevalue"]));	
		}
		else {
			$options = "";
		}
		
		$q = "INSERT INTO `tthwQ` (`divid`, `type`, `src`, `requestor`, `shown`, `playing`, `options`, `incept`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		
		$stmt = $mysqli->prepare($q) or die($q." ".mysqli_error($mysqli));
		
		if (!$stmt->bind_param('dsssddsd',$_POST["divid"], $type,$_POST["source"],$requestor,$shown,$playing,$options,$incept)) {
			$status = array("error" => "1", "text" => "Binding failed." );
		}
		else {
			if ($stmt->execute()) {
				$status = array("error" => "0", "text" => "Queued", "type" => $type, "itemid" => $mysqli->insert_id, "thediv" => $_POST["divid"] );
			}
			else {
				$status = array("error" => "1", "text" => $mysqli->error );
			}
		}
		# get confirmation
	}
	else {
		$status = array("error" => "1", "text" => "Could not connect to database." );
	}	
	echo (json_encode($status));
?>