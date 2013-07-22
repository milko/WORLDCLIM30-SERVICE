<?php
	
/**
 * Geographic layers service.
 *
 * This file contains the scripts to handle the geographic layers service.
 *
 *	@package	GEO
 *	@subpackage	Service
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 21/07/2013
 */

/*=======================================================================================
 *																						*
 *								GeoLayers.remote.php									*
 *																						*
 *======================================================================================*/

/**
 * Local includes.
 *
 * This include file contains local definitions.
 */
require_once( 'local.remote.inc.php' );

/**
 * Class includes.
 *
 * This include file contains the service class definitions.
 */
require_once( "CGeoLayerService.php" );


/*=======================================================================================
 *	SERVICE WORLDCLIM 30 DATA															*
 *======================================================================================*/

//
// Instantiate service.
//
$wrapper = new CGeoLayerService( kDEFAULT_SERVER, kDEFAULT_DATABASE, kDEFAULT_COLLECTION );
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
