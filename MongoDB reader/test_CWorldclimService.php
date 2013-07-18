<?php

/**
 * {@link CWorldclimService.php Base} object test suite.
 *
 * This file contains routines to test and demonstrate the behaviour of the
 * base object {@link CWorldclimService class}.
 *
 *	@package	Test
 *	@subpackage	Framework
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 04/09/2012
 */

/*=======================================================================================
 *																						*
 *								test_CWorldclimService.php								*
 *																						*
 *======================================================================================*/

//
// Local includes.
//
require_once( 'local.inc.php' );

//
// Class includes.
//
require_once( "CWorldclimService.php" );


/*=======================================================================================
 *	TEST																				*
 *======================================================================================*/
 
//
// Test service URL.
//
$url = "http://localhost/worldclimtest/MongoDB%20reader/WORLDCLIM30.php";

//
// Test class.
//
try
{
	//
	// Test PING.
	//
	echo( '<h4>Test PING</h4>' );
	$op = kAPI_OP_PING;
	$geo = kAPI_GEOMETRY_RECT.'=-16.6463,28.2768;-16.6380,28.2685';
	$mod = implode( '&', array( kAPI_OP_REQUEST, kAPI_OP_CONNECTION ) );
	$request = "$url?$op&$mod&$geo";
	echo( "Request: <code>$request</code><br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( '<h5>$response = json_decode( $response );</h5>' );
	$response = json_decode( $response );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test HELP.
	//
	echo( '<h4>Test HELP</h4>' );
	$op = kAPI_OP_HELP;
	$request = "$url?$op";
	echo( "Request: <code>$request</code><br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test TILE.
	//
	echo( '<h4>Test TILE</h4>' );
	$op = kAPI_OP_TILE;
	$geo = kAPI_GEOMETRY_TILE.'=33065587,774896741';
	$mod = implode( '&', array( kAPI_OP_REQUEST, kAPI_OP_CONNECTION ) );
	$request = "$url?$op&$mod&$geo";
	echo( "Request: <code>$request</code><br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( '<h5>$response = json_decode( $response );</h5>' );
	$response = json_decode( $response );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test IN.
	//
	echo( '<h4>Test IN</h4>' );
	$op = kAPI_OP_IN;
//	$geo = kAPI_GEOMETRY_POINT.'='.implode( ',', array( -16.6463, 28.2768 ) );
/*	$geo = kAPI_GEOMETRY_RECT.'='
		.implode( ',', array( -16.6463,28.2768 ) )
		.';'
		.implode( ',', array( -16.6380,28.2685 ) );
*/	$geo = kAPI_GEOMETRY_POLY.'='
		.implode( ',', array( 9.5387,46.2416 ) )
		.';'
		.implode( ',', array( 9.5448,46.2369 ) )
		.';'
		.implode( ',', array( 9.5536,46.2381 ) )
		.';'
		.implode( ',', array( 9.5571,46.2419 ) )
		.';'
		.implode( ',', array( 9.5507,46.2462 ) )
		.';'
		.implode( ',', array( 9.5439,46.2468 ) )
		.';'
		.implode( ',', array( 9.5387,46.2416 ) )
		.':'
		.implode( ',', array( 9.5445,46.2422 ) )
		.';'
		.implode( ',', array( 9.5481,46.2399 ) )
		.';'
		.implode( ',', array( 9.5517,46.2420 ) )
		.';'
		.implode( ',', array( 9.5463,46.2443 ) )
		.';'
		.implode( ',', array( 9.5445,46.2422 ) );
	$mod = implode( '&', array( kAPI_OP_REQUEST, kAPI_OP_CONNECTION ) );
	$request = "$url?$op&$mod&$geo";
	echo( "Request: <code>$request</code><br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( '<h5>$response = json_decode( $response );</h5>' );
	$response = json_decode( $response );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test IN (count).
	//
	echo( '<h4>Test IN (count)</h4>' );
	$op = kAPI_OP_IN;
	$geo = kAPI_GEOMETRY_RECT.'='
		.implode( ',', array( -10,30 ) )
		.';'
		.implode( ',', array( -15,25 ) );
	$mod = implode( '&', array( kAPI_OP_REQUEST, kAPI_OP_CONNECTION, kAPI_OP_COUNT ) );
	$request = "$url?$op&$mod&$geo";
	echo( "Request: <code>$request</code><br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( '<h5>$response = json_decode( $response );</h5>' );
	$response = json_decode( $response );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test IN (default limits).
	//
	echo( '<h4>Test IN (default limits)</h4>' );
	$op = kAPI_OP_IN;
	$geo = kAPI_GEOMETRY_RECT.'='
		.implode( ',', array( -10,30 ) )
		.';'
		.implode( ',', array( -15,25 ) );
	$mod = implode( '&', array( kAPI_OP_REQUEST, kAPI_OP_CONNECTION ) );
	$request = "$url?$op&$mod&$geo";
	echo( "Request: <code>$request</code><br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( '<h5>$response = json_decode( $response );</h5>' );
	$response = json_decode( $response );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test IN (enforced limits).
	//
	echo( '<h4>Test IN (enforced limits)</h4>' );
	$op = kAPI_OP_IN;
	$geo = kAPI_GEOMETRY_RECT.'='
		.implode( ',', array( -10,30 ) )
		.';'
		.implode( ',', array( -15,25 ) );
	$mod = implode( '&', array( kAPI_OP_REQUEST, kAPI_OP_CONNECTION ) );
	$limit = kAPI_PAGE_LIMIT.'=200000';
	$request = "$url?$op&$mod&$geo&$limit";
	echo( "Request: <code>$request</code><br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( '<h5>$response = json_decode( $response );</h5>' );
	$response = json_decode( $response );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test IN (provided limits).
	//
	echo( '<h4>Test IN (provided limits)</h4>' );
	$op = kAPI_OP_IN;
	$geo = kAPI_GEOMETRY_RECT.'='
		.implode( ',', array( -10,30 ) )
		.';'
		.implode( ',', array( -15,25 ) );
	$mod = implode( '&', array( kAPI_OP_REQUEST, kAPI_OP_CONNECTION ) );
	$start = kAPI_PAGE_START.'=230';
	$limit = kAPI_PAGE_LIMIT.'=5';
	$request = "$url?$op&$mod&$geo&$start&$limit";
	echo( "Request: <code>$request</code><br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( '<h5>$response = json_decode( $response );</h5>' );
	$response = json_decode( $response );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test IN (elevation range).
	//
	echo( '<h4>Test IN (elevation range)</h4>' );
	$op = kAPI_OP_IN;
	$geo = kAPI_GEOMETRY_RECT.'='
		.implode( ',', array( -10,30 ) )
		.';'
		.implode( ',', array( -15,25 ) );
	$mod = implode( '&', array( kAPI_OP_REQUEST, kAPI_OP_CONNECTION, kAPI_OP_COUNT ) );
	$elevation = kAPI_ENV_ELEVATION.'=1000,1050';
	$request = "$url?$op&$mod&$geo&$elevation";
	echo( "Request: <code>$request</code><br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( '<h5>$response = json_decode( $response );</h5>' );
	$response = json_decode( $response );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );
	echo( '<hr />' );
}

//
// Catch exceptions.
//
catch( \Exception $error )
{
	echo( '<h3><font color="red">Unexpected exception</font></h3>' );
	echo( '<pre>'.(string) $error.'</pre>' );
	echo( '<hr>' );
}

echo( "\nDone!\n" );

?>
