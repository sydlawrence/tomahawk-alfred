<?php
require_once('workflows.php');
$w = new Workflows();

/**
* Running?
* Check to see if Rdio is running and proceed accordingly
*/
exec('ps aux | grep Rdio', $running);

if ( count( $running ) > 1 ):

	/**
	* Playing?
	* Is Rdio currently playing a track?
	*/
	$playing = `osascript -e 'tell application "Rdio" to player state'`;

	// If playing
	if ( str_replace( "\n", "", $playing ) == 'playing' ):

		$attr = exec('osascript RdioPlaying.scpt');
		$values = explode("	", $attr);
		$keys = array('artist', 'track', 'album');
		extract( array_combine( $keys, $values ) );

		// Create a new feedback item based on information about the current track
		$w->result( 'rdioplaying', $track, "Now playing: $track", $artist.' - '.$album, 'playing.png', 'no' );

	// If not playing
	else:

		// Create a new feedback item indicating that Rdio isn't playing a track
		$w->result( 'rdioplaying', 'none', 'Now Playing', 'No track is currently playing', 'playing.png', 'no' );

	endif;

// Create a feedback item indicating that Rdio isn't playing a track (it's not running)
else:

$w->result( 'rdioplaying', 'none', 'Now Playing', 'No track is currently playing', 'playing.png', 'no' );

endif;

// Return the feedback results
echo $w->toxml();