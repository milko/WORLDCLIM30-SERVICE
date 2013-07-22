<?php

/**
 * Load geographic data layers.
 *
 * This file contains the routine to load the current data of WORLDCLIM from a set of
 * 30-seconds files in .bil format.
 *
 *	@package	GEOGRAPHY
 *	@subpackage	WORLDCLIM
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 27/06/2013
 *				1.50 20/17/2013
 */

/*=======================================================================================
 *																						*
 *										LoadLayers.php									*
 *																						*
 *======================================================================================*/

/**
 * Local definitions.
 *
 * This include file contains all local definitions.
 */
require_once( "local.inc.php" );

/**
 * Class definitions.
 *
 * This include file contains all class definitions.
 */
require_once( "CBilFilesReader.php" );


/*=======================================================================================
 *	GLOBALS																				*
 *======================================================================================*/
 
//
// Elevation and global environment stratification.
//
$resources = array
(
	'alt' => array
	(
		kFILE_PATH => kPATH_FILES.'/alt/alt.bil',
		kFILE_BANDS => 1,
		kFILE_BPACK => 's',
		kFILE_NODATA => -9999
	),
	'gens' => array
	(
		kFILE_PATH => kPATH_FILES.'/gens/gens.bil',
		kFILE_BANDS => 1,
		kFILE_BPACK => 'C',
		kFILE_NODATA => 0
	)
);

//
// Load precipitation.
//
for( $i = 1; $i < 13; $i++ )
{
	$resources[ "prec$i" ] = array
	(
		kFILE_PATH => kPATH_FILES."/prec/prec$i.bil",
		kFILE_BANDS => 1,
		kFILE_BPACK => 's',
		kFILE_NODATA => -9999
	);
}

//
// Load minimum temperature.
//
for( $i = 1; $i < 13; $i++ )
{
	$resources[ "tmin$i" ] = array
	(
		kFILE_PATH => kPATH_FILES."/tmin/tmin$i.bil",
		kFILE_BANDS => 1,
		kFILE_BPACK => 's',
		kFILE_NODATA => -9999
	);
}

//
// Load mean temperature.
//
for( $i = 1; $i < 13; $i++ )
{
	$resources[ "tmean$i" ] = array
	(
		kFILE_PATH => kPATH_FILES."/tmean/tmean$i.bil",
		kFILE_BANDS => 1,
		kFILE_BPACK => 's',
		kFILE_NODATA => -9999
	);
}

//
// Load maximum temperature.
//
for( $i = 1; $i < 13; $i++ )
{
	$resources[ "tmax$i" ] = array
	(
		kFILE_PATH => kPATH_FILES."/tmax/tmax$i.bil",
		kFILE_BANDS => 1,
		kFILE_BPACK => 's',
		kFILE_NODATA => -9999
	);
}

//
// Load bio-climatic variables.
//
for( $i = 1; $i < 20; $i++ )
{
	$resources[ "bio$i" ] = array
	(
		kFILE_PATH => kPATH_FILES."/bio$i/bio$i.bil",
		kFILE_BANDS => 1,
		kFILE_BPACK => 's',
		kFILE_NODATA => -9999
	);
}

//
// Batch records.
//
$records = Array();


/*=======================================================================================
 *	TRY																					*
 *======================================================================================*/
 
//
// TRY BLOCK.
//
try
{
	//
	// Open database connection.
	//
	$mongo = new MongoClient( kDEFAULT_SERVER );
	$database = $mongo->selectDB( kDEFAULT_DATABASE );
	$collection = $database->selectCollection( kDEFAULT_COLLECTION );
	
	//
	// Drop collection.
	//
	$collection->drop();
	
	//
	// Instantiate files iterator.
	//
	$iter = new CBilFilesIterator( kBUF_TILE_REST,
								   kBUF_TILE_COUNT,
								   array( kORIGIN_LON, kORIGIN_LAT ),
								   array( kRANGE_LON, kRANGE_LAT ) );
	
	//
	// Reference files in iterator.
	//
	foreach( $resources as $key => $value )
		$iter->FileSet( $key, $value[ kFILE_PATH ],
							  $value[ kFILE_BANDS ],
							  $value[ kFILE_BPACK ],
							  $value[ kFILE_NODATA ] );
	
	//
	// Instantiate and oterate reader.
	//
	$reader = new CBilFilesReader( $iter );
	foreach( $reader as $key => $value )
	{
		//
		// Init record.
		//
		$record = array( '_id' => (int) $key );
		
		//
		// Load point coordinates.
		//
		$record[ 'pt' ] = array( 'type' => 'Point',
								 'coordinates' => $value[ kOFFSET_POINT ] );
		$record[ 'dms' ] = $value[ kOFFSET_COORD ];
								  
		//
		// Load tile coordinates.
		//
		$record[ 'tile' ] = $value[ kOFFSET_TILE ];

		//
		// Load box coordinates.
		//
		$record[ 'bdec' ] = $value[ kOFFSET_BOX_DEC ];
		$record[ 'bdms' ] = $value[ kOFFSET_BOX_DMS ];
		
		//
		// Load elevation.
		//
		if( array_key_exists( 'alt', $value ) )
			$record[ 'elev' ] = (int) $value[ 'alt' ];
		
		//
		// Init climate data.
		//
		$clim = Array();
		
		//
		// Load current data.
		//
		$clim[ '2000' ] = Array();
		$cur = & $clim[ '2000' ];
		
		//
		// Load Global Environment Stratification.
		//
		if( array_key_exists( 'gens', $value ) )
			$cur[ 'gens' ] = $value[ 'gens' ];
		
		//
		// Load bioclimatic data.
		//
		$tmp = Array();
		for( $i = 1; $i <=19; $i++ )
		{
			if( array_key_exists( "bio$i", $value ) )
				$tmp[ $i ] = $value[ "bio$i" ];
		}
		if( count( $tmp ) )
			$cur[ 'bio' ] = $tmp;
		
		//
		// Load precipitation.
		//
		$tmp = Array();
		for( $i = 1; $i <=13; $i++ )
		{
			if( array_key_exists( "prec$i", $value ) )
				$tmp[ $i ] = $value[ "prec$i" ];
		}
		if( count( $tmp ) )
			$cur[ 'prec' ] = $tmp;
		
		//
		// Load temperature.
		//
		$tmp = Array();
		for( $i = 1; $i <=13; $i++ )
		{
			if( array_key_exists( "tmin$i", $value ) )
				$tmp[ 'l' ][ $i ] = $value[ "tmin$i" ];
			if( array_key_exists( "tmean$i", $value ) )
				$tmp[ 'm' ][ $i ] = $value[ "tmean$i" ];
			if( array_key_exists( "tmax$i", $value ) )
				$tmp[ 'h' ][ $i ] = $value[ "tmax$i" ];
		}
		if( count( $tmp ) )
			$cur[ 'temp' ] = $tmp;
		
		//
		// Load climate.
		//
		if( count( $clim ) )
			$record[ 'clim' ] = $clim;
		
		//
		// Add to records.
		//
		$records[] = $record;
		
		//
		// Flush records.
		//
		if( count( $records ) >= kBUF_RECORD_COUNT )
		{
			echo( 'X: '.$record[ 'tile' ][ 0 ].' Y: '.$record[ 'tile' ][ 1 ]."\n" );
			$collection->batchInsert( $records );
			$records = Array();
		}
	
	} // Iterating reader.
	
	//
	// Flush records.
	//
	if( count( $records ) )
	{
		echo( 'X: '.$record[ 'tile' ][ 0 ].' Y: '.$record[ 'tile' ][ 0 ]."\n" );
		$collection->batchInsert( $records );
	}
	
	//
	// Show.
	//
	echo( "\n\nDone!\n" );

} // TRY BLOCK.


/*=======================================================================================
 *	CATCH																				*
 *======================================================================================*/
 
//
// CATCH BLOCK.
//
catch( Exception $error )
{
	echo( (string) $error );
}


?>
