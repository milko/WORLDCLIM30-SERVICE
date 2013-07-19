<?php

/**
 * <i>CBilReader</i> class definition.
 *
 * This file contains the class definition of <b>CBilReader</b> which represents a
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
 *									CBilReader.php										*
 *																						*
 *======================================================================================*/

/**
 * Class definitions.
 *
 * This include file contains all class definitions.
 */
require_once( "CBilReader.inc.php" );

/**
 * <h4><tt>.bil</tt> file reader</h4>
 *
 * The main duty of this class is to open a series of <tt>.bil</tt> files and read
 * sequentially from all of them returning the values in a specific order.
 *
 *	@package	GEO
 *	@subpackage	Framework
 */
class CBilReader implements Iterator
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
	 *	<li><tt>{@link kFILE_SIGNED}</tt>: Signed value flag.
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
	 * </ul>
	 * This corresponds to a map with 43200 horizontal and 18000 vertical tiles.
	 *
	 * @param integer				$theSeconds			Size of tile in seconds.
	 * @param integer				$theBuffer			Buffer tiles count.
	 * @param array					$theOrigin			Origin coordinate in degrees.
	 * @param array					$theRange			Longitude and latitude range.
	 */
	public function __construct( $theSeconds, $theBuffer, $theOrigin, $theRange )
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
	 *	<li><b>$isSigned</b>: Flag indicating whether the values are signed or not.
	 * </ul>
	 *
	 * The method will set the files list member and return the entry.
	 *
	 * @param string				$theKey				File key.
	 * @param mixed					$thePath			File path.
	 * @param integer				$theBands			Bands count.
	 * @param integer				$theKind			Bands kind.
	 * @param integer				$theNull			No data value.
	 * @param boolean				$isSigned			TRUE means signed value.
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
							 $isSigned = TRUE )
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
				$file[ kFILE_SIGNED ] = (boolean) $isSigned;
				$file[ kFILE_NODATA ] = $theNull;
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
	 * This method can be used to retrieve the current tile longitude, if you provide
	 * <tt>TRUE</tt> in the provided parameter, the method will return the value in
	 * degrees, minutes and seconds expressed as an array of four elements in which the
	 * first is the degrees, the second is the minutes, the third is the seconds and the
	 * fourth the hemisphere.
	 *
	 * @param boolean				$doDMS				TRUE means return DMS.
	 *
	 * @access public
	 * @return mixed
	 */
	public function Longitude()
	{
		//
		// Get longitude.
		//
		$lon = $this->_Longitude();
		
		$lon[] = $this->mCol;
		
		return $lon;
	
	} // Longitude.

		

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
		$this->mRow = $this->mCol =
		$this->mTileIndex = $this->mTilesCount = 0;
		
		//
		// Rewind files.
		//
		$this->_RewindFiles();
	
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
	 * @access public
	 * @return array
	 */
	public function current()
	{
		//
		// Iterate until a tile with data is found.
		//
		while( ! count( $data = $this->_LoadCurrent() ) )
		{
			$this->next();
			$this->valid();
		}
		
		//
		// Set coordinates.
		//
		
		
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
			// Init global storage.
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

	 
	/*===================================================================================
	 *	_LoadCurrent																	*
	 *==================================================================================*/

	/**
	 * Load current element.
	 *
	 * This method will return the current element skipping all non valid data.
	 * If the method returns an empty array, it means that no valid data was found.
	 *
	 * This method is used by {@link current()} to return the first tile that contains at
	 * least one valid data element.
	 *
	 * @access protected
	 * @return array
	 */
	protected function _LoadCurrent()
	{
		//
		// Init local storage.
		//
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
			// Handle multi band.
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
					if( $item != $file[ kFILE_NODATA ] )
						$data[ $key ][ $i ]
							= & $tile[ $index ];
					
					//
					// Iterate.
					//
					$i++;
				
				} // Iterating tile elements.
			
			} // Multiband.
			
			//
			// Handle single band.
			//
			elseif( $tile != $file[ kFILE_NODATA ] )
				$data[ $key ]
					= & $tile;
		
		} // Iterating files.
		
		return $data;																// ==>
			
	} // _LoadCurrent.

	 
	/*===================================================================================
	 *	_Longitude																		*
	 *==================================================================================*/

	/**
	 * Return current longitude.
	 *
	 * This method will return the current longitude as an array og three elements: the
	 * degrees in which negative values indicate the east, the minutes and the seconds.
	 *
	 * The method accepts a parameter which can be used as a delta value expressed in
	 * seconds.
	 *
	 * @param integer				$theDelta			Delta seconds.
	 *
	 * @access protected
	 * @return array
	 */
	protected function _Longitude( $theDelta = 0 )
	{
		//
		// Get absolute seconds.
		//
		$position = (($this->mOrigin[ 0 ] * 3600) / $this->mSeconds)
				  + $this->mCol
//				  + $theDelta;
				  + 1;
echo( "$position<br>" );
		
		//
		// Get degrees.
		//
		$tmp = ($position * $this->mSeconds) / 3600;
		$degrees = ( $position >= 0 )
				 ? floor( $tmp )
				 : ceil( $tmp );
echo( "$degrees<br>" );
		
		//
		// Update position.
		//
		$position -= ($degrees * 3600);
echo( "$position<br>" );
exit;
		
		
		
		//
		// Init local storage.
		//
		$seconds = ($this->Column() * $this->mSeconds) + $theDelta;
		
		//
		// Calculate degrees.
		//
		$tmp = $seconds / 3600;
		$deg = ( $this->mOrigin[ 0 ] >= 0 )
			 ? $this->mOrigin[ 0 ] - floor( $tmp )
			 : $this->mOrigin[ 0 ] + ceil( $tmp );
		
		//
		// Set sign.
		//
		$sign = ( $this->mOrigin[ 0 ] >= 0 )
			  ? ( ($this->mOrigin[ 0 ] - $tmp) > 0 )
			  : ( ($this->mOrigin[ 0 ] + $tmp) > 0 );
		
		//
		// Update seconds.
		//
		$seconds -= (floor( $tmp ) * 3600);
		
		//
		// Calculate minutes.
		//
		$tmp = $seconds / 60;
		if( $this->mOrigin[ 0 ] > 0 )
			$min = floor( $tmp );
		elseif( $tmp )
			$min = 60 - ceil( $tmp );
		else
			$min = 0;
		
		//
		// Update seconds.
		//
		$seconds -= (floor( $tmp ) * 60);
		
		//
		// Calculate seconds.
		//
		$sec = ( $this->mOrigin[ 0 ] >= 0 )
			 ? $seconds
			 : $seconds;
		
		return array( $sign, $deg, $min, $sec );											// ==>
			
	} // _Longitude.

	 

} // class CBilReader.


?>
