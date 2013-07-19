<?php

/*=======================================================================================
 *																						*
 *									CBilReader.inc.php									*
 *																						*
 *======================================================================================*/
 
/**
 *	{@link CBilReader} definitions.
 *
 *	This file contains common definitions used by the {@link CBilReader} class.
 *
 *	@package	GEO
 *	@subpackage	Framework
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 19/07/2013
 */

/*=======================================================================================
 *	FILE INFO OFFSETS																	*
 *======================================================================================*/

/**
 * Path.
 *
 * This tag identifies the file path.
 *
 * Type: string.
 */
define( "kFILE_PATH",					'path' );

/**
 * Bands.
 *
 * This tag identifies the number of bands per tile.
 *
 * Type: integer.
 */
define( "kFILE_BANDS",					'bands' );

/**
 * Band pack.
 *
 * This tag identifies the data type of each band:
 * <ul>
 *	<li><tt>c</tt>: signed char
 *	<li><tt>C</tt>: unsigned char
 *	<li><tt>s</tt>: signed short (always 16 bit, machine byte order)
 *	<li><tt>S</tt>: unsigned short (always 16 bit, machine byte order)
 *	<li><tt>n</tt>: unsigned short (always 16 bit, big endian byte order)
 *	<li><tt>v</tt>: unsigned short (always 16 bit, little endian byte order)
 *	<li><tt>i</tt>: signed integer (machine dependent size and byte order)
 *	<li><tt>I</tt>: unsigned integer (machine dependent size and byte order)
 *	<li><tt>l</tt>: signed long (always 32 bit, machine byte order)
 *	<li><tt>L</tt>: unsigned long (always 32 bit, machine byte order)
 *	<li><tt>N</tt>: unsigned long (always 32 bit, big endian byte order)
 *	<li><tt>V</tt>: unsigned long (always 32 bit, little endian byte order)
 * </ul>
 *
 * Type: string.
 */
define( "kFILE_BPACK",					'bpack' );

/**
 * Band size.
 *
 * This tag identifies the size of each band in bytes.
 *
 * Type: string.
 */
define( "kFILE_BSIZE",					'bsize' );

/**
 * Sigmed.
 *
 * This tag identifies the signed value flag.
 *
 * Type: boolean.
 */
define( "kFILE_SIGNED",					'signed' );

/**
 * No data.
 *
 * This tag identifies the no-data value.
 *
 * Type: mixed.
 */
define( "kFILE_NODATA",					'ndata' );

/**
 * Pointer.
 *
 * This tag identifies the file pointer.
 *
 * Type: resource.
 */
define( "kFILE_POINTER",				'fp' );

/**
 * buffer.
 *
 * This tag identifies the read buffer, it is an array that contains the current list of
 * band values. Each element is either a scalar, if the {@link kFILE_BANDS} is 1, or an
 * array if greater than 1. The buffer will contain {@link kBUF_TILE_COUNT} elements.
 *
 * Type: array.
 */
define( "kFILE_BUFFER",					'buf' );


?>
