<?php

/**
 * Load geographic data layers.
 *
 * This file contains the routine to load the current data of WORLDCLIM from a set of
 * 30-seconds files in .bil format.
 *
 * The script accepts a single optional paraneter that determines the number of tiles to
 * skip.
 *
 *	@package	GEOGRAPHY
 *	@subpackage	LAYERS
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
		kFILE_NODATA => -9999,
		kFILE_TRANS => NULL
	),
	'gens' => array
	(
		kFILE_PATH => kPATH_FILES.'/gens/gens.bil',
		kFILE_BANDS => 1,
		kFILE_BPACK => 'C',
		kFILE_NODATA => 0,
		kFILE_TRANS => array(
			1 => array(
				'id' => 'A1',
				'c' => '1',
				'e' => 'A' ),
			2 => array(
				'id' => 'A2',
				'c' => '1',
				'e' => 'A' ),
			3 => array(
				'id' => 'B1',
				'c' => '1',
				'e' => 'B' ),
			4 => array(
				'id' => 'B2',
				'c' => '1',
				'e' => 'B' ),
			5 => array(
				'id' => 'B3',
				'c' => '1',
				'e' => 'B' ),
			6 => array(
				'id' => 'C1',
				'c' => '1',
				'e' => 'C' ),
			7 => array(
				'id' => 'C2',
				'c' => '1',
				'e' => 'C' ),
			8 => array(
				'id' => 'D1',
				'c' => '1',
				'e' => 'D' ),
			9 => array(
				'id' => 'D2',
				'c' => '1',
				'e' => 'D' ),
			10 => array(
				'id' => 'D3',
				'c' => '1',
				'e' => 'D' ),
			11 => array(
				'id' => 'F1',
				'c' => '2',
				'e' => 'F' ),
			12 => array(
				'id' => 'F2',
				'c' => '2',
				'e' => 'F' ),
			13 => array(
				'id' => 'E1',
				'c' => '2',
				'e' => 'E' ),
			14 => array(
				'id' => 'E2',
				'c' => '2',
				'e' => 'E' ),
			15 => array(
				'id' => 'F3',
				'c' => '2',
				'e' => 'F' ),
			16 => array(
				'id' => 'F4',
				'c' => '2',
				'e' => 'F' ),
			17 => array(
				'id' => 'F5',
				'c' => '2',
				'e' => 'F' ),
			18 => array(
				'id' => 'F6',
				'c' => '2',
				'e' => 'F' ),
			19 => array(
				'id' => 'F7',
				'c' => '2',
				'e' => 'F' ),
			20 => array(
				'id' => 'F8',
				'c' => '2',
				'e' => 'F' ),
			21 => array(
				'id' => 'F9',
				'c' => '2',
				'e' => 'F' ),
			22 => array(
				'id' => 'F10',
				'c' => '2',
				'e' => 'F' ),
			23 => array(
				'id' => 'C3',
				'c' => '1',
				'e' => 'C' ),
			24 => array(
				'id' => 'E3',
				'c' => '2',
				'e' => 'E' ),
			25 => array(
				'id' => 'F11',
				'c' => '2',
				'e' => 'F' ),
			26 => array(
				'id' => 'F12',
				'c' => '2',
				'e' => 'F' ),
			27 => array(
				'id' => 'F13',
				'c' => '2',
				'e' => 'F' ),
			28 => array(
				'id' => 'F14',
				'c' => '2',
				'e' => 'F' ),
			29 => array(
				'id' => 'F15',
				'c' => '2',
				'e' => 'F' ),
			30 => array(
				'id' => 'G1',
				'c' => '2',
				'e' => 'G' ),
			31 => array(
				'id' => 'G2',
				'c' => '2',
				'e' => 'G' ),
			32 => array(
				'id' => 'E4',
				'c' => '2',
				'e' => 'E' ),
			33 => array(
				'id' => 'G3',
				'c' => '2',
				'e' => 'G' ),
			34 => array(
				'id' => 'G4',
				'c' => '2',
				'e' => 'G' ),
			35 => array(
				'id' => 'G5',
				'c' => '2',
				'e' => 'G' ),
			36 => array(
				'id' => 'G6',
				'c' => '2',
				'e' => 'G' ),
			37 => array(
				'id' => 'G7',
				'c' => '2',
				'e' => 'G' ),
			38 => array(
				'id' => 'G8',
				'c' => '2',
				'e' => 'G' ),
			39 => array(
				'id' => 'E5',
				'c' => '2',
				'e' => 'E' ),
			40 => array(
				'id' => 'G9',
				'c' => '2',
				'e' => 'G' ),
			41 => array(
				'id' => 'G10',
				'c' => '2',
				'e' => 'G' ),
			42 => array(
				'id' => 'G11',
				'c' => '2',
				'e' => 'G' ),
			43 => array(
				'id' => 'H1',
				'c' => '3',
				'e' => 'H' ),
			44 => array(
				'id' => 'G12',
				'c' => '2',
				'e' => 'G' ),
			45 => array(
				'id' => 'H2',
				'c' => '3',
				'e' => 'H' ),
			46 => array(
				'id' => 'H3',
				'c' => '3',
				'e' => 'H' ),
			47 => array(
				'id' => 'G13',
				'c' => '2',
				'e' => 'G' ),
			48 => array(
				'id' => 'G14',
				'c' => '2',
				'e' => 'G' ),
			49 => array(
				'id' => 'J1',
				'c' => '3',
				'e' => 'J' ),
			50 => array(
				'id' => 'H4',
				'c' => '3',
				'e' => 'H' ),
			51 => array(
				'id' => 'H5',
				'c' => '3',
				'e' => 'H' ),
			52 => array(
				'id' => 'H6',
				'c' => '3',
				'e' => 'H' ),
			53 => array(
				'id' => 'J2',
				'c' => '3',
				'e' => 'J' ),
			54 => array(
				'id' => 'H7',
				'c' => '3',
				'e' => 'H' ),
			55 => array(
				'id' => 'J3',
				'c' => '3',
				'e' => 'J' ),
			56 => array(
				'id' => 'H8',
				'c' => '3',
				'e' => 'H' ),
			57 => array(
				'id' => 'H9',
				'c' => '3',
				'e' => 'H' ),
			58 => array(
				'id' => 'I1',
				'c' => '3',
				'e' => 'I' ),
			59 => array(
				'id' => 'I2',
				'c' => '3',
				'e' => 'I' ),
			60 => array(
				'id' => 'J4',
				'c' => '3',
				'e' => 'J' ),
			61 => array(
				'id' => 'J5',
				'c' => '3',
				'e' => 'J' ),
			62 => array(
				'id' => 'J6',
				'c' => '3',
				'e' => 'J' ),
			63 => array(
				'id' => 'I3',
				'c' => '3',
				'e' => 'I' ),
			64 => array(
				'id' => 'I4',
				'c' => '3',
				'e' => 'I' ),
			65 => array(
				'id' => 'I5',
				'c' => '3',
				'e' => 'I' ),
			66 => array(
				'id' => 'K1',
				'c' => '4',
				'e' => 'K' ),
			67 => array(
				'id' => 'K2',
				'c' => '4',
				'e' => 'K' ),
			68 => array(
				'id' => 'K3',
				'c' => '4',
				'e' => 'K' ),
			69 => array(
				'id' => 'I6',
				'c' => '3',
				'e' => 'I' ),
			70 => array(
				'id' => 'K4',
				'c' => '4',
				'e' => 'K' ),
			71 => array(
				'id' => 'K5',
				'c' => '4',
				'e' => 'K' ),
			72 => array(
				'id' => 'K6',
				'c' => '4',
				'e' => 'K' ),
			73 => array(
				'id' => 'K7',
				'c' => '4',
				'e' => 'K' ),
			74 => array(
				'id' => 'K8',
				'c' => '4',
				'e' => 'K' ),
			75 => array(
				'id' => 'K9',
				'c' => '4',
				'e' => 'K' ),
			76 => array(
				'id' => 'K10',
				'c' => '4',
				'e' => 'K' ),
			77 => array(
				'id' => 'K11',
				'c' => '4',
				'e' => 'K' ),
			78 => array(
				'id' => 'L1',
				'c' => '4',
				'e' => 'L' ),
			79 => array(
				'id' => 'L2',
				'c' => '4',
				'e' => 'L' ),
			80 => array(
				'id' => 'K12',
				'c' => '4',
				'e' => 'K' ),
			81 => array(
				'id' => 'K13',
				'c' => '4',
				'e' => 'K' ),
			82 => array(
				'id' => 'L3',
				'c' => '4',
				'e' => 'L' ),
			83 => array(
				'id' => 'N1',
				'c' => '6',
				'e' => 'N' ),
			84 => array(
				'id' => 'L4',
				'c' => '4',
				'e' => 'L' ),
			85 => array(
				'id' => 'N2',
				'c' => '6',
				'e' => 'N' ),
			86 => array(
				'id' => 'L5',
				'c' => '4',
				'e' => 'L' ),
			87 => array(
				'id' => 'N3',
				'c' => '6',
				'e' => 'N' ),
			88 => array(
				'id' => 'N4',
				'c' => '6',
				'e' => 'N' ),
			89 => array(
				'id' => 'L6',
				'c' => '4',
				'e' => 'L' ),
			90 => array(
				'id' => 'N5',
				'c' => '6',
				'e' => 'N' ),
			91 => array(
				'id' => 'N6',
				'c' => '6',
				'e' => 'N' ),
			92 => array(
				'id' => 'N7',
				'c' => '6',
				'e' => 'N' ),
			93 => array(
				'id' => 'N8',
				'c' => '6',
				'e' => 'N' ),
			94 => array(
				'id' => 'N9',
				'c' => '6',
				'e' => 'N' ),
			95 => array(
				'id' => 'M1',
				'c' => '5',
				'e' => 'M' ),
			96 => array(
				'id' => 'N10',
				'c' => '6',
				'e' => 'N' ),
			97 => array(
				'id' => 'N11',
				'c' => '6',
				'e' => 'N' ),
			98 => array(
				'id' => 'M2',
				'c' => '5',
				'e' => 'M' ),
			99 => array(
				'id' => 'O1',
				'c' => '6',
				'e' => 'O' ),
			100 => array(
				'id' => 'M3',
				'c' => '5',
				'e' => 'M' ),
			101 => array(
				'id' => 'M4',
				'c' => '5',
				'e' => 'M' ),
			102 => array(
				'id' => 'M5',
				'c' => '5',
				'e' => 'M' ),
			103 => array(
				'id' => 'O2',
				'c' => '6',
				'e' => 'O' ),
			104 => array(
				'id' => 'M6',
				'c' => '5',
				'e' => 'M' ),
			105 => array(
				'id' => 'M7',
				'c' => '5',
				'e' => 'M' ),
			106 => array(
				'id' => 'M8',
				'c' => '5',
				'e' => 'M' ),
			107 => array(
				'id' => 'O3',
				'c' => '6',
				'e' => 'O' ),
			108 => array(
				'id' => 'R1',
				'c' => '7',
				'e' => 'R' ),
			109 => array(
				'id' => 'R2',
				'c' => '7',
				'e' => 'R' ),
			110 => array(
				'id' => 'P1',
				'c' => '6',
				'e' => 'P' ),
			111 => array(
				'id' => 'R3',
				'c' => '7',
				'e' => 'R' ),
			112 => array(
				'id' => 'R4',
				'c' => '7',
				'e' => 'R' ),
			113 => array(
				'id' => 'R5',
				'c' => '7',
				'e' => 'R' ),
			114 => array(
				'id' => 'R6',
				'c' => '7',
				'e' => 'R' ),
			115 => array(
				'id' => 'R7',
				'c' => '7',
				'e' => 'R' ),
			116 => array(
				'id' => 'P2',
				'c' => '6',
				'e' => 'P' ),
			117 => array(
				'id' => 'R8',
				'c' => '7',
				'e' => 'R' ),
			118 => array(
				'id' => 'R9',
				'c' => '7',
				'e' => 'R' ),
			119 => array(
				'id' => 'Q1',
				'c' => '6',
				'e' => 'Q' ),
			120 => array(
				'id' => 'R10',
				'c' => '7',
				'e' => 'R' ),
			121 => array(
				'id' => 'Q2',
				'c' => '6',
				'e' => 'Q' ),
			122 => array(
				'id' => 'Q3',
				'c' => '6',
				'e' => 'Q' ),
			123 => array(
				'id' => 'Q4',
				'c' => '6',
				'e' => 'Q' ),
			124 => array(
				'id' => 'Q5',
				'c' => '6',
				'e' => 'Q' ),
			125 => array(
				'id' => 'Q6',
				'c' => '6',
				'e' => 'Q' ) )
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
		kFILE_NODATA => -9999,
		kFILE_TRANS => NULL
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
		kFILE_NODATA => -9999,
		kFILE_TRANS => NULL
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
		kFILE_NODATA => -9999,
		kFILE_TRANS => NULL
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
		kFILE_NODATA => -9999,
		kFILE_TRANS => NULL
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
		kFILE_NODATA => -9999,
		kFILE_TRANS => NULL
	);
}

//
// Batch records.
//
$records = Array();

//
// Tiles skip count.
//
$skip = 0;


/*=======================================================================================
 *	TRY																					*
 *======================================================================================*/
 
//
// TRY BLOCK.
//
try
{
	//
	// Check tiles skip count.
	//
	if( isset( $_SERVER['argc'] )
	 && ($_SERVER['argc'] > 1) )
		$skip = (int) $argv[ 1 ];
	
	//
	// Open database connection.
	//
	$mongo = new MongoClient( kDEFAULT_SERVER );
	$database = $mongo->selectDB( kDEFAULT_DATABASE );
	$collection = $database->selectCollection( kDEFAULT_COLLECTION );
	
	//
	// Drop collection.
	//
	if( ! $skip )
		$collection->drop();
	
	//
	// Instantiate files iterator.
	//
	$iter = new CBilFilesIterator( kBUF_TILE_REST,
								   kBUF_TILE_COUNT,
								   array( kORIGIN_LON, kORIGIN_LAT ),
								   array( kRANGE_LON, kRANGE_LAT ),
								   $skip );
	
	//
	// Reference files in iterator.
	//
	foreach( $resources as $key => $value )
		$iter->FileSet( $key, $value[ kFILE_PATH ],
							  $value[ kFILE_BANDS ],
							  $value[ kFILE_BPACK ],
							  $value[ kFILE_NODATA ],
							  $value[ kFILE_TRANS ] );
	
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
			$collection->batchInsert( $records );
			$records = Array();
			if( isset( $_SERVER['argc'] )
			 && defined( 'kENV_VERBOSE' )
			 && kENV_VERBOSE )
				echo( 'X: '.$record[ 'tile' ][ 0 ].' Y: '.$record[ 'tile' ][ 1 ]."\n" );
		}
	
	} // Iterating reader.
	
	//
	// Flush records.
	//
	if( count( $records ) )
	{
		$collection->batchInsert( $records );
		if( isset( $_SERVER['argc'] )
		 && defined( 'kENV_VERBOSE' )
		 && kENV_VERBOSE )
			echo( 'X: '.$record[ 'tile' ][ 0 ].' Y: '.$record[ 'tile' ][ 1 ]."\n" );
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
