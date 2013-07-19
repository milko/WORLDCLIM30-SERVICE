<?php

/**
 * {@link CBilReader.php Base} object test suite.
 *
 * This file contains routines to test and demonstrate the behaviour of the
 * base object {@link CBilReader class}.
 *
 *	@package	Test
 *	@subpackage	Framework
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 19/07/2013
 */

/*=======================================================================================
 *																						*
 *									test_CBilReader.php									*
 *																						*
 *======================================================================================*/

//
// Local includes.
//
require_once( 'local.inc.php' );

//
// Class includes.
//
require_once( "CBilReader.php" );


/*=======================================================================================
 *	TEST CLASS																			*
 *======================================================================================*/
 
//
// Test class definition.
//
class MyClass extends CBilReader
{
	//
	// Utilities to show protected data.
	//
	public function rewindFiles()	{	return $this->_RewindFiles();					}
	public function loadBuffers()	{	return $this->_LoadBuffers();					}
	public function my_current()	{	return $this->_LoadCurrent();					}
}


/*=======================================================================================
 *	TEST																				*
 *======================================================================================*/

//
// Extend execution time.
//
ini_set( 'max_execution_time', 600);	//600 seconds = 10 minutes
 
//
// Test class.
//
try
{
	//
	// Instantiate class.
	//
	echo( '<h4>Instantiate class</h4>' );
	echo( '<h5>$test = new MyClass( 30, 4320, array( -180, 90 ),  array( 360, 150 ) );</h5>' );
	$test = new MyClass( 30, 4320, array( -180, 90 ), array( 360, 150 ) );
	echo( 'Object<pre>' ); print_r( $test ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Add file.
	//
	echo( '<h4>Add file</h4>' );
	echo( '<h5>$file = $test->FileSet( "test1", "test_CBilReader.php", 1, "s", -9999, TRUE );</h5>' );
	$file = $test->FileSet( "test1", "test_CBilReader.php", 1, "s", -9999, TRUE );
	echo( 'Object<pre>' ); print_r( $test ); echo( '</pre>' );
	echo( 'File<pre>' ); print_r( $file ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Get file.
	//
	echo( '<h4>Get file</h4>' );
	echo( '<h5>$file = $test->FileGet( "test1" );</h5>' );
	$file = $test->FileGet( "test1" );
	echo( 'File<pre>' ); print_r( $file ); echo( '</pre>' );
	echo( '<hr />' );
	echo( '<hr />' );

	//
	// Test load buffers.
	//
	echo( '<h4>Test load buffers</h4>' );
	echo( '<h5>$test = new MyClass( 30, 10, array( -180, 90 ),  array( 360, 150 ) );</h5>' );
	$test = new MyClass( 30, 10, array( -180, 90 ), array( 360, 150 ) );
	echo( '<h5>$file = $test->FileSet( "test1", "/Volumes/Data/GeographicFeatures/WORLDCLIM30/alt/alt.bil", 1, "s", -9999, TRUE );</h5>' );
	$file = $test->FileSet( "test1", "/Volumes/Data/GeographicFeatures/WORLDCLIM30/alt/alt.bil", 1, "s", -9999, TRUE );
	echo( '<h5>$file = $test->FileSet( "test2", "/Volumes/Data/GeographicFeatures/WORLDCLIM30/alt/alt.bil", 3, "s", -9999, TRUE );</h5>' );
	$file = $test->FileSet( "test2", "/Volumes/Data/GeographicFeatures/WORLDCLIM30/alt/alt.bil", 3, "s", -9999, TRUE );
	echo( '<h5>$test->rewindFiles();</h5>' );
	$test->rewindFiles();
	echo( '<h5>$test->loadBuffers();</h5>' );
	$test->loadBuffers();
	echo( 'Object<pre>' ); print_r( $test ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test current.
	//
	echo( '<h4>Test current</h4>' );
	echo( '<h5>$data = $test->my_current();</h5>' );
	$data = $test->my_current();
	echo( 'Current<pre>' ); print_r( $data ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test key.
	//
	echo( '<h4>Test key</h4>' );
	echo( '<h5>$data = $test->key();</h5>' );
	$data = $test->key();
	echo( 'Key<pre>' ); print_r( $data ); echo( '</pre>' );
	echo( '<hr />' );
	echo( '<hr />' );

	//
	// Test longitude.
	//
	echo( '<h4>Test longitude</h4>' );
	$test->rewind();
	for( $i = 0; $i < 43300; $i++ )
	{
		echo( '<pre>' );
		print_r( $test->Longitude() );
		echo( '</pre>' );
		$test->next();
	}
	echo( '<hr />' );
/*
	//
	// Get first data.
	//
	echo( '<h4>Get first data</h4>' );
	echo( '<h5>$test = new MyClass( 30, 43200, array( -180, 90 ),  array( 360, 150 ) );</h5>' );
	$test = new MyClass( 30, 43200, array( -180, 90 ), array( 360, 150 ) );
	echo( '<h5>$file = $test->FileSet( "test", "/Volumes/Data/GeographicFeatures/WORLDCLIM30/alt/alt.bil", 1, "s", -9999, TRUE );</h5>' );
	$file = $test->FileSet( "test", "/Volumes/Data/GeographicFeatures/WORLDCLIM30/alt/alt.bil", 1, "s", -9999, TRUE );
	$count = 10;
	foreach( $test as $key => $value )
	{
		if( count( $value ) )
		{
			echo( '<pre>' );
			print_r( array( $key => $value ) );
			echo( '</pre>' );
			if( ! --$count )
				break;
		}
	}
	echo( '<hr />' );
*/
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
