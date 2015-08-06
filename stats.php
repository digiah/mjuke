<?php 

include("dbheader.php");

if ($dberror == 0) {
	# get the status of all items in the playlist // where `requestor` = '".$_POST["requestor"]."' and `playing` = 1
	$q = "select `id`, `divid`,`shown`, `src`,`playing`, `muted`, `incept` from `tthwQ` order by incept";
	$r = $mysqli->query($q) or die($mysqli->error);
	if ($r->num_rows > 0) {
		$items = array();
		while ($i = mysqli_fetch_array($r)) {
			$items[] = $i;
		}
	}
	
	$q = "select * from `tthwCMD` order by incept";
	$r = $mysqli->query($q) or die($mysqli->error);
	if ($r->num_rows > 0) {
		$cmds = array();
		while ($c = mysqli_fetch_array($r)) {
			$cmds[] = $c;
		}
	}
	
}
else {
	echo "Could not connect to database.";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>DB status</title>
<style>
.column {
	width: 250px;
	border-right: 1px solid #333;
	border-bottom: 1px solid #333;
	padding: 10px;
	float: left;
}
.small {
	width: 80px;	
	text-align: center;
}
</style>
<script>
window.onload = function() { setTimeout(function() { location.reload(); },1500); }
</script>
</head>

<body>
<div style="min-width: 750px;margin: auto;">
<h1>Items</h1>
<div class="column small">ID</div>
<div class="column">TITLE</div>
<div class="column small">DIVID</div>
<div class="column small">SHOWN</div>
<div class="column small">PLAYING</div>
<div class="column small">MUTED</div>
<div class="column small">INCEPT</div>
<div style="clear: both;"></div>
<div style="clear: both;"></div>
<?php if (count($items)): ?>
<?php for($i=0;$i<count($items);$i++): ?>
<div class="column small"><?php echo $items[$i]["id"]; ?></div>
<div class="column"><?php echo $items[$i]["src"]; ?></div>
<div class="column small"><?php echo $items[$i]["divid"]; ?></div>
<div class="column small"><?php echo $items[$i]["shown"]; ?></div>
<div class="column small"><?php echo $items[$i]["playing"]; ?></div>
<div class="column small"><?php echo $items[$i]["muted"]; ?></div>
<div class="column small"><?php echo gmdate("H:i:s", $items[$i]["incept"]); ?></div>
<div style="clear: both;"></div>
<?php endfor; ?>

<?php endif; ?>

<h1>Commands</h1>
<div class="column small">ID</div>
<div class="column small">DIVID</div>
<div class="column small">CMD</div>
<div class="column small">ITEM</div>
<div class="column small">INCEPT</div>
<div style="clear: both;"></div>
<div style="clear: both;"></div>
<?php if (count($cmds)): ?>
<?php for($i=0;$i<count($cmds);$i++): ?>
<div class="column small"><?php echo $cmds[$i]["id"]; ?></div>
<div class="column small"><?php echo $cmds[$i]["divid"]; ?></div>
<div class="column small"><?php echo $cmds[$i]["cmd"]; ?></div>
<div class="column small"><?php echo $cmds[$i]["itemid"]; ?></div>
<div class="column small"><?php echo gmdate("H:i:s", $cmds[$i]["incept"]); ?></div>
<div style="clear: both;"></div>
<?php endfor; ?>

<?php endif; ?>

</div>
</body>
</html>