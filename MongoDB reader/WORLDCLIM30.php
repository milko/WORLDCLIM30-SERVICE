<?php
	
/**
 * WORLDCLIM 30 service.
 *
 * This file contains the scripts to handle the WORLDCLIM 30 seconds service.
 *
 *	@package	WORLDCLIM
 *	@subpackage	Service
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 16/07/2013
 */

/*=======================================================================================
 *																						*
 *									WORLDCLIM30.php										*
 *																						*
 *======================================================================================*/

/**
 * Local includes.
 *
 * This include file contains local definitions.
 */
require_once( 'local.inc.php' );

/**
 * Class includes.
 *
 * This include file contains the service class definitions.
 */
require_once( "CWorldclimService.php" );


/*=======================================================================================
 *	SERVICE WORLDCLIM 30 DATA															*
 *======================================================================================*/

//
// Instantiate service.
//
$wrapper = new CWorldclimService( kDEFAULT_SERVER, kDEFAULT_DATABASE, kDEFAULT_COLLECTION );
if( $wrapper->Status() == kAPI_STATE_ERROR )
	exit( json_encode( $wrapper->getArrayCopy() ) );								// ==>

//
// Handle request.
//
$wrapper->HandleRequest();

//
// Handle success.
//
if( $wrapper->Status() == kAPI_STATE_OK )
{
	//
	// Handle help.
	//
	if( $wrapper->Operation() == kAPI_OP_HELP )
		exit( $wrapper->getArrayCopy()[ kAPI_RESPONSE_DATA ] );						// ==>
}

exit( json_encode( $wrapper->getArrayCopy() ) );									// ==>

?>
