<?php

/**
 * This script will return the climate data corresponding to the provided coordinates.
 *
 *	@package	Services
 *	@subpackage	WorldClim
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 02/12/2010
 */

/*=======================================================================================
 *																						*
 *										WorldClim.php									*
 *																						*
 *======================================================================================*/


//
// Includes.
//
require_once( "WorldClim.inc.php" );


/*=======================================================================================
 *																						*
 *											MAIN										*
 *																						*
 *======================================================================================*/

//
// Get parameters.
//
if( isset( $_REQUEST )
 && array_key_exists( 'lat', $_REQUEST )
 && array_key_exists( 'lon', $_REQUEST ) )
{
	//
	// Get values.
	//
	$latitude = (double) $_REQUEST[ 'lat' ];
	$longitude = (double) $_REQUEST[ 'lon' ];
	
	//
	// Build command.
	//
	$command = '"'.kPATH_CLIM_CMD.'" "'.kPATH_CLIM_DIR.'" '."$latitude $longitude";
	
	//
	// Run command.
	//
	echo( shell_exec( $command ) );
	
} // Provided coordinates.


?>
