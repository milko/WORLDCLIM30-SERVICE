<?php

/*=======================================================================================
 *																						*
 *								CBilFilesReader.inc.php									*
 *																						*
 *======================================================================================*/
 
/**
 *	{@link CBilFilesReader} definitions.
 *
 *	This file contains common definitions used by the {@link CBilFilesReader} class.
 *
 *	@package	GEO
 *	@subpackage	Framework
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 20/07/2013
 */

/*=======================================================================================
 *	COORDINATE DATA OFFSETS																*
 *======================================================================================*/

/**
 * Point.
 *
 * This tag identifies the the tile point coordinates, the value will be an array of two
 * elements representing respectively the longitude and latitude expressed in decimal
 * degrees.
 *
 * Type: array.
 */
define( "kOFFSET_POINT",				'pt' );

/**
 * Coordinates.
 *
 * This tag identifies the the tile point coordinates, the value will be an array of two
 * elements representing respectively the longitude and latitude expressed in degrees,
 * minutes and seconds.
 *
 * Type: array.
 */
define( "kOFFSET_COORD",				'dms' );

/**
 * Tile.
 *
 * This tag identifies the the tile column and row expressed as an array of <tt>x</tt> and
 * <tt>y</tt> pairs of integers.
 *
 * Type: array.
 */
define( "kOFFSET_TILE",					'tile' );

/**
 * Bounding box in degrees minutes and seconds.
 *
 * This tag identifies the the tile bounding box coordinates, the value will be an array of
 * two elements representing respectively the top-left and bottom-right vertices coordinates
 * and each element holds the longitude and latitude expressed in degrees, minutes and
 * seconds.
 *
 * Type: array.
 */
define( "kOFFSET_BOX_DMS",				'bdms' );

/**
 * Bounding box in decimal degrees.
 *
 * This tag identifies the the tile bounding box coordinates, the value will be an array of
 * two elements representing respectively the top-left and bottom-right vertices coordinates
 * and each element holds the longitude and latitude expressed in decimal degrees.
 *
 * Type: array.
 */
define( "kOFFSET_BOX_DEC",				'bdec' );


?>
