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
require_once( "/CWorldclimService.inc.php" );

/**
 * Local definitions.
 *
 * This include file contains all local definitions to this class.
 */
require_once( "/local.inc.php" );

/**
 * <h4>WORLDCLIM web-service</h4>
 *
 * This class represents a web-service, it defines the parameter classes and the methods
 * that all derived web services must implement.
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
	 * @var mixed
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
	 * The constructor will set-up the environment and parse the request.
	 */
	public function __construct()
	{

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
	 *		<li><tt>FALSE</tt>: Delete current value.
	 *		<li><i>other</i>: Set with the provided value.
	 *	 </ul>
	 *	<li><tt>$getOld</tt>: Which value to return:
	 *	 <ul>
	 *		<li><tt>TRUE</tt>: <i>Before</i> it was eventually modified.
	 *		<li><tt>FALSE</tt>: <i>After</i> it was eventually modified (DEFAULT).
	 *	 </ul>
	 * </ul>
	 *
	 * @access public
	 *
	 * @uses _ManageMember()
	 */
	public function Server( $theValue = NULL, $getOld = FALSE )
	{
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
	 *		<li><tt>FALSE</tt>: Delete current value.
	 *		<li><i>other</i>: Set with the provided value.
	 *	 </ul>
	 *	<li><tt>$getOld</tt>: Which value to return:
	 *	 <ul>
	 *		<li><tt>TRUE</tt>: <i>Before</i> it was eventually modified.
	 *		<li><tt>FALSE</tt>: <i>After</i> it was eventually modified (DEFAULT).
	 *	 </ul>
	 * </ul>
	 *
	 * @access public
	 *
	 * @uses _ManageMember()
	 */
	public function Database( $theValue = NULL, $getOld = FALSE )
	{
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
	 *		<li><tt>FALSE</tt>: Delete current value.
	 *		<li><i>other</i>: Set with the provided value.
	 *	 </ul>
	 *	<li><tt>$getOld</tt>: Which value to return:
	 *	 <ul>
	 *		<li><tt>TRUE</tt>: <i>Before</i> it was eventually modified.
	 *		<li><tt>FALSE</tt>: <i>After</i> it was eventually modified (DEFAULT).
	 *	 </ul>
	 * </ul>
	 *
	 * @access public
	 *
	 * @uses _ManageMember()
	 */
	public function Collection( $theValue = NULL, $getOld = FALSE )
	{
		return $this->_ManageMember( $this->mCollection, $theValue, $getOld );		// ==>
	
	} // Collection.

		

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
	public function _ManageMember( &$theMember, $theValue, $getOld )
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

	 

} // class CWorldclimService.


?>
