<?php

/**
 * {@link CBilFilesReader} object test suite.
 *
 * This file contains routines to test and demonstrate the behaviour of the
 * {@link CBilFilesIterator} and {@link CBilFilesReader} classes.
 *
 *	@package	Test
 *	@subpackage	Framework
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 19/07/2013
 */

/*=======================================================================================
 *																						*
 *								test_CBilFilesReader.php								*
 *																						*
 *======================================================================================*/

//
// Local includes.
//
require_once( 'local.inc.php' );

//
// Class includes.
//
require_once( "CBilFilesReader.php" );


/*=======================================================================================
 *	TEST CLASS																			*
 *======================================================================================*/
 
//
// Test class definition.
//
class MyClass extends CBilFilesIterator
{
	//
	// Utilities to show protected data.
	//
	public function rewindFiles()	{	return $this->_RewindFiles();					}
	public function loadBuffers()	{	return $this->_LoadBuffers();					}
}


/*=======================================================================================
 *	TEST																				*
 *======================================================================================*/

//
// Extend execution time.
//
ini_set( 'max_execution_time', 600);	//600 seconds = 10 minutes
 
//
// BIL files path.
//
$file1 = '/Library/WebServer/Data/GeographicFeatures/alt/alt.bil';
$file2 = '/Library/WebServer/Data/GeographicFeatures/gens/gens.bil';
 
//
// Test class.
//
try
{
	//
	// Instantiate iterator.
	//
	echo( '<h4>Instantiate class</h4>' );
	echo( '<h5>$files = new MyClass( 30, 4320, array( -180, 90 ),  array( 360, 150 ) );</h5>' );
	$files = new MyClass( 30, 4320, array( -180, 90 ), array( 360, 150 ) );
	echo( 'Object<pre>' ); print_r( $files ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Add file.
	//
	echo( '<h4>Add file</h4>' );
	echo( '<h5>$file = $files->FileSet( "test1", $file1, 1, "s", -9999, TRUE );</h5>' );
	$file = $files->FileSet( "test1", $file1, 1, "s", -9999, TRUE );
	echo( 'Object<pre>' ); print_r( $files ); echo( '</pre>' );
	echo( 'File<pre>' ); print_r( $file ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Get file.
	//
	echo( '<h4>Get file</h4>' );
	echo( '<h5>$file = $files->FileGet( "test1" );</h5>' );
	$file = $files->FileGet( "test1" );
	echo( 'File<pre>' ); print_r( $file ); echo( '</pre>' );
	echo( '<hr />' );
	echo( '<hr />' );

	//
	// Test load buffers.
	//
	echo( '<h4>Test load buffers</h4>' );
	echo( '<h5>$files = new MyClass( 30, 10, array( -180, 90 ),  array( 360, 150 ) );</h5>' );
	$files = new MyClass( 30, 10, array( -180, 90 ), array( 360, 150 ) );
	echo( '<h5>$file = $files->FileSet( "test1", $file1, 1, "s", -9999 );</h5>' );
	$file = $files->FileSet( "test1", $file1, 1, "s", -9999 );
	echo( '<h5>$file = $files->FileSet( "test2", 1, "C", 0 );</h5>' );
	$file = $files->FileSet( "test2", $file2, 1, "C", 0 );
	echo( '<h5>$files->rewindFiles();</h5>' );
	$files->rewindFiles();
	echo( '<h5>$files->loadBuffers();</h5>' );
	$files->loadBuffers();
	echo( 'Object<pre>' ); print_r( $files ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test current.
	//
	echo( '<h4>Test current</h4>' );
	echo( '<h5>$data = $files->current();</h5>' );
	$data = $files->current();
	echo( 'Current<pre>' ); print_r( $data ); echo( '</pre>' );
	echo( '<hr />' );

	//
	// Test key.
	//
	echo( '<h4>Test key</h4>' );
	echo( '<h5>$data = $files->key();</h5>' );
	$data = $files->key();
	echo( 'Key<pre>' ); print_r( $data ); echo( '</pre>' );
	echo( '<hr />' );
	echo( '<hr />' );

	//
	// Test coordinates.
	//
	echo( '<h4>Test coordinates</h4>' );
	$i = 5;
	foreach( $files as $key => $value )
	{
		$lat = $files->Latitude( $latd );
		$lon = $files->Longitude( $lond );
		$dms = $files->Bounds( $dec );
		$data = array( $key => array( array( $lon, $lond ),
									  array( $lat, $latd ),
									  array( $dms, $dec ) ) );
		echo( '<pre>' );
		print_r( $data );
		print_r( $value );
		echo( '</pre>' );
		
		if( ! --$i )
			break;
	}
	echo( '<hr />' );
	echo( '<hr />' );
	
	//
	// Instantiate reader.
	//
	echo( '<h4>Instantiate reader</h4>' );
	echo( '<h5>$files = new MyClass( 30, 4320, array( -180, 90 ),  array( 360, 150 ), 95731200 );</h5>' );
	$files = new MyClass( 30, 4320, array( -180, 90 ), array( 360, 150 ), 95731200 );
	echo( '<h5>$files->FileSet( "test1", $file1, 1, "s", -9999 );</h5>' );
	$files->FileSet( "test1", $file1, 1, "s", -9999 );
	echo( '<h5>$files->FileSet( "test2", $file2, 1, "C", 0 );</h5>' );
	$files->FileSet( "test2", $file2, 1, "C", 0 );
	echo( 'Iterator:<pre>' ); print_r( $files ); echo( '</pre>' );
	echo( '<h5>$reader = new CBilFilesReader( $files );</h5>' );
	$count = 11;
	$reader = new CBilFilesReader( $files );
	foreach( $reader as $key => $value )
	{
		echo( 'Data<pre>' ); print_r( array( $key => $value ) ); echo( '</pre>' );
		if( ! $count-- )
			break;
	}
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
