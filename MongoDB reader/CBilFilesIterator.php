<?php

/**
 * <i>CBilFilesIterator</i> class definition.
 *
 * This file contains the class definition of <b>CBilFilesIterator</b> which represents a
 * <tt>.bil</tt> file reader.
 *
 *	@package	GEO
 *	@subpackage	Framework
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 19/07/2013
 */

/*=======================================================================================
 *																						*
 *								CBilFilesIterator.php									*
 *																						*
 *======================================================================================*/

/**
 * Class definitions.
 *
 * This include file contains all class definitions.
 */
require_once( "CBilFilesIterator.inc.php" );

/**
 * <h4><tt>.bil</tt> file reader</h4>
 *
 * The main duty of this class is to open a series of <tt>.bil</tt> files and read
 * sequentially from all of them returning the corresponding values.
 *
 *	@package	GEO
 *	@subpackage	Framework
 */
class CBilFilesIterator implements Iterator
{
	/**
	 * <b>Seconds</b>
	 *
	 * This data member holds the tile size in seconds.
	 *
	 * @var integer
	 */
	private $mSeconds = NULL;

	/**
	 * <b>Origin</b>
	 *
	 * This data member holds the first tile vertex.
	 *
	 * @var array
	 */
	private $mOrigin = Array();

	/**
	 * <b>Buffer tiles</b>
	 *
	 * This data member holds the buffer number of tiles.
	 *
	 * @var integer
	 */
	private $mBufTiles = NULL;

	/**
	 * <b>Skip</b>
	 *
	 * This data member holds the number of tiles to skip.
	 *
	 * @var integer
	 */
	private $mSkip = NULL;

	/**
	 * <b>Cols</b>
	 *
	 * This data member holds the number of columns.
	 *
	 * @var integer
	 */
	private $mCols = NULL;

	/**
	 * <b>Rows</b>
	 *
	 * This data member holds the number of rows.
	 *
	 * @var integer
	 */
	private $mRows = NULL;

	/**
	 * <b>Files</b>
	 *
	 * This data member will hold the list of <tt>.bil</tt> files and their information,
	 * each element is structured as follows:
	 *
	 * <ul>
	 *	<li><tt>{@link kFILE_PATH}</tt>: Path to the file.
	 *	<li><tt>{@link kFILE_POINTER}</tt>: File pointer.
	 *	<li><tt>{@link kFILE_BANDS}</tt>: Number of bands per tile.
	 *	<li><tt>{@link kFILE_BPACK}</tt>: Number of bytes per band.
	 *	<li><tt>{@link kFILE_NODATA}</tt>: No data value.
	 *	<li><tt>{@link kFILE_BUFFER}</tt>: Read buffer.
	 * </ul>
	 *
	 * @var array
	 */
	private $mFiles = Array();

	/**
	 * <b>Row</b>
	 *
	 * Current row index.
	 *
	 * @var integer
	 */
	private $mRow = 0;

	/**
	 * <b>Column</b>
	 *
	 * Current column index.
	 *
	 * @var integer
	 */
	private $mCol = 0;

	/**
	 * <b>Tiles index</b>
	 *
	 * Current tile index.
	 *
	 * @var integer
	 */
	private $mTileIndex = 0;

	/**
	 * <b>Tiles count</b>
	 *
	 * Current tiles count.
	 *
	 * @var integer
	 */
	private $mTilesCount = 0;

		

/*=======================================================================================
 *																						*
 *										MAGIC											*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	__construct																		*
	 *==================================================================================*/

	/**
	 * Instantiate class.
	 *
	 * The constructor expects the following parameters:
	 *
	 * <ul>
	 *	<li><b>$theSeconds</b>: The tile size in seconds.
	 *	<li><b>$theBuffer</b>: Buffer tiles count.
	 *	<li><b>$theOrigin</b>: The vertex coordinates of the first tile in degrees,
	 *		expressed as longitude and latitude.
	 *	<li><b>$theRange</b>: The longitude and latitude range in degrees.
	 *	<li><b>$theStart</b>: The absolute tile position from which to start reading:
	 *		the value is zero based.
	 * </ul>
	 *
	 * The provided values will be set in the object and cannot be changed: these determine
	 * the size and structure of all the files that the object can handle.
	 *
	 * Example:
	 * <ul>
	 *	<li><tt>$theSeconds = 30;</tt>.
	 *	<li><tt>$theBuffer = 4320;</tt>.
	 *	<li><tt>$theOrigin = array( -180, 90 );</tt>.
	 *	<li><tt>$theRange = array( 360, 150 );</tt>.
	 *	<li><tt>$theStart = 2500;</tt>.
	 * </ul>
	 * This corresponds to a map with 43200 horizontal and 18000 vertical tiles, with data
	 * starting at tile 2501.
	 *
	 * @param integer				$theSeconds			Size of tile in seconds.
	 * @param integer				$theBuffer			Buffer tiles count.
	 * @param array					$theOrigin			Origin coordinate in degrees.
	 * @param array					$theRange			Longitude and latitude range.
	 * @param integer				$theStart			First tile to read.
	 */
	public function __construct( $theSeconds, $theBuffer, $theOrigin, $theRange,
								 $theStart = 0 )
	{
		//
		// Check seconds.
		//
		if( is_int( $theSeconds ) )
		{
			//
			// Set seconds.
			//
			$this->mSeconds = (int) $theSeconds;
			
			//
			// Check buffer tiles count.
			//
			if( is_int( $theBuffer ) )
			{
				//
				// Set buffer tiles count.
				//
				$this->mBufTiles = (int) $theBuffer;
			
				//
				// Check origin.
				//
				if( is_array( $theOrigin ) )
				{
					//
					// Check elements.
					//
					if( count( $theOrigin ) == 2 )
					{
						//
						// Cast.
						//
						$theOrigin[ 0 ] = (int) $theOrigin[ 0 ];
						$theOrigin[ 1 ] = (int) $theOrigin[ 1 ];
					
						//
						// Set.
						//
						$this->mOrigin = $theOrigin;
				
					} // Correct origin count.
				
					else
						throw new Exception
							( "Invalid tile origin value: "
							 ."expecting an array of two elements." );			// !@! ==>
			
				} // Correct origin type.
			
				else
					throw new Exception
						( "Invalid tile origin value: "
						 ."expecting an array." );								// !@! ==>
			
				//
				// Check range.
				//
				if( is_array( $theRange ) )
				{
					//
					// Check elements.
					//
					if( count( $theRange ) == 2 )
					{
						//
						// Cast.
						//
						$theRange[ 0 ] = (int) $theRange[ 0 ];
						$theRange[ 1 ] = (int) $theRange[ 1 ];
				
					} // Correct origin count.
				
					else
						throw new Exception
							( "Invalid tile range value: "
							 ."expecting an array of two elements." );			// !@! ==>
			
				} // Correct range type.
			
				else
					throw new Exception
						( "Invalid tile range value: "
						 ."expecting an array." );								// !@! ==>
			
				//
				// Set grid.
				//
				$this->mCols = ($theRange[ 0 ] * 3600) / $this->mSeconds;
				$this->mRows = ($theRange[ 1 ] * 3600) / $this->mSeconds;
				
				//
				// Set start.
				//
				$this->mSkip = (int) $theStart;
			
			} // Integer buffer tiles.
		
			else
				throw new Exception
					( "Invalid buffer tiles count: "
					 ."expecting an integer." );								// !@! ==>
			
		} // Integer seconds.
		
		else
			throw new Exception
				( "Invalid tile seconds value: "
				 ."expecting an integer." );									// !@! ==>

	} // Constructor.

	 
	/*===================================================================================
	 *	__destruct																		*
	 *==================================================================================*/

	/**
	 * Release object.
	 *
	 * The destructor will clean up and release the object.
	 */
	public function __destruct()
	{
		//
		// Close files.
		//
		while( ($file = array_shift( $this->mFiles )) !== NULL )
		{
			if( $file[ kFILE_POINTER ] !== NULL )
				fclose( $file[ kFILE_POINTER ] );
		}

	} // Destructor.

		

/*=======================================================================================
 *																						*
 *							PUBLIC DATA MEMBER ACCESSOR METHODS							*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	Seconds																			*
	 *==================================================================================*/

	/**
	 * Seconds.
	 *
	 * This method can be used to retrieve the tile size in seconds, this value is set at
	 * instantiation time and is immutable.
	 *
	 * @access public
	 * @return integer
	 */
	public function Seconds()									{	return $this->mSeconds;	}

	 
	/*===================================================================================
	 *	Columns																			*
	 *==================================================================================*/

	/**
	 * Columns.
	 *
	 * This method can be used to retrieve the number of horizontal tiles of the map, this
	 * value is set at instantiation time and is immutable.
	 *
	 * @access public
	 * @return integer
	 */
	public function Columns()									{	return $this->mCols;	}

	 
	/*===================================================================================
	 *	Rows																			*
	 *==================================================================================*/

	/**
	 * Rows.
	 *
	 * This method can be used to retrieve the number of vertical tiles of the map, this
	 * value is set at instantiation time and is immutable.
	 *
	 * @access public
	 * @return integer
	 */
	public function Rows()										{	return $this->mRows;	}

	 
	/*===================================================================================
	 *	FileSet																			*
	 *==================================================================================*/

	/**
	 * Set a file entry.
	 *
	 * It accepts six parameters:
	 *
	 * <ul>
	 *	<li><b>$theKey</b>: File index.
	 *	<li><b>$thePath</b>: File path; if you provide <tt>FALSE<tt> in this parameter, it
	 *		means that you want to delete the entry and all subsequent parameters will be
	 *		ignored.
	 *	<li><b>$theBands</b>: Number of items per tile.
	 *	<li><b>$theKind</b>: The type of the band elements:
	 *	 <ul>
	 *		<li><tt>c</tt>: signed char
	 *		<li><tt>C</tt>: unsigned char
	 *		<li><tt>s</tt>: signed short (always 16 bit, machine byte order)
	 *		<li><tt>S</tt>: unsigned short (always 16 bit, machine byte order)
	 *		<li><tt>n</tt>: unsigned short (always 16 bit, big endian byte order)
	 *		<li><tt>v</tt>: unsigned short (always 16 bit, little endian byte order)
	 *		<li><tt>i</tt>: signed integer (machine dependent size and byte order)
	 *		<li><tt>I</tt>: unsigned integer (machine dependent size and byte order)
	 *		<li><tt>l</tt>: signed long (always 32 bit, machine byte order)
	 *		<li><tt>L</tt>: unsigned long (always 32 bit, machine byte order)
	 *		<li><tt>N</tt>: unsigned long (always 32 bit, big endian byte order)
	 *		<li><tt>V</tt>: unsigned long (always 32 bit, little endian byte order)
	 *	 </ul>
	 *	<li><b>$theNull</b>: Value used to indicate no data.
	 *	<li><b>$theTrans</b>: Eventual translation table for controlled vocabularies: the
	 *		value must be an array in which the key corresponds to the value read from the
	 *		file and the value is the replacement data.
	 * </ul>
	 *
	 * The method will set the files list member and return the entry.
	 *
	 * @param string				$theKey				File key.
	 * @param mixed					$thePath			File path.
	 * @param integer				$theBands			Bands count.
	 * @param integer				$theKind			Bands kind.
	 * @param integer				$theNull			No data value.
	 * @param array					$theTrans			Replacement table.
	 *
	 * @access public
	 * @return array
	 *
	 * @uses _ManageArrayMember()
	 */
	public function FileSet( $theKey,
							 $thePath,
							 $theBands = 1,
							 $theKind = 's',
							 $theNull = -9999,
							 $theTrans = NULL )
	{
		//
		// Delete entry.
		//
		if( $thePath === FALSE )
		{
			//
			// Check key.
			//
			if( array_key_exists( $theKey, $this->mFiles ) )
			{
				$save = $this->mFiles[ $theKey ];
				unset( $this->mFiles[ $theKey ] );
				
				return $save;														// ==>
			
			} // Found entry.
			
			return NULL;															// ==>
		
		} // Delete entry.
		
		//
		// Check file.
		//
		if( file_exists( (string) $thePath ) )
		{
			//
			// Check if readable.
			//
			if( is_readable( (string) $thePath ) )
			{
				//
				// Check band kind.
				//
				switch( (string) $theKind )
				{
					case 'c':
					case 'C':
						$bytes = 1;
						break;
						
					case 's':
					case 'S':
					case 'n':
					case 'v':
						$bytes = 2;
						break;
						
					case 'l':
					case 'L':
					case 'N':
					case 'V':
						$bytes = 4;
						break;
					
					default:
						throw new Exception
							( "Invalid band data type: [$theKind]." );			// !@! ==>
				}
				
				//
				// Init local storage.
				//
				$file = Array();
				$thePath = realpath( (string) $thePath );
				
				//
				// Load reference.
				//
				$file[ kFILE_PATH ] = (string) $thePath;
				$file[ kFILE_BANDS ] = (int) $theBands;
				$file[ kFILE_BPACK ] = (string) $theKind;
				$file[ kFILE_BSIZE ] = $bytes;
				$file[ kFILE_NODATA ] = $theNull;
				$file[ kFILE_TRANS ] = $theTrans;
				$file[ kFILE_POINTER ] = NULL;
				$file[ kFILE_BUFFER ] = Array();
				
				//
				// Set element.
				//
				$this->mFiles[ (string) $theKey ] = $file;
				
				return $file;														// ==>
			
			} // File is readable.
			
			else
				throw new Exception
					( "Invalid file reference: "
					 ."File is not readable [$thePath]." );						// !@! ==>
		
		} // File exists.
		
		else
			throw new Exception
				( "Invalid file reference: "
				 ."File does not exist [$thePath]." );							// !@! ==>
	
	} // FileSet.

	 
	/*===================================================================================
	 *	FileGet																			*
	 *==================================================================================*/

	/**
	 * Retrieve a file entry.
	 *
	 * It accepts a single parameter representing the file index and returns the file entry,
	 * if found, or <tt>NULL</tt>.
	 *
	 * @param string				$theKey				File key.
	 *
	 * @access public
	 * @return array
	 *
	 * @uses _ManageArrayMember()
	 */
	public function FileGet( $theKey )
	{
		return ( array_key_exists( $theKey, $this->mFiles ) )
			 ? $this->mFiles[ $theKey ]												// ==>
			 : NULL;																// ==>
	
	} // FileGet.

	 
	/*===================================================================================
	 *	Column																			*
	 *==================================================================================*/

	/**
	 * Return the current column.
	 *
	 * This method can be used to retrieve the current tile column.
	 *
	 * @access public
	 * @return integer
	 */
	public function Column()										{	return $this->mCol;	}

	 
	/*===================================================================================
	 *	Row																				*
	 *==================================================================================*/

	/**
	 * Return the current row.
	 *
	 * This method can be used to retrieve the current tile row.
	 *
	 * @access public
	 * @return integer
	 */
	public function Row()											{	return $this->mRow;	}

	 
	/*===================================================================================
	 *	Longitude																		*
	 *==================================================================================*/

	/**
	 * Return the current tile longitude.
	 *
	 * This method can be used to retrieve the current tile longitude, the provided
	 * reference will receive the decimal degrees value, while the method will return the
	 * degrees, minutes and seconds value.
	 *
	 * @param reference			   &$theDegrees			Receives decimal degrees.
	 *
	 * @access public
	 * @return string
	 */
	public function Longitude( &$theDegrees )
	{
		//
		// Get degrees.
		//
		$degrees
			= $this->_Degrees(
				$theDegrees, $this->mOrigin[ 0 ], $this->mCol, $this->mSeconds / 2 );
		
		return ( $theDegrees < 0 )
			 ? abs($degrees[ 0 ]).'°'.$degrees[ 1 ]."'".$degrees[ 2 ].'"W'			// ==>
			 : abs($degrees[ 0 ]).'°'.$degrees[ 1 ]."'".$degrees[ 2 ].'"E';			// ==>
	
	} // Longitude.

	 
	/*===================================================================================
	 *	Latitude																		*
	 *==================================================================================*/

	/**
	 * Return the current tile latitude.
	 *
	 * This method can be used to retrieve the current tile latitude, the provided
	 * reference will receive the decimal degrees value, while the method will return the
	 * degrees, minutes and seconds value.
	 *
	 * @param reference			   &$theDegrees			Receives decimal degrees.
	 *
	 * @access public
	 * @return string
	 */
	public function Latitude( &$theDegrees )
	{
		//
		// Get degrees.
		//
		$degrees
			= $this->_Degrees(
				$theDegrees, $this->mOrigin[ 1 ], $this->mRow, $this->mSeconds / 2 );
		
		return ( $theDegrees < 0 )
			 ? abs($degrees[ 0 ]).'°'.$degrees[ 1 ]."'".$degrees[ 2 ].'"S'			// ==>
			 : abs($degrees[ 0 ]).'°'.$degrees[ 1 ]."'".$degrees[ 2 ].'"N';			// ==>
	
	} // Latitude.

	 
	/*===================================================================================
	 *	Bounds																			*
	 *==================================================================================*/

	/**
	 * Return the current tile bounds.
	 *
	 * This method can be used to retrieve the current tile bounds, the provided
	 * reference will receive the decimal degrees values, while the method will return the
	 * degrees, minutes and seconds values.
	 *
	 * The returned data will be in the form of a rect where the first point will be the
	 * top-left vertex and the second point the bottom-right vertex.
	 *
	 * @param reference			   &$theDegrees			Receives decimal degrees.
	 *
	 * @access public
	 * @return string
	 */
	public function Bounds( &$theDegrees )
	{
		//
		// Get left.
		//
		$left_dms
			= $this->_Degrees(
				$left_dec, $this->mOrigin[ 0 ], $this->mCol );
		$left_dms = ( $left_dec < 0 )
				  ? abs($left_dms[ 0 ]).'°'.$left_dms[ 1 ]."'".$left_dms[ 2 ].'"W'
				  : abs($left_dms[ 0 ]).'°'.$left_dms[ 1 ]."'".$left_dms[ 2 ].'"E';
		
		//
		// Get right.
		//
		$right_dms
			= $this->_Degrees(
				$right_dec, $this->mOrigin[ 0 ], $this->mCol, $this->mSeconds );
		$right_dms = ( $right_dec < 0 )
				   ? abs($right_dms[ 0 ]).'°'.$right_dms[ 1 ]."'".$right_dms[ 2 ].'"W'
				   : abs($right_dms[ 0 ]).'°'.$right_dms[ 1 ]."'".$right_dms[ 2 ].'"E';
		
		//
		// Get top.
		//
		$top_dms
			= $this->_Degrees(
				$top_dec, $this->mOrigin[ 1 ], $this->mRow );
		$top_dms = ( $top_dec < 0 )
				 ? abs($top_dms[ 0 ]).'°'.$top_dms[ 1 ]."'".$top_dms[ 2 ].'"S'
				 : abs($top_dms[ 0 ]).'°'.$top_dms[ 1 ]."'".$top_dms[ 2 ].'"N';
		
		//
		// Get bottom.
		//
		$bot_dms
			= $this->_Degrees(
				$bot_dec, $this->mOrigin[ 1 ], $this->mRow, $this->mSeconds );
		$bot_dms = ( $bot_dec < 0 )
				 ? abs($bot_dms[ 0 ]).'°'.$bot_dms[ 1 ]."'".$bot_dms[ 2 ].'"S'
				 : abs($bot_dms[ 0 ]).'°'.$bot_dms[ 1 ]."'".$bot_dms[ 2 ].'"N';
		
		//
		// Set decimal rect.
		//
		$theDegrees = array( array( $left_dec, $top_dec ), array( $right_dec, $bot_dec ) );
		
		return array( array( $left_dms, $top_dms ),
					  array( $right_dms, $bot_dms ) );								// ==>
	
	} // Bounds.

		

/*=======================================================================================
 *																						*
 *								PUBLIC INTERFACE METHODS								*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	rewind																			*
	 *==================================================================================*/

	/**
	 * rewind.
	 *
	 * Rewind to beginning.
	 *
	 * @access public
	 */
	public function rewind()
	{
		//
		// Reset position.
		//
		$this->mRow = floor( $this->mSkip / $this->mCols );
		$this->mCol = $this->mSkip - ( $this->mRow * $this->mCols );

		//
		// Reset position.
		//
		$this->mTileIndex = $this->mTilesCount = 0;
		
		//
		// Rewind files.
		//
		$this->_RewindFiles();
		
		//
		// Skip tiles.
		//
		if( $this->mSkip )
		{
			//
			// Force load buffers.
			// This is to correct the tile index within the buffer
			// only once at rewind time.
			//
			$this->_LoadBuffers();
			
			//
			// Set buffer tile index.
			//
			$this->mTileIndex
				= $this->mSkip - ( floor( $this->mSkip / $this->mBufTiles )
								 * $this->mBufTiles );
		
		} // Provided skip value.
	
	} // rewind.

	 
	/*===================================================================================
	 *	valid																			*
	 *==================================================================================*/

	/**
	 * valid.
	 *
	 * Load buffers if needed or return <tt>FALSE</tt> if the end of the files was reached.
	 *
	 * @access public
	 * @return boolean
	 */
	public function valid()
	{
		//
		// Load buffer.
		//
		$this->_LoadBuffers();
		
		return ( $this->mTilesCount > 0 );											// ==>
	
	} // valid.

	 
	/*===================================================================================
	 *	current																			*
	 *==================================================================================*/

	/**
	 * current.
	 *
	 * Return current element.
	 *
	 * The method will iterate all files
	 *
	 * @access public
	 * @return array
	 */
	public function current()
	{
		//
		// Init local storage.
		//
		$first = TRUE;
		$data = Array();
		
		//
		// Iterate all files.
		//
		$keys = array_keys( $this->mFiles );
		foreach( $keys as $key )
		{
			//
			// Init local references.
			//
			$file = & $this->mFiles[ $key ];
			$buffer = & $file[ kFILE_BUFFER ];
			$tile = $buffer[ $this->mTileIndex ];
			
			//
			// Handle multiband.
			//
			if( is_array( $tile ) )
			{
				//
				// Reindex and check values.
				//
				$i = 1;
				foreach( $tile as $index => $item )
				{
					//
					// Check for no-data.
					//
					if( ($item === $file[ kFILE_NODATA ])
					 || ($item === NULL) )
						continue;											// =>
					
					//
					// Handle translation table.
					//
					if( is_array( $file[ kFILE_TRANS ] ) )
					{
						if( array_key_exists( $item, $file[ kFILE_TRANS ] ) )
							$data[ $key ][ $i ] = $file[ kFILE_TRANS ][ $item ];
					}
					
					//
					// Set data.
					//
					else
						$data[ $key ][ $i ] = $item;
				
				} // Iterating tile elements.
						
			} // Multiband.
			
			//
			// Handle single band.
			//
			else
			{
				//
				// Check for no-data.
				//
				if( ($tile === $file[ kFILE_NODATA ])
				 || ($tile === NULL) )
					continue;												// =>
				
				//
				// Handle translation table.
				//
				if( is_array( $file[ kFILE_TRANS ] ) )
				{
					if( array_key_exists( $tile, $file[ kFILE_TRANS ] ) )
						$data[ $key ] = $file[ kFILE_TRANS ][ $tile ];
				}
				
				//
				// Set data.
				//
				else
					$data[ $key ] = $tile;
			
			} // Singleband.
			
			//
			// Skip other files if first has no data.
			//
			if( kENV_CHECK_ONLY_FIRST
			 && $first
			 && (! count( $data )) )
				break;
			
			//
			// Reset first file flag.
			//
			$first = FALSE;
		
		} // Iterating files.
		
		return $data;																// ==>
	
	} // current.

	 
	/*===================================================================================
	 *	key																				*
	 *==================================================================================*/

	/**
	 * key.
	 *
	 * Return the key of the current element.
	 *
	 * In this class we return the absolute tile index.
	 *
	 * @access public
	 * @return integer
	 */
	public function key()
	{
		return (int) ($this->mRow * $this->mCols) + ($this->mCol + 1);				// ==>
	
	} // key.

	 
	/*===================================================================================
	 *	next																			*
	 *==================================================================================*/

	/**
	 * next.
	 *
	 * Move forward to next element.
	 *
	 * @access public
	 */
	public function next()
	{
		//
		// Increment tile index.
		//
		$this->mTileIndex++;
		
		//
		// Increment grid index.
		//
		if( ++$this->mCol >= $this->mCols )
		{
			$this->mCol = 0;
			$this->mRow++;
		}
	
	} // next.

		

/*=======================================================================================
 *																						*
 *								PROTECTED ITERATION INTERFACE							*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	_RewindFiles																	*
	 *==================================================================================*/

	/**
	 * Rewind files.
	 *
	 * This method will open all unopened files, or rewind and clear the buffers of all open
	 * files.
	 *
	 * Note that the file will be rewinded to the buffer in which the starting tile is to be
	 * read.
	 *
	 * @access protected
	 */
	protected function _RewindFiles()
	{
		//
		// Iterate all files.
		//
		$keys = array_keys( $this->mFiles );
		foreach( $keys as $key )
		{
			//
			// Point to file.
			//
			$file = & $this->mFiles[ $key ];
			
			//
			// Open file.
			//
			if( $file[ kFILE_POINTER ] === NULL )
				$file[ kFILE_POINTER ] = fopen( $file[ kFILE_PATH ], 'rb' );
			
			//
			// Rewind file.
			//
			else
			{
				//
				// Rewind file pointer.
				//
				rewind( $file[ kFILE_POINTER ] );
				
				//
				// Reset file buffer.
				//
				$file[ kFILE_BUFFER ] = Array();
			
			} // File was open.
			
			//
			// Skip tiles.
			//
			if( $this->mSkip )
			{
				//
				// Calculate skip offset.
				//
				$offset = floor( $this->mSkip / $this->mBufTiles );
				if( $offset )
					fseek( $file[ kFILE_POINTER ], $offset
												 * $this->mBufTiles
												 * $file[ kFILE_BANDS ]
												 * $file[ kFILE_BSIZE ] );
			
			} // SKip tiles.
		
		} // Iterating files.
	
	} // _RewindFiles.

	 
	/*===================================================================================
	 *	_LoadBuffers																	*
	 *==================================================================================*/

	/**
	 * Load buffers.
	 *
	 * This method will load all file buffers at the current position
	 *
	 * All buffers will be cleared and the global file position will be reset to zero.
	 *
	 * @access protected
	 */
	protected function _LoadBuffers()
	{
		//
		// Check if needed.
		//
		if( $this->mTileIndex >= $this->mTilesCount )
		{
			//
			// Set current tile indexes.
			//
			$this->mTileIndex = $this->mTilesCount = 0;
			
			//
			// Iterate all files.
			//
			$keys = array_keys( $this->mFiles );
			foreach( $keys as $key )
			{
				//
				// Point to file.
				//
				$file = & $this->mFiles[ $key ];
			
				//
				// Init local storage.
				//
				$file[ kFILE_BUFFER ] = Array();
				$bytes = $this->mBufTiles * $file[ kFILE_BANDS ] * $file[ kFILE_BSIZE ];
			
				//
				// Read data.
				//
				$data = fread( $file[ kFILE_POINTER ], $bytes );
				
				//
				// Unpack single band to buffer.
				//
				if( $file[ kFILE_BANDS ] == 1 )
					$file[ kFILE_BUFFER ]
						= array_values(
							unpack( $file[ kFILE_BPACK ].'*', $data ) );
				
				//
				// Unpack multiple band tiles.
				//
				else
				{
					//
					// Iterate tiles.
					//
					for( $pos = 0,
						 $chunk = $file[ kFILE_BANDS ] * $file[ kFILE_BSIZE ];
							$pos < strlen( $data );
								$pos += $chunk )
					{
						//
						// Get band.
						//
						$band = substr( $data, $pos, $chunk );
					
						//
						// Pack band.
						//
						$file[ kFILE_BUFFER ][] = unpack( $file[ kFILE_BPACK ].'*', $band );
				
					} // Iterating tiles.
				
				} // Multiband tile.
				
				//
				// Set tiles count.
				//
				if( ! $this->mTilesCount )
					$this->mTilesCount = count( $file[ kFILE_BUFFER ] );
		
			} // Iterating files.
		
		} // Reached end of buffer.
			
	} // _LoadBuffers.

		

/*=======================================================================================
 *																						*
 *							PROTECTED COORDINATES INTERFACE								*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	_Degrees																		*
	 *==================================================================================*/

	/**
	 * Return degrees.
	 *
	 * This method will return a value in decimal degrees and degrees, minutes and seconds.
	 *
	 * The method accepts the following parameters:
	 *
	 * <ul>
	 *	<li><b>&$theDegrees</b>: The reference will receive the decimal degrees value.
	 *	<li><b>$theOrigin</b>: Coordinate origin in degrees.
	 *	<li><b>$theIndex</b>: The current row or column.
	 *	<li><b>$theDelta</b>: Eventual offset in seconds.
	 * </ul>
	 *
	 * @param reference			   &$theDegrees			Receives decimal degrees.
	 * @param integer				$theOrigin			Coordinate origin in degrees.
	 * @param integer				$theIndex			Row or column index.
	 * @param integer				$theDelta			Delta seconds.
	 *
	 * @access protected
	 * @return array
	 */
	protected function _Degrees( &$theDegrees, $theOrigin, $theIndex, $theDelta = 0 )
	{
		//
		// Get absolute seconds.
		//
		$seconds = ( $theOrigin > 0 )
				 ? (((($theOrigin * 3600) - $theDelta) / $this->mSeconds) - $theIndex)
				  * $this->mSeconds
				 : (((($theOrigin * 3600) + $theDelta) / $this->mSeconds) + $theIndex)
				  * $this->mSeconds;
		
		//
		// Convert to decimal degrees.
		//
		$theDegrees = $seconds / 3600;
		
		//
		// Get degrees.
		//
		$degrees = ( $seconds >= 0 )
				 ? floor( $theDegrees )
				 : ceil( $theDegrees );
		
		//
		// Get minutes.
		//
		$seconds -= ($degrees * 3600);
		$minutes = floor( abs( $seconds / 60 ) );
		
		//
		// Get seconds.
		//
		$seconds = abs( $seconds ) - ($minutes * 60);
		
		return array( $degrees, $minutes, $seconds );								// ==>
			
	} // _Degrees.

	 

} // class CBilFilesIterator.


?>
