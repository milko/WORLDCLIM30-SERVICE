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
 *								test_CBilFilesReader.bis.php							*
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
 *	TEST																				*
 *======================================================================================*/

//
// BIL files path.
//
$file1 = kPATH_FILES.'/alt/alt.bil';
$file2 = kPATH_FILES.'/gens/gens.bil';
 
//
// Test class.
//
try
{
	//
	// Instantiate reader.
	//
	$iterations = 1000;
	$files = new CBilFilesIterator( 30, 43200, array( -180, 90 ), array( 360, 150 ) );
	$files->FileSet( "test1", $file1, 1, "s", -9999 );
	$files->FileSet( "test2", $file2, 1, "C", 0 );
	$reader = new CBilFilesReader( $files );
	foreach( $reader as $key => $value )
	{
		echo( "\n" );
		print_r( array( $key => $value ) );
		if( ! $iterations-- )
			break;
	}
}

//
// Catch exceptions.
//
catch( Exception $error )
{
	echo( "\nError\n" );
	echo( (string) $error );
	echo( "\n" );
}

echo( "\nDone!\n" );

?>
