<?php

/**
 * <i>CWorldclimService</i> class definition.
 *
 * This file contains the class definition of <b>CWorldclimService</b> which represents a
 * web-service that can be used to query WORLDCLIM data.
 *
 *	@package	WORLDCLIM30
 *	@subpackage	Services
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 04/07/2013
 */

/*=======================================================================================
 *																						*
 *								CWorldclimService.php									*
 *																						*
 *======================================================================================*/

/**
 * Class definitions.
 *
 * This include file contains all class definitions.
 */
require_once( "CWorldclimService.inc.php" );

/**
 * <h4>WORLDCLIM web-service</h4>
 *
 * This class represents a web-service, it defines the parameter classes and the methods
 * that implement a WORLDCLIM 30 seconds service.
 *
 *	@package	WORLDCLIM30
 *	@subpackage	Services
 */
class CWorldclimService
{
	/**
	 * <b>Server</b>
	 *
	 * This data member will hold the server reference.
	 *
	 * @var MongoClient
	 */
	 protected $mServer = NULL;

	/**
	 * <b>Database</b>
	 *
	 * This data member will hold the database reference.
	 *
	 * @var mixed
	 */
	 protected $mDatabase = NULL;

	/**
	 * <b>Collection</b>
	 *
	 * This data member will hold the collection reference.
	 *
	 * @var mixed
	 */
	 protected $mCollection = NULL;

	/**
	 * <b>Command</b>
	 *
	 * This data member will hold the service command code.
	 *
	 * @var string
	 */
	 protected $mCommand = NULL;

	/**
	 * <b>Geometry</b>
	 *
	 * This data member will hold the service request geometry.
	 *
	 * @var array
	 */
	 protected $mGeometry = NULL;

	/**
	 * kTileDegs.
	 *
	 * This double value represents 15 seconds in decimal degrees.
	 *
	 * Type: double.
	 */
	const kTileDegs = 0.0041666666667;

		

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
	 * The constructor will set-up the environment by setting the eventual server, database
	 * and collection and parse the request collecting the service parameters.
	 *
	 * @param mixed					$theServer			Server reference.
	 * @param mixed					$theDatabase		Database reference.
	 * @param mixed					$theCollection		Collection reference.
	 */
	public function __construct( $theServer = NULL,
								 $theDatabase = NULL,
								 $theCollection = NULL )
	{
		//
		// Set server.
		//
		if( $theServer !== NULL )
			$this->Server( $theServer );

		//
		// Set database.
		//
		if( $theDatabase !== NULL )
			$this->Database( $theDatabase );

		//
		// Set collection.
		//
		if( $theCollection !== NULL )
			$this->Collection( $theCollection );
		
		//
		// Get command.
		//
		$this->mCommand = $this->_Command();
		switch( $this->mCommand )
		{
			case kAPI_OP_PING:
			case kAPI_OP_HELP:
				break;
			
			default:
				$this->mGeometry = $this->_Geometry();
				break;
		}

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

	} // Destructor.

		

/*=======================================================================================
 *																						*
 *							PUBLIC DATA MEMBER ACCESSOR METHODS							*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	Server																			*
	 *==================================================================================*/

	/**
	 * Manage server.
	 *
	 * This method can be used to set, retrieve and delete the server reference.
	 *
	 * It accepts two parameters:
	 *
	 * <ul>
	 *	<li><b>$theValue</b>: Value or operation:
	 *	 <ul>
	 *		<li><tt>NULL</tt>: Retrieve current value (DEFAULT).
	 *		<li><tt>MongoClient</tt>: Set with the provided value.
	 *		<li><i>other</i>: Any other value will be cast to a string and used to
	 *			instantiate the server.
	 *	 </ul>
	 *	<li><tt>$getOld</tt>: Which value to return:
	 *	 <ul>
	 *		<li><tt>TRUE</tt>: <i>Before</i> it was eventually modified.
	 *		<li><tt>FALSE</tt>: <i>After</i> it was eventually modified (DEFAULT).
	 *	 </ul>
	 * </ul>
	 *
	 * When providing a new value, if a database exists, it will be replaced by a
	 * reference to a database with the same name in the newly provided server.
	 *
	 * @param mixed					$theValue			New value or operation.
	 * @param boolean				$getOld				TRUE get old value.
	 *
	 * @access public
	 *
	 * @uses Database()
	 * @uses _ManageMember()
	 */
	public function Server( $theValue = NULL, $getOld = FALSE )
	{
		//
		// Handle new value.
		//
		if( $theValue !== NULL )
		{
			//
			// Instantiate server.
			//
			if( ! ($theValue instanceof MongoClient) )
				$theValue = new MongoClient( (string) $theValue );
			
			//
			// Handle database.
			//
			if( ($tmp = $this->Database()) !== NULL )
				$this->Database( $theValue->selectDB( (string) $tmp ) );
		
		} // New value.
		
		return $this->_ManageMember( $this->mServer, $theValue, $getOld );			// ==>
	
	} // Server.

	 
	/*===================================================================================
	 *	Database																		*
	 *==================================================================================*/

	/**
	 * Manage database.
	 *
	 * This method can be used to set, retrieve and delete the database reference.
	 *
	 * It accepts two parameters:
	 *
	 * <ul>
	 *	<li><b>$theValue</b>: Value or operation:
	 *	 <ul>
	 *		<li><tt>NULL</tt>: Retrieve current value (DEFAULT).
	 *		<li><tt>MongoDB</tt>: Set with the provided value.
	 *		<li><i>other</i>: Any other value will be cast to a string and used to
	 *			instantiate the database.
	 *	 </ul>
	 *	<li><tt>$getOld</tt>: Which value to return:
	 *	 <ul>
	 *		<li><tt>TRUE</tt>: <i>Before</i> it was eventually modified.
	 *		<li><tt>FALSE</tt>: <i>After</i> it was eventually modified (DEFAULT).
	 *	 </ul>
	 * </ul>
	 *
	 * If a database object is provided, the method will clear the eventual existing server.
	 * When providing a new value, if a collection exists, it will be replaced by a
	 * reference to a collection with the same name in the newly provided database.
	 * If a database name is provided and no server is set, the method will raise an
	 * exception.
	 *
	 * @param mixed					$theValue			New value or operation.
	 * @param boolean				$getOld				TRUE get old value.
	 *
	 * @access public
	 *
	 * @uses Server()
	 * @uses Collection()
	 * @uses _ManageMember()
	 */
	public function Database( $theValue = NULL, $getOld = FALSE )
	{
		//
		// Handle new value.
		//
		if( $theValue !== NULL )
		{
			//
			// Save server.
			//
			$server = $this->Server();
			
			//
			// Handle database reference.
			//
			if( $theValue instanceof MongoDB )
			{
				//
				// Handle server.
				//
				if( $server !== NULL )
					$this->mServer = NULL;
			
			} // Provided object.
		
			//
			// Instantiate database.
			//
			else
			{
				//
				// Check server.
				//
				if( $server !== NULL )
					$theValue = $server->selectDB( (string) $theValue );
				
				else
					throw new Exception
						( "Unable to set database reference: "
						 ."missing server reference." );						// !@! ==>
			
			} // Provided name.
			
			//
			// Handle existing collection.
			//
			if( ($tmp = $this->Collection()) !== NULL )
				$this->mCollection = $theValue->selectCollection( $tmp->getName() );
			
		} // New value.
		
		return $this->_ManageMember( $this->mDatabase, $theValue, $getOld );		// ==>
	
	} // Database.

	 
	/*===================================================================================
	 *	Collection																		*
	 *==================================================================================*/

	/**
	 * Manage collection.
	 *
	 * This method can be used to set, retrieve and delete the collection reference.
	 *
	 * It accepts two parameters:
	 *
	 * <ul>
	 *	<li><b>$theValue</b>: Value or operation:
	 *	 <ul>
	 *		<li><tt>NULL</tt>: Retrieve current value (DEFAULT).
	 *		<li><tt>MongoCollection</tt>: Set with the provided value.
	 *		<li><i>other</i>: Any other value will be cast to a string and used to
	 *			instantiate the collection.
	 *	 </ul>
	 *	<li><tt>$getOld</tt>: Which value to return:
	 *	 <ul>
	 *		<li><tt>TRUE</tt>: <i>Before</i> it was eventually modified.
	 *		<li><tt>FALSE</tt>: <i>After</i> it was eventually modified (DEFAULT).
	 *	 </ul>
	 * </ul>
	 *
	 * If a collection object is provided, the method will replace the eventual existing
	 * database reference with the collection's database.
	 * If a collection name is provided and no database is set, the method will raise an
	 * exception.
	 *
	 * @access public
	 *
	 * @param mixed					$theValue			New value or operation.
	 * @param boolean				$getOld				TRUE get old value.
	 *
	 * @uses Database()
	 * @uses _ManageMember()
	 */
	public function Collection( $theValue = NULL, $getOld = FALSE )
	{
		//
		// Handle new value.
		//
		if( $theValue !== NULL )
		{
			//
			// Handle collection reference.
			//
			if( $theValue instanceof MongoCollection )
			{
				//
				// Handle database.
				//
				if( $this->Database() !== NULL )
					$this->Database( $theValue->db );
			
			} // Provided object.
		
			//
			// Instantiate collection.
			//
			else
			{
				//
				// Check database.
				//
				if( ($tmp = $this->Database()) !== NULL )
					$theValue = $tmp->selectCollection( (string) $theValue );
				
				else
					throw new Exception
						( "Unable to set collection reference: "
						 ."missing database reference." );						// !@! ==>
			
			} // Provided name.
			
		} // New value.
		
		return $this->_ManageMember( $this->mCollection, $theValue, $getOld );		// ==>
	
	} // Collection.

		

/*=======================================================================================
 *																						*
 *								PUBLIC HANDLER INTERFACE								*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	HandleRequest																	*
	 *==================================================================================*/

	/**
	 * Handle request.
	 *
	 * This method will handle the request.
	 *
	 * @access public
	 *
	 * @uses Collection()
	 * @uses _Ping()
	 * @uses _Help()
	 * @uses _Command()
	 */
	public function HandleRequest()
	{
		//
		// Check if ready.
		//
		if( ($collection = $this->Collection()) !== NULL )
		{
			//
			// Check request.
			//
			if( isset( $_REQUEST )			// Has request
			 && count( $_REQUEST ) )		// and request is not empty.
			{
				//
				// Execute command.
				//
				switch( $this->mCommand )
				{
					case kAPI_OP_PING:
						$this->_RequestPing();
						break;

					case kAPI_OP_HELP:
						$this->_RequestHelp();
						break;

					case kAPI_OP_NEAR:
						$this->_RequestNear();
						break;

					case kAPI_OP_INTERSECT:
						$this->_RequestIntersect();
						break;

					case kAPI_OP_WITHIN:
						$this->_RequestWithin();
						break;
			
					default:
						throw new Exception
							( "Unable to handle request: "
							 ."unsupported operation [$this->mCommand]." );		// !@! ==>
				
				} // Parsing command.
			
			} // Provided request.
			
		} // Has collection reference (thus also database).
		
		else
			throw new Exception
				( "Unable to handle request: "
				 ."missing collection reference." );							// !@! ==>
	
	} // HandleRequest.

		

/*=======================================================================================
 *																						*
 *						PROTECTED DATA MEMBER ACCESSOR METHODS							*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	_ManageMember																	*
	 *==================================================================================*/

	/**
	 * Manage data members.
	 *
	 * This method can be used to handle a data member, it acccepts the following
	 * parameters:
	 *
	 * <ul>
	 *	<li><tt>&$theMember</tt>: Reference to the property being managed.
	 *	<li><tt>$theValue</tt>: The property value or operation:
	 *	 <ul>
	 *		<li><tt>NULL</tt>: Return the current property value.
	 *		<li><tt>FALSE</tt>: Reset the property to the default value (<tt>NULL</tt> is
	 *			the default).
	 *		<li><i>other</i>: Any other type represents the new value of the property.
	 *	 </ul>
	 *	<li><tt>$getOld</tt>: Determines what the method will return:
	 *	 <ul>
	 *		<li><tt>TRUE</tt>: Return the value of the property <i>before</i> it was
	 *			eventually modified.
	 *		<li><tt>FALSE</tt>: Return the value of the property <i>after</i> it was
	 *			eventually modified.
	 *	 </ul>
	 * </ul>
	 *
	 * @param reference			   &$theMember			Property reference.
	 * @param mixed					$theValue			Value or operation.
	 * @param boolean				$getOld				<tt>TRUE</tt> get old value.
	 *
	 * @access protected
	 */
	protected function _ManageMember( &$theMember, $theValue, $getOld )
	{
		//
		// Return current value.
		//
		if( $theValue === NULL )
			return $theMember;														// ==>

		//
		// Save current value.
		//
		$save = $theMember;
		
		//
		// Delete offset.
		//
		if( $theValue === FALSE )
			$theMember = NULL;
		
		//
		// Set offset.
		//
		else
			$theMember = $theValue;
		
		return ( $getOld ) ? $save													// ==>
						   : $theMember;											// ==>
	
	} // _ManageMember.

		

/*=======================================================================================
 *																						*
 *								PROTECTED HANDLER INTERFACE								*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	_RequestPing																	*
	 *==================================================================================*/

	/**
	 * Handle ping service.
	 *
	 * This method will simply respond <i>pong</i>.
	 *
	 * @access protected
	 */
	protected function _RequestPing()
	{
		exit( 'pong' );																// ==>
	
	} // _RequestPing.

	 
	/*===================================================================================
	 *	_RequestHelp																	*
	 *==================================================================================*/

	/**
	 * Handle help service.
	 *
	 * This method will return an HTML help page.
	 *
	 * @access protected
	 */
	protected function _RequestHelp()
	{
		exit( 'HELP!' );															// ==>
	
	} // _RequestHelp.

	 
	/*===================================================================================
	 *	_RequestWithin																	*
	 *==================================================================================*/

	/**
	 * Return elements within.
	 *
	 * This method will select all elements included in the provided geometry, or return
	 * all the element that contains the provided point.
	 *
	 * @access protected
	 *
	 * @uses _Point2Polygon()
	 */
	protected function _RequestWithin()
	{
		//
		// Transform to box.
		//
		if( $this->mGeometry[ 'type' ] == 'Point' )
			$this->mGeometry
				= $this->_Point2Polygon( $this->mGeometry );
	
	} // _RequestWithin.

		

/*=======================================================================================
 *																						*
 *							PROTECTED HANDLER PARSING INTERFACE							*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	_Command																		*
	 *==================================================================================*/

	/**
	 * Get command.
	 *
	 * This method will parse the request and extract the relevant command.
	 *
	 * The list of accepted commands are:
	 *
	 * <ul>
	 *	<li><tt>{@link kAPI_OP_WITHIN}</tt>: Retrieve elements contained by the provided
	 *		geometry or the element that contains the provided point.
	 *	<li><tt>{@link kAPI_OP_INTERSECT}</tt>: Retrieve elements intersecting the provided
	 *		geometry.
	 *	<li><tt>{@link kAPI_OP_NEAR}</tt>: Retrieve the 100 closest elements to the provided
	 *		point.
	 * </ul>
	 *
	 * If no command was provided, {@link kAPI_OP_WITHIN} is assumed by default.
	 *
	 * @access protected
	 * @return string
	 */
	protected function _Command()
	{
		//
		// Check PING.
		//
		if( array_key_exists( kAPI_OP_PING, $_REQUEST ) )
			return kAPI_OP_PING;													// ==>
		
		//
		// Check HELP.
		//
		if( array_key_exists( kAPI_OP_HELP, $_REQUEST ) )
			return kAPI_OP_HELP;													// ==>
		
		//
		// Check NEAR.
		//
		if( array_key_exists( kAPI_OP_NEAR, $_REQUEST ) )
			return kAPI_OP_NEAR;													// ==>
		
		//
		// Check INTERSECT.
		//
		if( array_key_exists( kAPI_OP_INTERSECT, $_REQUEST ) )
			return kAPI_OP_INTERSECT;												// ==>
		
		return kAPI_OP_WITHIN;														// ==>
	
	} // _Command.

	 
	/*===================================================================================
	 *	_Geometry																		*
	 *==================================================================================*/

	/**
	 * Get geometry.
	 *
	 * This method will parse the request and extract the provided geometry.
	 *
	 * The list of accepted geometries are:
	 *
	 * <ul>
	 *	<li><tt>{@link kAPI_GEOMETRY_POINT}</tt>: A point expressed as a string representing
	 *		longitude and latitude in this order; the coordinates must be separated by a
	 *		comma and can be expressed in decimal degrees.
	 * </ul>
	 *
	 * The method will set the corresponding object property with the GeoJSON geometry.
	 *
	 * @access protected
	 *
	 * @uses _Point()
	 */
	protected function _Geometry()
	{
		//
		// Check point.
		//
		if( array_key_exists( kAPI_GEOMETRY_POINT, $_REQUEST ) )
			$this->mGeometry
				= $this->_Point( $_REQUEST[ kAPI_GEOMETRY_POINT ] );
		
		else
			throw new Exception
				( "Unable to handle request: "
				 ."missing request geometry." );								// !@! ==>
	
	} // _Geometry.

	 
	/*===================================================================================
	 *	_Point																			*
	 *==================================================================================*/

	/**
	 * Get point.
	 *
	 * This method will parse the provided parameter and return a GeoJSON point, or raise
	 * an exception if the point cannot be parsed.
	 *
	 * The provided value is a string that represents longitude and latitude in this order;
	 * the coordinates must be separated by a comma and must be expressed in decimal
	 * degrees.
	 *
	 * The method will return a GeoJSON point.
	 *
	 * @param string				$theCoordinates		Longitude and latitude.
	 *
	 * @access protected
	 * @return array
	 */
	protected function _Point( $theCoordinates )
	{
		//
		// Parse coordinates.
		//
		$coordinates = explode( ',', $theCoordinates );
		if( count( $coordinates ) == 2 )
		{
			//
			// Check longitude.
			//
			$coordinates[ 0 ] = trim( $coordinates[ 0 ] );
			if( strlen( $coordinates[ 0 ] )
			 && is_numeric( $coordinates[ 0 ] ) )
			{
				//
				// Check latitude.
				//
				$coordinates[ 1 ] = trim( $coordinates[ 1 ] );
				if( strlen( $coordinates[ 1 ] )
				 && is_numeric( $coordinates[ 1 ] ) )
					return array( 'type' => 'Point',
								  'coordinates' => array( $coordinates[ 0 ],
								  						  $coordinates[ 1 ] ) );	// ==>
			
			} // Not empty longitude.
		
		} // Two values.
		
		throw new Exception
			( "Unable to handle request: "
			 ."provided invalid point geometry [$theCoordinates]." );			// !@! ==>
	
	} // _Point.

		

/*=======================================================================================
 *																						*
 *								PROTECTED TRANSFORM INTERFACE							*
 *																						*
 *======================================================================================*/


	 
	/*===================================================================================
	 *	_Point2Polygon																	*
	 *==================================================================================*/

	/**
	 * Transform point into polygon.
	 *
	 * This method will transform the provided point into a rect where the vertices are
	 * 15 seconds in either direction.
	 *
	 * The method will return a GeoJSON polygon.
	 *
	 * @param array					$thePoint			GeoJson point.
	 *
	 * @access protected
	 * @return array
	 */
	protected function _Point2Polygon( $thePoint )
	{
		//
		// Check point.
		//
		if( ($tmp = $thePoint[ 'type' ]) == 'Point' )
		{
			//
			// Init local storage.
			//
			$ptcoords = & $thePoint[ 'coordinates' ];
			
			//
			// Set left.
			//
			$left = $ptcoords[ 0 ] - self::kTileDegs;
			if( $left < -180 )
				$left = 180 - ($left + 180);
			
			//
			// Set right.
			//
			$right = $ptcoords[ 0 ] + self::kTileDegs;
			if( $right > 180 )
				$right = -180 + ($right - 180);
			
			//
			// Set top.
			//
			$top = $ptcoords[ 1 ] + self::kTileDegs;
			if( $top > 90 )
				$top = -90 + ($top - 90);
			
			//
			// Set bottom.
			//
			$bottom = $ptcoords[ 1 ] - self::kTileDegs;
			if( $bottom < -90 )
				$bottom = 90 - ($bottom + 90);
			
			return array(
				'type' => 'Polygon',
				'coordinates' => array(
					array( $left, $top ),
					array( $right, $top ),
					array( $right, $bottom ),
					array( $left, $bottom ),
					array( $left, $top ) ) );										// ==>
		
		} // Provided a point.
		
		throw new Exception
			( "Unable to handle request: "
			 ."expecting a point, received [$tmp]." );							// !@! ==>
	
	} // _Box.

	 

} // class CWorldclimService.


?>
