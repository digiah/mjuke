<?php

include("dbheader.php");

if ($dberror == 0) {
	$status = array("error" => "0", "text" => "Click the button corresponding to the resource you want to show next." );
}
else {
	$status = array("error" => "1", "text" => "Could not connect to database." );
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<title>NQ</title>

<link href="reset.css" rel="stylesheet" type="text/css" />
<style>

.column {
	width: auto;
	text-align: left;
	padding-left: 10px;
	padding-right: 10px;
	float: left;
	font-family: Helvetica,Arial,sans-serif;
}

.graybg {
	background-color:#AAD4E8;	
}
.redbg {
	background-color:#F00;
}
.greenbg {
	background-color:#6C9;
}

.leftcolumn {
	border-radius: 5px 0px 0px 5px;
	-moz-border-radius: 5px 0px 0px 5px;
	-webkit-border-radius: 5px 0px 0px 5px;
	border: 0px solid #000000;
	text-align: center;
}

.midcolumn {
	text-align: center;
	border-right: 1px solid #333;
}

.rightcolumn {
	border-radius: 0px 5px 5px 0px;
	-moz-border-radius: 0px 5px 5px 0px;
	-webkit-border-radius: 0px 5px 5px 0px;
	border: 0px solid #000000;
}

.title {
	text-align: center;
	width: auto;
	margin-bottom: 10px;
	margin-bottom: 20px;
	margin-top: 15px;
	display: block;	
	font-weight: bold;
	text-transform: uppercase;
}

button {
	height: 31px;
	width: auto;
	border: 0px solid #000;
	margin-left: 3px;
	margin-right: 3px;
	margin-bottom: 10px;
	vertical-align: middle;
	border-radius: 5px 5px 5px 5px;
	-moz-border-radius: 5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
	display: block;
	cursor: pointer;
	background-color:#4FD355;
	float: right;
	font-size: 18px;
	padding-left: 5px;
	padding-right: 5px;
	
	background: rgba(183,222,237,1);
background: -moz-linear-gradient(top, rgba(183,222,237,1) 0%, rgba(113,206,239,1) 43%, rgba(33,180,226,1) 86%, rgba(183,222,237,1) 100%);
background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(183,222,237,1)), color-stop(43%, rgba(113,206,239,1)), color-stop(86%, rgba(33,180,226,1)), color-stop(100%, rgba(183,222,237,1)));
background: -webkit-linear-gradient(top, rgba(183,222,237,1) 0%, rgba(113,206,239,1) 43%, rgba(33,180,226,1) 86%, rgba(183,222,237,1) 100%);
background: -o-linear-gradient(top, rgba(183,222,237,1) 0%, rgba(113,206,239,1) 43%, rgba(33,180,226,1) 86%, rgba(183,222,237,1) 100%);
background: -ms-linear-gradient(top, rgba(183,222,237,1) 0%, rgba(113,206,239,1) 43%, rgba(33,180,226,1) 86%, rgba(183,222,237,1) 100%);
background: linear-gradient(to bottom, rgba(183,222,237,1) 0%, rgba(113,206,239,1) 43%, rgba(33,180,226,1) 86%, rgba(183,222,237,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b7deed', endColorstr='#b7deed', GradientType=0 );

}


input[type="button"]:disabled {
	background-color:#999;
}

.info {
	line-height: 31px;
	height: 31px;
	margin-bottom: 10px;
	width: auto;
	vertical-align: middle;
	font-size: 18px;
	text-transform: capitalize;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<script>

var controls = { 
	play: "<i class='fa fa-play'></i>",
	pause: "<i class='fa fa-pause'></i>",
	eject: "<i class='fa fa-eject'></i>",
	mute: "<i class='fa fa-volume-off'></i>",
	volume: "<i class='fa fa-volume-up'></i>"
}

var timer = null;

$(document).ready(function(e) {
	timer = setInterval(getStatus,1500);
	//getStatus();
});


function getStatus() {
	var jqxhr = $.ajax({
		method: "POST",
		url: "http://dahi.manoa.hawaii.edu/three/status.php",
		data: { requestor: "dmg" }
		})
		.done(function(responseText) {
			r = JSON.parse(responseText);
			if (r.error == 0) {
				if (r.playlist != "null") {
					if (r.items.length > 0) {
						for (i=0;i<r.items.length;i++) {
							
							// get rid of all control buttons
							$(".ctlbutton"+r.items[i].divid).remove();
							
							var thabuttons = "";
							
							if ((r.items[i].shown == "1") && (r.items[i].playing == "0")) {
								// item has been played
								$("#status"+r.items[i].divid).html("Shown");
								
								// add and options enabled
								$("#action"+r.items[i].divid).attr("disabled", false);
								$("#option"+r.items[i].divid).removeAttr("disabled");
								
								// remove from tthwQ
								$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/kq.php", data: { id: r.items[i].id, eject: "1" }}).done();
							}
							else {
								
								thabuttons = "<button class='ctlbutton"+r.items[i].divid+"' id='eject"+r.items[i].divid+"' onclick=\"ctl("+r.items[i].divid+", "+r.items[i].id+", 'eject');\">"+controls.eject+"</button>";
								
							}
							
							if (r.items[i].playing == "-1") {
								// item is cued
								$("#status"+r.items[i].divid).html("Queued");
								
								// add and options disabled
								$("#action"+r.items[i].divid).attr("disabled", true);
								$("#option"+r.items[i].divid).attr("disabled", true);
								
								
							}
							if (r.items[i].playing == "1") {
					
								// item is playing
								$("#status"+r.items[i].divid).html("PLAYING <i class='fa fa-spinner fa-spin'></i>");
								
								// add and options disabled
								$("#action"+r.items[i].divid).attr("disabled", true);
								$("#option"+r.items[i].divid).attr("disabled", true);
								
								// play pause in PAUSE mode
								thabuttons += "<button class='ctlbutton"+r.items[i].divid+"' id='playpause"+r.items[i].divid+"' onclick=\"ctl("+r.items[i].divid+", "+r.items[i].id+", 'pause');\">"+controls.pause+"</button>";
								
								
							}
							
							if ((r.items[i].shown == "0") && (r.items[i].playing == "0")) {
								// item is paused
								$("#status"+r.items[i].divid).html("PAUSED <i class='fa fa-spinner'></i>");
								
								// add button disabled
								$("#action"+r.items[i].divid).attr("disabled", true);
								$("#option"+r.items[i].divid).attr("disabled", true);
								
								// play pause in PLAY mode
								thabuttons += "<button class='ctlbutton"+r.items[i].divid+"' id='playpause"+r.items[i].divid+"' onclick=\"ctl("+r.items[i].divid+", "+r.items[i].id+", 'play');\">"+controls.play+"</button>";
								
							}
							
							if ((r.items[i].muted == "1") && (r.items[i].shown == "0") && (r.items[i].type != "jpg")) {
								thabuttons += "<button class='ctlbutton"+r.items[i].divid+"' id='mutevolume"+r.items[i].divid+"' onclick=\"ctl("+r.items[i].divid+", "+r.items[i].id+", 'volume');\">"+controls.volume+"</button>";
							}
							
							if ((r.items[i].muted == "0") && (r.items[i].shown == "0") && (r.items[i].type != "jpg")) {
								thabuttons += "<button class='ctlbutton"+r.items[i].divid+"' id='mutevolume"+r.items[i].divid+"' onclick=\"ctl("+r.items[i].divid+", "+r.items[i].id+", 'mute');\">"+controls.mute+"</button>";
							}
							
							$("#actions"+r.items[i].divid).append(thabuttons);
							
							
						}
					}
				}
			}
			else {
				alert(r.text);
			}
		})
		.fail(function() {
			alert( "error: couldn't connect to script...STATUS" );
		})
		.always(function() {
			
		});
}

function nq(src, div) {
	
	itemdata = { source: src, requestor: "<?php echo $_GET["u"]; ?>", divid: div, theoption: "", thevalue: "" }
	
	if ($("#option"+div).is(':checked')) {
		if ($("#filename"+div).data("type") == "mp4") {
			itemdata.theoption = "mute";
			itemdata.thevalue = "1";
		}
		if ($("#filename"+div).data("type") == "jpg") {
			itemdata.theoption = "duration";
			itemdata.thevalue = $("#duration"+div).val();
		}
	}
	
	var jqxhr = $.ajax({
		method: "POST",
		url: "http://dahi.manoa.hawaii.edu/three/nq.php",
		data: itemdata
		})
		.done(function(responseText) {
			r = JSON.parse(responseText);
			
			if (r.error == 0) {
				$("#status"+r.thediv).html("<span>"+r.text+"</span>");
				$("#option"+r.thediv).attr("disabled", true);
				$("#action"+r.thediv).attr("disabled", true);
				$("#action"+r.thediv).blur();
				
				if ($("#eject"+r.thediv).length == 0) {
					$("#actions"+r.thediv).append("<button class='ctlbutton"+r.thediv+"' id='eject"+r.thediv+"' onclick=\"ctl("+r.thediv+", "+r.itemid+", 'eject');\">"+controls.eject+"</button>");
				}
			}
			else {
				alert(r.text);
			}
		})
		.fail(function() {
			alert( "error: couldn't connect to script... NQ" );
		})
		.always(function() {
			
		});
}

function ctl(div, id, cmd) {
	var ctlqxhr = $.ajax({
		method: "POST",
		url: "http://dahi.manoa.hawaii.edu/three/cmd.php",
		data: { command: cmd, itemid: id, divid: div, mode: "add" }
		})
		.done(function(responseText) {
			r = JSON.parse(responseText);
			if (r.error == 0) {
				if (cmd == "eject") {
					//$("#"+cmd+div).attr("disabled", true);
					$(".ctlbutton"+div).remove();
					$("#action"+div).removeAttr("disabled");
					$("#option"+div).removeAttr("disabled");
					$("#status"+div).html("");
					clearInterval(timer);
					timer = setInterval(getStatus,2500);
				}
				/*
				if (cmd == "pause") {
					$("#playpause"+div).attr("onclick", "ctl("+div+", "+id+", 'play');");
					$("#playpause"+div).html(controls.play);
					//$("#"+cmd+div).remove();
					//$("#actions"+div).append("<button class='ctlbutton"+div+"' id='play"+div+"' onclick=\"ctl("+div+", "+id+", 'play');\">"+controls.play+"</button>");
				}
				if (cmd == "play") {
					$("#playpause"+div).attr("onclick", "ctl("+div+", "+id+", 'pause');");
					$("#plaupause"+div).html(controls.play);
					//$("#"+cmd+div).remove();
					//$("#actions"+div).append("<button class='ctlbutton"+div+"' id='pause"+div+"' onclick=\"ctl("+div+", "+id+", 'pause');\">"+controls.pause+"</button>");
				}
				*/
			}
			else {
				alert(r.text);
			}
		})
		.fail(function() {
			alert( "error: couldn't connect to script...CMD" );
		})
		.always(function() {
			
		});
	
	return false;	
}
</script>
</head>

<body>

<div style="width: 1010px; height: 660px; margin: auto; margin-top: 20px;">
<?php
$dir    = '.';
$files1 = scandir($dir);
$data = array();

foreach ($files1 as $file) {
	list($name,$type) = explode(".",$file);
	list($owner,$title) = explode("_",$name);
	
	if ((($type == "mp3") || ($type == "mp4") || ($type == "jpg")) && ($title != "")) {
		$data[] = array(
			"title" => str_replace("-"," ",$title),
			"src" => $file,
			"type" => $type,
			"owner" => $owner
		);
	}
}


?>

<div class="column leftcolumn graybg" >
<div class="title">Owner</div>
<?php for ($i=0;$i<count($data);$i++): ?>
<div class="info" style="text-transform: uppercase;"><?php echo $data[$i]["owner"]; ?></div>
<?php endfor; ?>
</div>

<div class="column midcolumn graybg" style="text-align: right;">
<div class="title">Resource</div>
<?php for ($i=0;$i<count($data);$i++): ?>
<div class="info" <?php echo (($i%2) == 0)?"style=\"font-weight: bold;\"":""; ?> id="filename<?php echo $i; ?>" data-type="<?php echo $data[$i]["type"]; ?>"><?php echo $data[$i]["title"]; ?></div>
<?php endfor; ?>

</div>

<div class="column midcolumn graybg">
<div class="title">Type</div>
<?php for ($i=0;$i<count($data);$i++): 

switch ($data[$i]["type"]) {
	case "mp3":
	$icon = "volume-up";
	break;
	
	case "mp4":
	$icon = "film";
	break;
	
	case "jpg":
	$icon = "image";
	break;	
}
?>
<div class="info"><i class="fa fa-<?php echo $icon; ?>"></i></div>
<?php endfor; ?>
</div>

<div class="column midcolumn graybg">
<div class="title">Action</div>
<?php for ($i=0;$i<count($data);$i++): ?>
<div id="actions<?php echo $i; ?>" style="text-align:center;">
<button id="action<?php echo $i; ?>" onclick="nq('<?php echo $data[$i]["src"]; ?>', <?php echo $i; ?>)">ADD</button>
</div>
<div style="clear: both;"></div>
<?php endfor; ?>

</div>

<div class="column midcolumn graybg">
<div class="title">Options</div>
<?php for ($i=0;$i<count($data);$i++): 

?>
<?php if ($data[$i]["type"] == "mp4"): 
/* offer mute option */
?>
<div class="info">&nbsp;</div>

<?php elseif ($data[$i]["type"] == "jpg"): 
/* offer duration option */
?>
<div class="info"><input type="checkbox" value="1" id="option<?php echo $i; ?>" name="option<?php echo $i; ?>" checked="checked" /> <input type="text" value="30" id="duration<?php echo $i; ?>" name="duration<?php echo $i; ?>" style="width: 20px;"  /> s.</div>

<?php else: ?>

<div class="info">&nbsp;<input type="hidden" value="" id="option<?php echo $i; ?>" name="option<?php echo $i; ?>" /></div>

<?php endif; ?>

<?php endfor; ?>
</div>

<div class="column rightcolumn graybg">
<div class="title">Status</div>
<?php for ($i=0;$i<count($data);$i++): ?>
<div class="info" id="status<?php echo $i; ?>">&nbsp;</div>
<?php endfor; ?>
</div>

<div style="clear:both;"></div>
<button style="float: none;margin-top:20px;margin-bottom: 20px;" onclick="document.location.href='clr.php?u=<?php echo $_GET["u"]; ?>';">Clear Queue</button>

<?php if ($_GET["u"] == "dmg"): ?>
<iframe style="width: 100%; height: 720px;" src="stats.php" frameborder="0" scrolling="no" name="stats"></iframe>
<?php endif; ?>
</div>

</body>
</html>