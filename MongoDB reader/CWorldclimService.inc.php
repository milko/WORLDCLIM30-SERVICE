<?php

/*=======================================================================================
 *																						*
 *								CWorldclimService.inc.php								*
 *																						*
 *======================================================================================*/
 
/**
 *	{@link CWorldclimService} definitions.
 *
 *	This file contains common definitions used by the {@link CWorldclimService} class.
 *
 *	@package	WORLDCLIM30
 *	@subpackage	Services
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 04/07/2013
 */

/*=======================================================================================
 *	DEFAULT OPERATION PARAMETERS														*
 *======================================================================================*/

/**
 * Ping.
 *
 * This is the tag that represents the PING web-service operation, which returns 'pong'.
 *
 * Type: no data.
 */
define( "kAPI_OP_PING",				'ping' );

/**
 * Help.
 *
 * This is the tag that represents the HELP web-service operation, which returns the list
 * of supported operations and options.
 *
 * Type: no data.
 */
define( "kAPI_OP_HELP",				'help' );

/*=======================================================================================
 *	OPERATION PARAMETERS																*
 *======================================================================================*/

/**
 * Contains.
 *
 * Retrieve elements contained by the provided geometry or the element that contains the
 * provided point.
 *
 * Type: no data.
 */
define( "kAPI_OP_WITHIN",			'within' );

/**
 * Intersection.
 *
 * Retrieve elements intersecting the provided geometry.
 *
 * Type: no data.
 */
define( "kAPI_OP_INTERSECT",		'intersect' );

/**
 * Near.
 *
 * Retrieve the 100 closest elements to the provided point.
 *
 * Type: no data.
 */
define( "kAPI_OP_NEAR",				'near' );

/**
 * Count.
 *
 * Return only element count.
 * This parameter may be added to the other operations.
 *
 * Type: no data.
 */
define( "kAPI_OP_COUNT",			'count' );

/*=======================================================================================
 *	GEOMETRY PARAMETERS																	*
 *======================================================================================*/

/**
 * Point.
 *
 * A point expressed as a pair of coordinates, longitude and latitude in that order,
 * separated by a comma.
 *
 * Examples:
 * -16.6422,28.2727
 *
 * Type: string.
 */
define( "kAPI_GEOMETRY_POINT",		'point' );


?>
