<?php

/**
 * Load WORDCLIM current data.
 *
 * This file contains the routine to load the current data of WORLDCLIM from a set of
 * 30-seconds files in .bil format.
 *
 *	@package	GEOGRAPHY
 *	@subpackage	WORLDCLIM
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 27/06/2013
 */

/*=======================================================================================
 *																						*
 *								Load_WORLDCLIM_Current.php								*
 *																						*
 *======================================================================================*/

//
// Files path.
//
// This defines the base path of the WORLDCLIM files.
//
define( "kPath", "/Volumes/Data/GeographicFeatures/WORLDCLIM30" );

//
// Database name.
//
define( "kDB", "GEO" );

//
// Collection name.
//
define( "kCOLL", "WORLDCLIM30" );

//
// Chunk size, for buffers and record batches.
//
define( "kChunk", 8192 );						// Read buffer size (must be even).
define( "kRecs", 1000 );						// Record buffer size.

//
// Number of grid elements.
//
define( "kLatNum", 18000 );						// Number of latitude grid elements.
define( "kLonNum", 43200 );						// Number of longitude grid elements.

//
// NO-DATA value.
//
define( "kNoData", -9999 );						// No data value.


/*=======================================================================================
 *	GLOBALS																				*
 *======================================================================================*/
 
//
// Filenames and descriptors.
//
// The key represents the descriptor, 'cnt' represents the number of elements.
//
$resources = array
(
	'alt' => array
	(
		'file' => kPath.'/alt/alt.',
		'cnt'  => 1
	),
	'prec' => array
	(
		'file' => kPath.'/prec/prec_',
		'cnt'  => 12
	),
	'tmin' => array
	(
		'file' => kPath.'/tmin/tmin_',
		'cnt'  => 12
	),
	'tmean' => array
	(
		'file' => kPath.'/tmean/tmean_',
		'cnt'  => 12
	),
	'tmax' => array
	(
		'file' => kPath.'/tmax/tmax_',
		'cnt'  => 12
	)/*,
	'bio1' => array
	(
		'file' => kPath.'/bio1/bio1.',
		'cnt'  => 1
	),
	'bio2' => array
	(
		'file' => kPath.'/bio2/bio2.',
		'cnt'  => 1
	),
	'bio3' => array
	(
		'file' => kPath.'/bio3/bio3.',
		'cnt'  => 1
	),
	'bio4' => array
	(
		'file' => kPath.'/bio4/bio4.',
		'cnt'  => 1
	),
	'bio5' => array
	(
		'file' => kPath.'/bio5/bio5.',
		'cnt'  => 1
	),
	'bio6' => array
	(
		'file' => kPath.'/bio6/bio6.',
		'cnt'  => 1
	),
	'bio7' => array
	(
		'file' => kPath.'/bio7/bio7.',
		'cnt'  => 1
	),
	'bio8' => array
	(
		'file' => kPath.'/bio8/bio8.',
		'cnt'  => 1
	),
	'bio9' => array
	(
		'file' => kPath.'/bio9/bio9.',
		'cnt'  => 1
	),
	'bio10' => array
	(
		'file' => kPath.'/bio10/bio10.',
		'cnt'  => 1
	),
	'bio11' => array
	(
		'file' => kPath.'/bio11/bio11.',
		'cnt'  => 1
	),
	'bio12' => array
	(
		'file' => kPath.'/bio12/bio12.',
		'cnt'  => 1
	),
	'bio13' => array
	(
		'file' => kPath.'/bio13/bio13.',
		'cnt'  => 1
	),
	'bio14' => array
	(
		'file' => kPath.'/bio14/bio14.',
		'cnt'  => 1
	),
	'bio15' => array
	(
		'file' => kPath.'/bio15/bio15.',
		'cnt'  => 1
	),
	'bio16' => array
	(
		'file' => kPath.'/bio16/bio16.',
		'cnt'  => 1
	),
	'bio17' => array
	(
		'file' => kPath.'/bio17/bio17.',
		'cnt'  => 1
	),
	'bio18' => array
	(
		'file' => kPath.'/bio18/bio18.',
		'cnt'  => 1
	),
	'bio19' => array
	(
		'file' => kPath.'/bio19/bio19.',
		'cnt'  => 1
	)
*/
);

//
// File pointers.
//
$fp = Array();

//
// Buffers.
//
$buffer = Array();

//
// Batch records.
//
$records = Array();

//
// Current buffer index.
//
$bufchunk = kChunk / 2;
$bufind = $bufchunk + 1;


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
	$mongo = new MongoClient();
	$database = $mongo->selectDB( kDB );
	$collection = $database->selectCollection( kCOLL );
	
	//
	// Drop collection.
	//
	$collection->drop();
	
	//
	// Open files.
	//
	foreach( $resources as $key => $value )
	{
		//
		// Handle single file.
		//
		if( $value[ 'cnt' ] == 1 )
		{
			//
			// Open file.
			//
			$file = $value[ 'file' ].'bil';
			$fp[ $key ] = fopen( $file, 'rb' );
			if( ! $fp[ $key ] )
				throw new Exception( "Unable to open file [$file]" );			// !@! ==>
			
			//
			// Init buffer.
			//
			$buffer[ $key ] = Array();
		
		} // Single file.
		
		//
		// Handle multiple files.
		//
		else
		{
			//
			// Iterate count.
			//
			for( $i = 1; $i <= $value[ 'cnt' ]; $i++ )
			{
				//
				// Open file.
				//
				$file = $value[ 'file' ].$i.'.bil';
				$fp[ $key ][ $i ] = fopen( $file, 'rb' );
				if( ! $fp[ $key ] )
					throw new Exception( "Unable to open file [$file]" );		// !@! ==>
			
				//
				// Init buffer.
				//
				$buffer[ $key ][ $i ] = Array();
			}
			
		} // Multiple file.
		
	} // Opening files.
	
	//
	// Traverse latitude.
	//
	for( $row = 0,
		 $lat = 90 * 3600;
		 	$row < kLatNum;
				$row++,
				$lat -= 30 )
	{
		//
		// Display.
		//
		if( ! ($tmp = $lat % 3600) )
			echo( "\n".($lat/3600) );
		
		//
		// Set box.
		//
		$latmin = Seconds2DMS( $latmindeg, $lat );
		$latmed = Seconds2DMS( $latmeddeg, $lat - 15 );
		$latmax = Seconds2DMS( $latmaxdeg, $lat - 30 );
	
		//
		// Traverse longitude.
		//
		for( $col = 0,
			 $lon = -180 * 3600;
				$col < kLonNum;
					$col++,
					$lon += 30 )
		{
			//
			// Set box.
			//
			$lonmin = Seconds2DMS( $lonmindeg, $lon );
			$lonmed = Seconds2DMS( $lonmeddeg, $lon + 15 );
			$lonmax = Seconds2DMS( $lonmaxdeg, $lon + 30 );
			
			//
			// Check buffer.
			//
			if( $bufind > $bufchunk )
			{
				//
				// Load buffer.
				//
				foreach( $resources as $key => $value )
				{
					//
					// Handle single file.
					//
					if( $value[ 'cnt' ] == 1 )
					{
						$tmp = fread( $fp[ $key ], kChunk );
						$buffer[ $key ] = unpack( "s*", $tmp );
					}
		
					//
					// Handle multiple files.
					//
					else
					{
						//
						// Iterate count.
						//
						for( $i = 1; $i <= $value[ 'cnt' ]; $i++ )
						{
							$tmp = fread( $fp[ $key ][ $i ], kChunk );
							$buffer[ $key ][ $i ] = unpack( "s*", $tmp );
						}
					}
				
				} // Loading buffer.
				
				//
				// Init buffer index.
				//
				$bufind = 1;
			
			} // Empty buffer.
			
			//
			// Check altitude.
			//
			$test = (int) $buffer[ 'alt' ][ $bufind ];
			if( $test != kNoData )
			{
				//
				// Load data.
				//
				$current = Array();
				foreach( $resources as $key => $value )
				{
					//
					// Skip altitude.
					//
					if( $key != 'alt' )
					{
						//
						// Handle single file.
						//
						if( $value[ 'cnt' ] == 1 )
						{
							if( $buffer[ $key ][ $bufind ] != kNoData )
								$current[ $key ]
									= (int) $buffer[ $key ][ $bufind ];
						}
	
						//
						// Handle multiple files.
						//
						else
						{
							//
							// Iterate count.
							//
							for( $i = 1; $i <= $value[ 'cnt' ]; $i++ )
							{
								if( $buffer[ $key ][ $i ][ $bufind ] != kNoData )
									$current[ $key ][ $i ]
										= (int) $buffer[ $key ][ $i ][ $bufind ];
							}
						}
					
					} // Not altitude.
			
				} // Loading values.
			
				//
				// Determine grid index.
				//
				$index = (int) ($row * kLonNum) + ($col + 1);
			
				//
				// Init record.
				//
				$record = array( '_id' => $index );
			
				//
				// Set altitude.
				//
				$record[ 'alt' ] = (int) $test;
			
				//
				// Set latitude and longitude.
				//
				$record[ 'lat' ] = FormatLatitude( $latmed );
				$record[ 'lon' ] = FormatLongitude( $lonmed );
				
				//
				// Set row and column.
				//
				$record[ 'row' ] = (int) $row;
				$record[ 'col' ] = (int) $col;
			
				//
				// Set box.
				//
				$record[ 'box' ] = array
					(
						array( FormatLongitude( $lonmin ), FormatLatitude( $latmin ) ),
						array( FormatLongitude( $lonmax ), FormatLatitude( $latmin ) ),
						array( FormatLongitude( $lonmax ), FormatLatitude( $latmax ) ),
						array( FormatLongitude( $lonmin ), FormatLatitude( $latmax ) )
					);
									 
			
				//
				// Set bounding box.
				//
				$record[ 'bbox' ]
					= array( 'type' => 'Polygon',
							 'coordinates' => array( array( $lonmindeg, $latmindeg ),
							 						 array( $lonmaxdeg, $latmindeg ),
							 						 array( $lonmaxdeg, $latmaxdeg ),
							 						 array( $lonmindeg, $latmaxdeg ),
							 						 array( $lonmindeg, $latmindeg ) ) );
			
				//
				// Set coordinate.
				//
				$record[ 'pt']
					= array( 'type' => 'Point',
							 'coordinates' => array( $lonmeddeg, $latmeddeg ) );
				
				//
				// Set features.
				//
				$record[ '2000' ] = $current;
				
				//
				// Flush records.
				//
				if( count( $records ) >= kRecs )
				{
					$collection->batchInsert( $records );
					$records = Array();
					echo( '.' );
				}
				
				//
				// Add record.
				//
				$records[] = $record;
			
			} // Has data.
			
			//
			// Increment buffer index.
			//
			$bufind++;
		
		} // Iterating longitude.
	
	} // Iterating latitude.
	
	//
	// Flush records.
	//
	if( count( $records ) )
	{
		$collection->batchInsert( $records );
		echo( '.' );
	}
	
	//
	// Inform on index building.
	//
	echo( "\n\nBuilding indexes:" );
	
	//
	// Build altitude index.
	//
	echo( "\n    Elevation" );
	$collection->ensureIndex( array( 'alt' => 1 ) );
	
	//
	// Build point index.
	//
	echo( "\n    Coordinate" );
	$collection->ensureIndex( array( 'pt' => '2dsphere' ) );
	
	//
	// Build bounding box index.
	//
	echo( "\n    Bounding box" );
	$collection->ensureIndex( array( 'bbox' => '2dsphere' ) );
	
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


/*=======================================================================================
 *	CLOSE																				*
 *======================================================================================*/
 
//
// Close files.
//
foreach( $fp as $file )
{
	if( is_array( $file ) )
	{
		foreach( $file as $subfile )
			fclose( $subfile );
	}
	else
		fclose( $file );
}

	 

/*=======================================================================================
 *																						*
 *										FUNCTIONS										*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	Seconds2DMS																		*
	 *==================================================================================*/

	/**
	 * <h4>Convert seconds to DMS</h4>
	 *
	 * This function will convert the provided seconds value into degrees, minutes and
	 * seconds, in which negative degrees represent south or west.
	 *
	 * The first parameter is a reference that receives the decimal degrees value.
	 *
	 * The method will return the resulting three values as an array.
	 *
	 * @param reference			   &$theDegrees				Receives decimal degrees.
	 * @param integer				$theSeconds				Seconds.
	 *
	 * @access protected
	 * @return array
	 */
	function Seconds2DMS( &$theDegrees, $theSeconds )
	{
		//
		// Get decimal degrees.
		//
		$theDegrees = $theSeconds / 3600;
		
		//
		// Set degrees.
		//
		$deg = ( $theSeconds > 0 )
			 ? floor( $theDegrees )
			 : ceil( $theDegrees );
		
		//
		// Set minutes.
		//
		$theSeconds -= ($deg * 3600);
		$min = ( $theSeconds > 0 )
			 ? abs( floor( $theSeconds / 60 ) )
			 : abs( ceil( $theSeconds / 60 ) );
		
		//
		// Set seconds.
		//
		$sec = abs( $theSeconds ) - ($min * 60);
		
		return array( $deg, $min, $sec );											// ==>
	
	} // Seconds2DMS.

	 
	/*===================================================================================
	 *	FormatLongitude																	*
	 *==================================================================================*/

	/**
	 * <h4>Format a longitude</h4>
	 *
	 * This function will return a properly formatted longitude from a provided longitude
	 * array of three elements: degrees, minutes and seconds.
	 *
	 * The degrees can be negative.
	 *
	 * @param reference			   &$theCoordinate			Coordinate array.
	 *
	 * @access protected
	 * @return string
	 */
	function FormatLongitude( &$theCoordinate )
	{
		$coordinate  = (string) abs( $theCoordinate[ 0 ] );
		$coordinate .= '°';
		$coordinate .= $theCoordinate[ 1 ];
		$coordinate .= "'";
		$coordinate .= $theCoordinate[ 2 ];
		$coordinate .= '"';
		$coordinate .= (( $theCoordinate[ 0 ] > 0 ) ? 'E' : 'W');
		
		return $coordinate;															// ==>
	
	} // FormatLongitude.

	 
	/*===================================================================================
	 *	FormatLatitude																	*
	 *==================================================================================*/

	/**
	 * <h4>Format a latitude</h4>
	 *
	 * This function will return a properly formatted latitude from a provided latitude
	 * array of three elements: degrees, minutes and seconds.
	 *
	 * The degrees can be negative.
	 *
	 * @param reference			   &$theCoordinate			Coordinate array.
	 *
	 * @access protected
	 * @return string
	 */
	function FormatLatitude( &$theCoordinate )
	{
		$coordinate  = (string) abs( $theCoordinate[ 0 ] );
		$coordinate .= '°';
		$coordinate .= $theCoordinate[ 1 ];
		$coordinate .= "'";
		$coordinate .= $theCoordinate[ 2 ];
		$coordinate .= '"';
		$coordinate .= (( $theCoordinate[ 0 ] > 0 ) ? 'N' : 'S');
		
		return $coordinate;															// ==>
	
	} // FormatLatitude.


?>
