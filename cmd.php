<?php
	# connect to database
	include("dbheader.php");
	
	if ($dberror == 0) {
		
		if ($_POST["mode"] == "add") {
			
			$status = array("error" => "0", "text" => "Sent ".$_POST["command"]." to queue for ".$_POST["itemid"] );
			
			# if command is EJECT and target item is QUEUED, then delete it from the Q!
			if ($_POST["command"] == "eject") {
				$status = array("error" => "0", "text" => "Ejected Queued item", "thediv" => $_POST["divid"], "id" => $_POST["itemid"] );
				$q = "select EXISTS (select 1 from `tthwQ` where `id` = ".$_POST["itemid"]." and `playing` = -1) as solo";
				$r = $mysqli->query($q) or die($q." love, cmd.php Queue - exists");
				$solo = mysqli_fetch_array($r);
			}
			if ($solo[0] == 1) {
					$q = "DELETE from `tthwQ` where `id` = ".$_POST["itemid"];
					$mysqli->query($q) or die($q." love, cmd.php Queue - delete");
			}
			else {
				# otherwise insert command into database
				$q = "INSERT INTO `tthwCMD` (`divid`, `cmd`, `itemid`, `incept`) VALUES (?, ?, ?, ?)";
				
				$stmt = $mysqli->prepare($q) or die($q." ".mysqli_error($mysqli));
				
				if (!$stmt->bind_param('dsdd',$_POST["divid"], $_POST["command"], $_POST["itemid"], time())) {
					$status = array("error" => "1", "text" => "Binding failed." );
				}
				else {
					if ($stmt->execute()) {
						$status = array("error" => "0", "text" => "Ejecting...", "thediv" => $_POST["divid"], "id" => $mysqli->insert_id );
					}
					else {
						$status = array("error" => "1", "text" => $q." ".implode(" ",$_POST)." ".$mysqli->error );
					}
				}
			}
				
		}
		else if ($_POST["mode"] == "remove") {
			# removing from database
			$status = array("error" => "0", "text" => "Completed ".$_POST["command"]." for ".$_POST["itemid"] );
			$q = "DELETE from `tthwCMD` where `itemid` = ".$_POST["itemid"];
			#$r = $mysqli->query($q) or die("REMOVAL ".$q."\n".implode(" * ",$_POST)."\n".$mysqli->error);
			$r = $mysqli->query($q) or die("fuck you from cmd.php");
			$r = $mysqli->query("update `tthwQ` set `shown` = 1 where `id` = ".$_POST["itemid"]) or die("died trying to reset item status");
		}
		else if (!isset($_POST["mode"])) {
			if (isset($_GET["list"])) {
				$status = array("error" => "0", "commandlist" => array());
				# returning list of commands
				$q = "select * from `tthwCMD` order by `incept`";
				$r = $mysqli->query($q) or die($mysqli->error);
				while ($item = mysqli_fetch_array($r)) {
					$status["commandlist"][] = array("itemid" => $item["itemid"], "cmd" => $item["cmd"]);	
				}
			}
		}
		
	}
	else {
		$status = array("error" => "1", "text" => "Could not connect to database." );
	}	
	echo (json_encode($status));
?>