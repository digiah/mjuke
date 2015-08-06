# mjuke

mJuke is a multiuser live media jukebox presentation platform.

Development began June 15, 2015 to support a panel discussion about Black Lives Matter.

Panelists point their browser to a client script (q.php) that allows them to select audio and video files for playback via a server script (v.php). In our first presentation, v.php was running on a laptop which was connected to a projector. The audience experienced the playback results while panelists cued and mixed sound live.

v.php periodically checks the playback queue which is running in a mySQL database, as well as a command queue. The q.php client script also checks the cue to make updates to the UI.

Each panelist can:

1) play an asset (one audio file and one video file can play simultaneously)
2) pause an asset
3) eject an asset (remove from playback immediately)
4) mute an asset

INITIAL TECHNICAL SUMMARY:

clr.php - "Clear" clears the playback cue.

cmd.php - "Command" adds a command (play, pause, eject, mute) to the command queue.  Returns a JSON object with status.

dbheader.php - "Database Header" connects to the database

gq.php  - "Get Queue" gets the list of media objects that are NOT playing, returns a JSON object with media asset data or an error.

kq.php - "Kill Queue" deletes a specific item from the playback queue and any associated commands. The EJECT command does this. Returns a JSON object with status.

nq.php - "Enqueue" adds a media asset to the playback queue, returns a JSON object with status of operation

ppq.php - "Play Pause Queue" updates the playback queue for the corresponding media object with the command passed to it, removes that command from the command queue. Returns a JSON object with status of the operation.

q.php - "Queue" is the CLIENT script. Lists all media assets available for playback, as well as their status. If the USER is set to DMG (for now) this script also renders a raw summary of the playback and command cues. Queue-flush command can be triggered from this script.

stats.php - "Stats" renders the current state of the playback and command queues. Only visible to q.php if user DMG is indicated.

status.php - "Status" gets the play status of all items in the playback cue. Returns a JSON object with either the items int he playback queue or an error.

v.php - "Video" (misnomer, because it also plays audio!) periodically queries the playback and command queues to decide which media asset to present and how (if necessary) to modify its playback (mute, pause, eject).


