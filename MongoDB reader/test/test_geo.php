<?php
	
/**
 * Test geographic queries.
 *
 * This file contains a series of geographic queries matched upon WORLDCLIM30 data.
 *
 *	@package	Test
 *	@subpackage	TEST
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 15/07/2013
 */

/*=======================================================================================
 *																						*
 *										test_geo.php									*
 *																						*
 *======================================================================================*/


/*=======================================================================================
 *	TEST GEOGRAPHIC QUERIES																*
 *======================================================================================*/

//
// TRY BLOCK.
//
try
{
	//
	// Connect to database.
	//
	$client = new MongoClient( 'mongodb://192.168.181.101:27017' );
	$database = $client->selectDB( 'GEO' );
	$collection = $database->selectCollection( 'WORLDCLIM30' );
	
	//
	// Test proximity.
	//
	echo( "\nNEAR\n" );
	$query = array
	(
		'pt' => array
		(
			'$near' => array
			(
				'$geometry' => array
				(
					'type' => 'Point',
					'coordinates' => array( -16.6422, 28.2727 )
				),
				'$maxDistance' => 650
			)
		)
	);
	print_r( $query );
	echo( "\n" );
	$rs = $collection->find( $query );
	$records = Array();
	foreach( $rs as $record )
	{
		$line = Array();
		$line[0] = $record[ 'pt' ][ 'coordinates' ][ 0 ];
		$line[1] = $record[ 'pt' ][ 'coordinates' ][ 1 ];
		$line[2] = $record[ 'alt' ];
		$records[] = $line;
	}
	print_r( $records );
	echo( "\n" );
	
	//
	// Test within.
	//
	echo( "\nGEOWITHIN\n" );
	$query = array
	(
		'pt' => array
		(
			'$geoWithin' => array
			(
				'$geometry' => array
				(
					'type' => 'Polygon',
					'coordinates' => array
					(
						array
						(
							array( (-16.6422 - 0.0041666666667), (28.2727 + 0.0041666666667) ),
							array( (-16.6422 + 0.0041666666667), (28.2727 + 0.0041666666667) ),
							array( (-16.6422 + 0.0041666666667), (28.2727 - 0.0041666666667) ),
							array( (-16.6422 - 0.0041666666667), (28.2727 - 0.0041666666667) ),
							array( (-16.6422 - 0.0041666666667), (28.2727 + 0.0041666666667) )
						)
					)
				)
			)
		)
	);
	print_r( $query );
	echo( "\n" );
	$rs = $collection->find( $query );
	$records = Array();
	foreach( $rs as $record )
	{
		$line = Array();
		$line[0] = $record[ 'pt' ][ 'coordinates' ][ 0 ];
		$line[1] = $record[ 'pt' ][ 'coordinates' ][ 1 ];
		$line[2] = $record[ 'alt' ];
		$records[] = $line;
	}
	print_r( $records );
	echo( "\n" );
	
	//
	// Test geoNear.
	//
	echo( "\nGEONEAR\n" );
	$command = array
	(
		'geoNear' => 'WORLDCLIM30',
		'near' => array( -16.6422, 28.2727 ),
		'spherical' => TRUE,
		'num' => 5
	);
	print_r( $command );
	echo( "\n" );
	$rs = $database->command( $command );
	$records = Array();
	foreach( $rs[ 'results' ] as $record )
	{
		$line = Array();
		$line[0] = $record[ 'obj' ][ 'pt' ][ 'coordinates' ][ 0 ];
		$line[1] = $record[ 'obj' ][ 'pt' ][ 'coordinates' ][ 1 ];
		$line[2] = $record[ 'obj' ][ 'alt' ];
		$line[3] = $record[ 'dis' ];
		$records[] = $line;
	}
	print_r( $records );
	echo( "\n" );
	
	echo( "\nDONE\n" );
}
catch( Exception $error )
{
	echo( "\nUnexpected exception\n" );
	echo( (string) $error );
}

?>
