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
 *	DEFAULT WEB-SERVICE OPERATION PARAMETERS											*
 *======================================================================================*/

/**
 * Help.
 *
 * This is the tag that represents the HELP web-service operation, which returns the list
 * of supported operations and options.
 */
define( "kAPI_OP_HELP",				'help' );

/**
 * Ping.
 *
 * This is the tag that represents the PING web-service operation, which returns 'pong'.
 */
define( "kAPI_OP_PING",				'ping' );

/*=======================================================================================
 *	WEB-SERVICE OPERATION PARAMETERS													*
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
 * Retrieve elements intersecting by the provided geometry.
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
 *	GEOMETRY AND LOCATION REQUEST PARAMETERS											*
 *======================================================================================*/

/**
 * Point latitude.
 *
 * Latitude of the provided point.
 *
 * Type: float.
 */
define( "kAPI_POINT_LATITUDE",		'lat' );

/**
 * Point longitude.
 *
 * Longitude of the provided point.
 *
 * Type: float.
 */
define( "kAPI_POINT_LONGITUDE",		'lon' );

/**
 * Rect left.
 *
 * Minimum longitude of the provided rect.
 *
 * Type: float.
 */
define( "kAPI_RECT_XMIN",			'xmin' );

/**
 * Rect right.
 *
 * Maximum longitude of the provided rect.
 *
 * Type: float.
 */
define( "kAPI_RECT_XMAX",			'xmax' );

/**
 * Rect top.
 *
 * Maximum latitude of the provided rect.
 *
 * Type: float.
 */
define( "kAPI_RECT_YMAX",			'ymax' );

/**
 * Rect bottom.
 *
 * Minimum latitude of the provided rect.
 *
 * Type: float.
 */
define( "kAPI_RECT_YMIN",			'ymin' );


?>
