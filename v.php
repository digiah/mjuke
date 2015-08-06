<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>3HW Video</title>

<style>
body {
	background-color:#000;
	background-image:url(starfield.jpg);
	background-size: 100% auto;
	background-repeat:repeat;
}

#gofull {
	border: 0px;
	padding: 0px;
	max-width: 1824px;
	max-height: 1026px;
	width: 100%;
	height: 100%;
	margin: auto;
	text-align: center;
	display: block;
}

#ac {
	width: 100%;
	height: 40px;
	position: absolute;
	top: 0px;	
	left: 0px;
	background-color: none;
	z-index: 9999;
}

#theAudio {
	width: 100%;
	/*
	height: 50px;
	position: absolute;
	top: 0px;	
	left: 0px;
	background-color: #000;
	z-index: 9999;
	display: none;
	*/
}

#theVideo {
	width: 100%;
	height: auto;
	margin: auto;
	display: none;	
}

#theImage {
	width:100%;
	height: auto;
	margin: auto;
	display: none;
}
</style>

<link href="reset.css" rel="stylesheet" type="text/css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
// full-screen available?

document.hardway = {}

// all ajax data
document.hardway.allFetch = {
	method: "POST",
	url: "http://dahi.manoa.hawaii.edu/three/gq.php",
	data: { type: "all" }
}

// Audio ajax data
document.hardway.myAudioFetch = { 
	method: "POST",
	url: "http://dahi.manoa.hawaii.edu/three/gq.php",
	data: { type: "mp3" }
	}
	
document.hardway.myAudioKill = {
	method: "POST",
	url: "http://dahi.manoa.hawaii.edu/three/kq.php",
	data: { id: -1, eject: 0 }
}

document.hardway.myAudio = {
	source: "",
	showing: 0,
	id: -1
}

// video ajax data
document.hardway.myVideoFetch = {
	method: "POST",
	url: "http://dahi.manoa.hawaii.edu/three/gq.php",
	data: { type: "mp4" }
}
	
document.hardway.myVideoKill = {
	method: "POST",
	url: "http://dahi.manoa.hawaii.edu/three/kq.php",
	data: { id: -1, eject: 0 }
}

document.hardway.myVideo = {
	source: "",
	showing: 0,
	id: -1
}

// image ajax data
document.hardway.myImageFetch = {
	method: "POST",
	url: "http://dahi.manoa.hawaii.edu/three/gq.php",
	data: { type: "jpg" }
}

document.hardway.myImageKill = {
	method: "POST",
	url: "http://dahi.manoa.hawaii.edu/three/kq.php",
	data: { id: -1, eject: 0 }
}

document.hardway.myImage = {
	source: "",
	showing: 0,
	id: -1	
}

// tracks how long image stays on screen
document.hardway.imageTimer = null;
document.hardway.imageDuration = 15;
document.hardway.imageElapsedTime = 0;
			
document.hardway.calls = 0;

$(document).ready(function(e) {
	
	$("#gofull").css({ width: $(window).width(), height: $(window).height() });
	
	$( window ).resize(function() {
		$("#gofull").css({ width: $(window).width(), height: $(window).height() });
		var diff = $("#gofull").height() - $("#theVideo").height();
		$("#theVideo").css("margin-top",diff/2);
	});

	setInterval(allGet,1500);
	//setInterval(cmdListen,2500);
	
});

function allGet() {
	//console.log("Audio status: "+document.hardway.myAudio.showing+"\nVideo status:"+document.hardway.myVideo.showing+"\nImage status:"+document.hardway.myImage.showing+"\n");
	cmdListen();
}

function cmdListen() {
	// console.log("Get list of commands.");
	$.ajax({url: "http://dahi.manoa.hawaii.edu/three/cmd.php?list"})
 	.done(function( responseText ) {
		r = JSON.parse(responseText);
		if (r.error == 0) {
			//console.log("If any command matches any of the current media, execute the command.");
			for (i=0;i<r.commandlist.length;i++) {
				if ( (r.commandlist[i].itemid == document.hardway.myAudio.id) && ($("#theAudio").length > 0) ) {
					if (r.commandlist[i].cmd == "eject") {
						// console.log("Remove the audio element.");
						document.hardway.myAudioKill.data.eject = 1;
						document.hardway.myAudioKill.data.id = document.hardway.myAudio.id;
						audioEnded();
					}
					if (r.commandlist[i].cmd == "pause") {   
						// console.log("Pause the audio element.");
						document.getElementById("theAudio").pause();  
						// console.log("Update the tthwCMD table.");
						$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/ppq.php", data: { id: document.hardway.myAudioKill.data.id, mode: "pause" }}).done();
					}
					if (r.commandlist[i].cmd == "play") {  
						// console.log("Play the audio element.");
						document.getElementById("theAudio").play(); 
						// console.log("Update the tthwCMD table.");
						$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/ppq.php", data: { id: document.hardway.myAudioKill.data.id, mode: "play" }}).done();
					}
					if (r.commandlist[i].cmd == "mute") {  
						// console.log("mute the audio element.");
						document.getElementById("theAudio").muted = true;
						$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/ppq.php", data: { id: document.hardway.myAudioKill.data.id, mode: "mute" }}).done();
					}
					if (r.commandlist[i].cmd == "volume") {  
						// console.log("unmute the audio element.");
						document.getElementById("theAudio").muted = false;
						$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/ppq.php", data: { id: document.hardway.myAudioKill.data.id, mode: "volume" }}).done();
					}
				}
				
				if ( (r.commandlist[i].itemid == document.hardway.myVideo.id) && ($("#theVideo").length > 0) ) {
					if (r.commandlist[i].cmd == "eject") {
						// console.log("Remove the video element.");
						document.hardway.myVideoKill.data.eject = 1;
						document.hardway.myVideoKill.data.id = document.hardway.myVideo.id;
						videoEnded();
					}
					if (r.commandlist[i].cmd == "pause") {   
						// console.log("Pause the video element.");
						document.getElementById("theVideo").pause(); 
						// console.log("Update the tthwCMD table.");
						$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/ppq.php", data: { id: document.hardway.myVideoKill.data.id, mode: "pause" }}).done();
					}
					
					if (r.commandlist[i].cmd == "play") {  
						// console.log("Play the video element.");
						document.getElementById("theVideo").play();  
						// console.log("Update the tthwCMD table.");
						$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/ppq.php", data: { id: document.hardway.myVideoKill.data.id, mode: "play" }}).done();
					}
					if (r.commandlist[i].cmd == "mute") {  
						// console.log("mute the video element.");
						document.getElementById("theVideo").muted = true;
						$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/ppq.php", data: { id: document.hardway.myVideoKill.data.id, mode: "mute" }}).done();
					}
					if (r.commandlist[i].cmd == "volume") {  
						// console.log("unmute the video element.");
						document.getElementById("theVideo").muted = false;
						$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/ppq.php", data: { id: document.hardway.myVideoKill.data.id, mode: "volume" }}).done();
					}
				}
				
				if ( (r.commandlist.itemid == document.hardway.myImage.id) && ($("#theImage").length > 0) ) {
					if (r.commandlist[i].cmd == "eject") {
						// console.log("Remove the image element.");
						// console.log("Update the tthwCMD table.");
						document.hardway.myImageKill.data.eject = 1;
						document.hardway.myImageKill.data.id = document.hardway.myImage.id;
						imageEnded();
					}
					if (r.commandlist[i].cmd == "pause") {   document.hardway.imageTimer = null; document.hardway.imagePausedTime = new Date(); }
					if (r.commandlist[i].cmd == "play") {  document.hardway.imageTimer = setTimeout(imageEnded,imagePausedTime.getTime() - imageStartTime.getTime());  }
				}
				
				// console.log("Updating all A/V elements");
			}
			playback();
		}
		else {
			alert(r.text);
		}
	 });
	 
}

function playback() {
	
	if (document.hardway.myAudio.showing == 0) {
		// get the playlist...
		var audiojqxhr = $.ajax( document.hardway.myAudioFetch )
		.done(function(responseText) {
			r = JSON.parse(responseText);
			if (r.error == 0) {
				if (r.source == "null") {
					// this is the last audio item currently in the playlist
					$("#theAudio").fadeOut(250, function() { $("#theAudio").remove(); });
				}
				else {
					// create and play the audio element
					if ($("#theAudio").length > 0) {
						$("#theAudio").fadeOut(250, function() { 
							$("#theAudio").remove(); 
							$("#ac").append("<audio controls id='theAudio' autoplay><source src='"+r.source+"' type='audio/mpeg' style='display: block;'></audio>"); });
					}
					else {
						$("#ac").append("<audio controls id='theAudio' autoplay><source src='"+r.source+"' type='audio/mpeg' style='display: block;'></audio>");
					}
					
					$("#theAudio").fadeIn(250);
					
					// indicate that it's showing (blocks subsequent creation attemps as the interval repeats, until it's done playing)
					document.hardway.myAudio.showing = 1;
					// log some info, just in case
					document.hardway.myAudio.source = r.source;
					document.hardway.myAudio.id = r.id;
					// remember the id to kill when playback done
					document.hardway.myAudioKill.data.id = r.id;
					// set up the playback ended listening event
					document.hardway.thatAudio = document.getElementById("theAudio");
					if (document.hardway.thatAudio.addEventListener) {
						document.hardway.thatAudio.addEventListener('ended', audioEnded, false);
					} else if (document.hardway.thatAudio.attachEvent)  {
						document.hardway.thatAudio.attachEvent('ended', audioEnded);
					}
				}
			
			}
			else {
				alert(r.text);
			}
		}).fail(function(data) { alert(data.statusText); }).always(function() { });	
	}
	
	if ((document.hardway.myVideo.showing == 0) && (document.hardway.myImage.showing == 0)) {
			
		// console.log("get the playlist..."); 
		var videojqxhr = $.ajax( document.hardway.myVideoFetch )
		.done(function(responseText) {
			r = JSON.parse(responseText);
			// console.log("Got some data back from server:\n"+responseText);
			if (r.error == 0) {
				if (r.source == "null") {
					// console.log("No videos in the playlist");
					$("#theVideo").fadeOut(250, function() { $("#theVideo").remove(); });
				}
				else {
					// console.log("check for options...");
					var mute = "";
					if (r.theoptions != "") {
						
						// console.log("we have options set...");
						theoptions = JSON.parse(r.theoptions);
						if (theoptions.theoption == "mute") {
							mute = " muted ";	
						}
					}
					
					// console.log("create and play video: "+r.source);			
					if ($("#theVideo").length > 0) {
						// console.log("Remove existing video element and append a new one.");
						$("#theVideo").remove(); $("#gofull").append("<video controls "+mute+" id='theVideo' autoplay><source src='"+r.source+"' style='display: block;' 'type='video/mp4'></video>");
					}
					else {
						// console.log("Append a new one.");
						$("#gofull").append("<video controls "+mute+" id='theVideo' autoplay><source src='"+r.source+"' style='display: block;' 'type='video/mp4'></video>");
					}
					
					
					
					// console.log("Make video ("+r.source+") visible...");
					$("#theVideo").fadeIn(250, function() {
						var diff = $("#gofull").height() - $("#theVideo").height();
						$("#theVideo").css("margin-top",diff/2);	
					});
								
					// console.log("Flag system that it's showing.");
					document.hardway.myVideo.showing = 1;
					document.hardway.myVideo.id = r.id;
					// console.log("Remember kill id.");
					document.hardway.myVideoKill.data.id = r.id;
					
					// console.log("Set up videoEnded listening event.");
					document.hardway.thatVideo = document.getElementById("theVideo");
					if (document.hardway.thatVideo.addEventListener) {
						document.hardway.thatVideo.addEventListener('ended', videoEnded, false);
					} else if (document.hardway.thatVideo.attachEvent)  {
						document.hardway.thatVideo.attachEvent('ended', videoEnded);
					}
				}
			}
			else {
				alert(r.text);
			}
		}).fail(function(data) { alert(data.statusText); }).always(function() { });	
		
	}	
	
	
	if ((document.hardway.myImage.showing == 0) && (document.hardway.myVideo.showing == 0)) {
					
		// get the playlist...
		var imagejqxhr = $.ajax( document.hardway.myImageFetch )
		.done(function(responseText) {
			r = JSON.parse(responseText);
			if (r.error == 0) {
				if (r.source == "null") {
					// this is the last img item currently in the playlist
					$("#theImage").fadeOut(250);
				}
				else {
					// create the image element, set up the timer for hiding it
					if (r.theoptions != "") {
						theoptions = JSON.parse(r.theoptions);
						if (theoptions.theoption == "duration") {
							document.hardway.imageDuration = parseInt(theoptions.thevalue)*1000;
							document.hardway.imageTimer = setTimeout(imageEnded,document.hardway.imageDuration);
							document.hardway.imageStartTime = new Date();
						}
					}
					
					if ($("#theVideo").length > 0) {
						$("#theVideo").fadeOut(250, function() { $("#theVideo").remove(); document.hardway.myVideo.showing = 0; });
					}
				
					$("#gofull").append("<img id='theImage' src='"+r.source+"' />");
					$("#theImage").fadeIn(250, function() {
						var diff = $("#gofull").height() - $("#theImage").height();
						$("#theImage").css("margin-top",diff/2);	
					});
					$("#theImage").fadeIn(250);
					//$("#theImage").css({ width: $("#theImage").width(), margin: "auto", display: "block", height: "auto" });
						
					// indicate that it's showing (blocks subsequent creation attemps as the interval repeats, until it's done playing)
					document.hardway.myImage.showing = 1;
					// log some info, just in case
					document.hardway.myImage.source = r.source;
					document.hardway.myImage.id = r.id;
					// remember the id to kill when playback done
					document.hardway.myImageKill.data.id = r.id;
				}
			}
			else {
				alert(r.text);
			}
		}).fail(function(data) { alert(data.statusText); }).always(function() { });	
		
	}
}

function audioEnded() {
	//console.log("Fade out the audio and remove it...");
	$("#theAudio").fadeOut(250,function() {  
	
		var vjqxhr = $.ajax( document.hardway.myAudioKill )
		.done(function(responseText) {
			r = JSON.parse(responseText);
			//console.log("Delete any related commands from "+document.hardway.myAudioKill.data.id);
			if (r.error == 0) {	
				$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/cmd.php", data: { itemid: document.hardway.myAudioKill.data.id, mode: "remove" }})
				.done(function() { 
					document.hardway.myAudioKill.data.id = -1;
					document.hardway.myAudioKill.data.eject = -1;
					document.hardway.myAudio.showing = 0;
					$("#theAudio").remove();
				});	
			}
			else {
				alert(r.text);
			}
		}).fail(function(data) { alert(data.statusText); }).always(function() { });
	});
}

function videoEnded() {	
	//console.log("Set system flag to not showing... reset kill object");
	//console.log(document.hardway.myVideoKill);
	//console.log("Fade out the video and remove it...");
	$("#theVideo").fadeOut(250,function() {  		
		var vjqxhr = $.ajax( document.hardway.myVideoKill )
		.done(function(responseText) {
			r = JSON.parse(responseText);
			//console.log("Got some data back from the video kill script...");
			if (r.error == 0) {
				// console.log("Delete any related commands from "+document.hardway.myVideoKill.data.id);
				$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/cmd.php", data: { itemid: document.hardway.myVideoKill.data.id, mode: "remove" }}).done(
					function() {
						document.hardway.myVideo.showing = 0;
						document.hardway.myVideoKill.data.id = -1;
						document.hardway.myVideoKill.data.eject = -1;
						$("#theVideo").remove();
					}
				);
			}
			else {
				alert(r.text);
			}
		}).fail(function(data) { alert(data.statusText); }).always(function() { });
	});
}

function imageEnded() {
	
	$("#theImage").fadeOut(250,function() {  
		var vjqxhr = $.ajax( document.hardway.myImageKill )
		.done(function(responseText) {
			r = JSON.parse(responseText);
			if (r.error == 0) {
				// console.log("Delete any related commands.");
				$.ajax({method: "POST", url: "http://dahi.manoa.hawaii.edu/three/cmd.php", data: { itemid: document.hardway.myImageKill.data.id, mode: "remove" }})
				.done( function() {
					document.hardway.imageTimer = null;
					document.hardway.myImage.showing = 0;
					document.hardway.myImageKill.data.id = -1;
					document.hardway.myImageKill.data.eject = 0;
					$("#theImage").remove();
				});
			}
			else {
				alert(r.text);
			}
		}).fail(function(data) { alert(data.statusText); }).always(function() { });
	});
}

</script>
</head>

<body>
<div id="gofull">
<div id="ac"></div>
</div>
</body>
</html>