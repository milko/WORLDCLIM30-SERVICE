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
	$request = "$url?".kAPI_OP_PING;
	echo( "Request: $request<br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test HELP.
	//
	echo( '<h4>Test HELP</h4>' );
	$request = "$url?".kAPI_OP_HELP;
	echo( "Request: $request<br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
	echo( 'Response<pre>' ); print_r( $response ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test Point.
	//
	echo( '<h4>Test Point</h4>' );
	$request = "$url?".kAPI_OP_WITHIN."&point=-16.6422,28.2727";
	echo( "Request: $request<br />" );
	echo( '<h5>$response = file_get_contents( $request );</h5>' );
	$response = file_get_contents( $request );
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
