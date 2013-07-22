<?php

/**
 * <i>CBilFilesReader</i> class definition.
 *
 * This file contains the class definition of <b>CBilFilesReader</b> which iterates through a
 * {@link CBaseBilIterator} excluding tiles that do not have any valid data.
 *
 *	@package	GEO
 *	@subpackage	Framework
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 20/07/2013
 */

/*=======================================================================================
 *																						*
 *									CBilFilesReader.php									*
 *																						*
 *======================================================================================*/

/**
 * Iterator class.
 *
 * This include file contains the iterator class.
 */
require_once( "CBilFilesIterator.php" );

/**
 * Class definitions.
 *
 * This include file contains current class definitions.
 */
require_once( "CBilFilesReader.inc.php" );

/**
 * <h4><tt>.bil</tt> file reader</h4>
 *
 * The main duty of this class is to open a series of <tt>.bil</tt> files and read
 * sequentially from all of them returning the corresponding values filtering all elements
 * that have no data: only tiles with data will be returned.
 *
 *	@package	GEO
 *	@subpackage	Framework
 */
class CBilFilesReader extends FilterIterator
{
		

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
	 * We overload the constructor to assert that the iterator is a {@link CBilFilesIterator}.
	 *
	 * @param CBilFilesIterator		$theIterator		Iterator instance.
	 */
	public function __construct( CBilFilesIterator $theIterator )
	{
		//
		// Call parent constructor.
		//
		parent::__construct( $theIterator );

	} // Constructor.

		

/*=======================================================================================
 *																						*
 *								PUBLIC INTERFACE METHODS								*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	accept																			*
	 *==================================================================================*/

	/**
	 * accept.
	 *
	 * Check if current element is valid.
	 *
	 * @access public
	 * @return boolean
	 */
	public function accept()
	{
		return ( count( parent::current() ) > 0 );									// ==>
	
	} // accept.

	 
	/*===================================================================================
	 *	current																			*
	 *==================================================================================*/

	/**
	 * current.
	 *
	 * Return current element.
	 *
	 * We overload this method to add coordinate information if the data is valid.
	 *
	 * @access public
	 * @return array
	 */
	public function current()
	{
		//
		// Get data.
		//
		$data = parent::current();
		
		//
		// Add coordinate information.
		//
		if( count( $data ) )
		{
			//
			// Load coordinates.
			//
			$lat_dms = $this->Latitude( $lat_dec );
			$lon_dms = $this->Longitude( $lon_dec );
			$dms = $this->Bounds( $dec );
			
			//
			// Set decimal coordinates.
			//
			$data[ kOFFSET_POINT ] = array( $lon_dec, $lat_dec );
			$data[ kOFFSET_BOX_DEC ] = $dec;
		
			//
			// Set tile coordinates.
			//
			$data[ kOFFSET_TILE ] = array( $this->Column(), $this->Row() );
			
			//
			// Set DMS coordinates.
			//
			$data[ kOFFSET_COORD ] = array( $lon_dms, $lat_dms );
			$data[ kOFFSET_BOX_DMS ] = $dms;
			
		} // Valid data.
		
		return $data;																// ==>
	
	} // current.

	 

} // class CBilFilesReader.


?>
