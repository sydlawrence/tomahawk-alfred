<?php

$query = $argv[1];

if ( strlen( $query ) < 3 ):
	exit(1);
endif;

require_once('rdio.php');
require_once('workflows.php');

$w = new Workflows();

define("RDIO_CONSUMER_KEY", 'zd94nugubcc78p7xfahmcny9');
define("RDIO_CONSUMER_SECRET", 's5Ezp3s3RE');

$rdio = new Rdio( array( RDIO_CONSUMER_KEY, RDIO_CONSUMER_SECRET ) );

$out = $rdio->call( 'search', array( 'query' => $query, 'types' => 'Track,Album,Artist', 'count' => 20 ) );

foreach( $out->result->results as $result ):

	if ( $result->type == "t" ):
		$uid = 'thtrack';
		$title = $result->name;
		$arg = "tomahawk://play/track?title=".$result->name."&artist=".$result->artist;
		$subtitle = $result->artist. " - " .$result->album;
		$icon = 'th-track.png';
	elseif ( $result->type == "a" ):
		$uid = 'thalbum';
		$arg = "tomahawk://view/album?artist=".$result->artist."&name=".$result->name;
		$title = $result->name;
		$subtitle = $result->artist;
		$icon = 'th-album.png';
	elseif ( $result->type == "r" ):
		$uid = 'thartist';
		$arg = "tomahawk://view/artist?name=".$result->name;
		$title = $result->name;
		$subtitle = $result->length. ' tracks available on Rdio';
		$icon = 'th-artist.png';
	endif;

	$w->result( $uid, $arg, $title, $subtitle, $icon );

endforeach;

echo $w->toxml();