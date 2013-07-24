<?php

/*=======================================================================================
 *																						*
 *								CGeoLayerService.inc.php								*
 *																						*
 *======================================================================================*/
 
/**
 *	{@link CGeoLayerService} definitions.
 *
 *	This file contains common definitions used by the {@link CGeoLayerService} class.
 *
 *	@package	WORLDCLIM30
 *	@subpackage	Services
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 04/07/2013
 */

/*=======================================================================================
 *	OPERATION PARAMETERS																*
 *======================================================================================*/

/**
 * Ping.
 *
 * This is the tag that represents the PING web-service operation, which returns 'pong'.
 *
 * Type: no data.
 */
define( "kAPI_OP_PING",					'ping' );

/**
 * Help.
 *
 * This is the tag that represents the HELP web-service operation, which returns the list
 * of supported operations and options.
 *
 * Type: no data.
 */
define( "kAPI_OP_HELP",					'help' );

/**
 * Tile.
 *
 * Retrieve tiles matching the provided list of tile identifiers.
 *
 * Type: no data.
 */
define( "kAPI_OP_TILE",					'tiles' );

/**
 * Contains.
 *
 * Retrieve tiles containing the provided point, or the tiles contained by the provided
 * rect or polygon.
 *
 * Type: no data.
 */
define( "kAPI_OP_CONTAINS",				'contains' );

/**
 * Intersects.
 *
 * Retrieve tiles intersecting the provided rect or polygon.
 *
 * Type: no data.
 */
define( "kAPI_OP_INTERSECTS",			'intersects' );

/**
 * Near.
 *
 * Retrieve tiles based on the distance from the provided geometry.
 *
 * Type: no data.
 */
define( "kAPI_OP_NEAR",					'near' );

/*=======================================================================================
 *	MODIFIERS																			*
 *======================================================================================*/

/**
 * Count.
 *
 * Return only element count and altitude range.
 *
 * Type: no data.
 */
define( "kAPI_OP_COUNT",				'count' );

/**
 * Request.
 *
 * Return a copy of the parsed request.
 *
 * Type: no data.
 */
define( "kAPI_OP_REQUEST",				'copy-request' );

/**
 * Connection.
 *
 * Return information on the connection.
 *
 * Type: no data.
 */
define( "kAPI_OP_CONNECTION",			'copy-connection' );

/*=======================================================================================
 *	GEOMETRY PARAMETERS																	*
 *======================================================================================*/

/**
 * Tile.
 *
 * A list of tile identifiers separated by a comma.
 *
 * Examples:
 * <tt>33065587,774896741</tt>
 *
 * Type: string.
 */
define( "kAPI_GEOMETRY_TILE",			'tile' );

/**
 * Point.
 *
 * A point is expressed as a pair of coordinates, longitude and latitude in that order,
 * separated by a comma.
 *
 * Examples:
 * <tt>-16.6422,28.2727</tt>
 *
 * Type: string.
 */
define( "kAPI_GEOMETRY_POINT",			'point' );

/**
 * Rect.
 *
 * A rect is expressed as a pair of longitude and latitude coordinates, the coordinates are
 * separated by a comma and the vertices by a semicolon.
 *
 * Examples:
 * <tt>-16.6422,28.2727;-12.22,23.55</tt>
 *
 * Type: string.
 */
define( "kAPI_GEOMETRY_RECT",			'rect' );

/**
 * Polygon.
 *
 * A polygon is expressed as a list of rings in which the first one represents the outer
 * ring and the others eventual holes. Rings are separated by colons, polygons by semicolons
 * and coordinates by commas; coordinates must be expressed as longitude and latitude pairs.
 *
 * Examples:
 * <tt>9.5387,46.2416;9.5448,46.2369;9.5536,46.2381;9.5571,46.2419;9.5507,46.2462;9.5439,46.2468;9.5387,46.2416:
 * 9.5445,46.2422;9.5481,46.2399;9.5517,46.2420;9.5463,46.2443;9.5445,46.2422</tt>
 *
 * Type: string.
 */
define( "kAPI_GEOMETRY_POLY",			'poly' );

/**
 * Max distance.
 *
 * This parameter can be used for two purposes:
 *
 * <ul>
 *	<li><i>Convert a point</i>:: When a point is provided as a geometry and the distance is
 *		also provided, this means that we are looking at a sphere, where the distance is its
 *		radius. This is only valid when searching for tiles contained in the provided
 *		geometry, when searching for intersections, the point will be converted to a rect.
 *	<li><i>Maximum distance</i>: When requesting tiles by proximity, the distance can be
 *		used to limit the search to tiles within the provided value.
 * </ul>
 *
 * The value is expressed in kilometers.
 *
 * Examples:
 * <tt>1.250</tt>
 * <tt>5.60125</tt>
 *
 * Type: float.
 */
define( "kAPI_GEOMETRY_DISTANCE",		'dist' );

/*=======================================================================================
 *	ENVIRONMENT PARAMETERS																*
 *======================================================================================*/

/**
 * Elevation.
 *
 * Elevation range selector, provide a minimum and maximum value.
 *
 * Examples:
 * <tt>200,250</tt>
 *
 * Type: array.
 */
define( "kAPI_ENV_ELEVATION",			'alt' );

/*=======================================================================================
 *	PAGING PARAMETERS																	*
 *======================================================================================*/

/**
 * Start.
 *
 * The starting record to retrieve.
 *
 * Type: intger.
 */
define( "kAPI_PAGE_START",				'start' );

/**
 * Limit.
 *
 * The maximum number of records to retrieve.
 *
 * Type: intger.
 */
define( "kAPI_PAGE_LIMIT",				'limit' );

/*=======================================================================================
 *	RESPONSE BLOCK PARAMETERS															*
 *======================================================================================*/

/**
 * Status.
 *
 * This tag identifies the status block.
 */
define( "kAPI_RESPONSE_STATUS",			'status' );

/**
 * Request.
 *
 * This tag identifies the parsed request block.
 */
define( "kAPI_RESPONSE_REQUEST",		'request' );

/**
 * Connection.
 *
 * This tag identifies the connection description block.
 */
define( "kAPI_RESPONSE_CONNECTION",		'connection' );

/**
 * Response.
 *
 * This tag identifies the service data response.
 */
define( "kAPI_RESPONSE_DATA",			'data' );

/*=======================================================================================
 *	STATUS BLOCK PARAMETERS																*
 *======================================================================================*/

/**
 * State.
 *
 * This tag identifies the status state, it can take two values: <tt>OK</tt> for a
 * successful operation or <tt>ERROR</tt> for a failed operation: the <tt>message</tt> field
 * will receive the error message.
 *
 * Type: string.
 */
define( "kAPI_STATUS_STATE",			'state' );

/**
 * Total.
 *
 * This tag identifies the result effective count, that is, the total number of results of
 * the query, excluding paging requests.
 *
 * Type: integer.
 */
define( "kAPI_STATUS_TOTAL",			'total' );

/**
 * Start.
 *
 * This tag identifies the result records start.
 *
 * Type: integer.
 */
define( "kAPI_STATUS_START",			'start' );

/**
 * Limit.
 *
 * This tag identifies the result maximum records count.
 *
 * Type: integer.
 */
define( "kAPI_STATUS_LIMIT",			'limit' );

/**
 * Count.
 *
 * This tag identifies the result count, that is, the total number of results returned by
 * the service.
 *
 * Type: integer.
 */
define( "kAPI_STATUS_COUNT",			'count' );

/**
 * Code.
 *
 * This tag identifies the status code, it will generally be omitted except if there is
 * an error.
 *
 * Type: int.
 */
define( "kAPI_STATUS_CODE",				'code' );

/**
 * Message.
 *
 * This tag identifies the status message, it will generally be omitted except if there is
 * an error.
 *
 * Type: string.
 */
define( "kAPI_STATUS_MESSAGE",			'message' );

/**
 * File.
 *
 * This tag identifies the exception file, it will generally be omitted except if there is
 * an error.
 *
 * Type: string.
 */
define( "kAPI_STATUS_FILE",				'file' );

/**
 * Line.
 *
 * This tag identifies the exception file line, it will generally be omitted except if there
 * is an error.
 *
 * Type: int.
 */
define( "kAPI_STATUS_LINE",				'line' );

/**
 * Trace.
 *
 * This tag identifies the exception trace, it will generally be omitted except if there is
 * an error.
 *
 * Type: array.
 */
define( "kAPI_STATUS_TRACE",			'trace' );

/*=======================================================================================
 *	REQUEST BLOCK PARAMETERS															*
 *======================================================================================*/

/**
 * OPERATION.
 *
 * This tag identifies the requested operation.
 */
define( "kAPI_REQUEST_OPERATION",		'operation' );

/**
 * MODIFIERS.
 *
 * This tag identifies the requested modifiers.
 */
define( "kAPI_REQUEST_MODIFIERS",		'modifiers' );

/**
 * GEOMETRY.
 *
 * This tag identifies the request geometry.
 */
define( "kAPI_REQUEST_GEOMETRY",		'geometry' );

/**
 * ELEVATION.
 *
 * This tag identifies the elevation range.
 */
define( "kAPI_REQUEST_ELEVATION",		'elevation' );

/*=======================================================================================
 *	CONNECTION BLOCK PARAMETERS															*
 *======================================================================================*/

/**
 * Server.
 *
 * This tag identifies the connected server.
 *
 * Type: string.
 */
define( "kAPI_CONNECTION_SERVER",		'server' );

/**
 * Database.
 *
 * This tag identifies the connected database.
 *
 * Type: string.
 */
define( "kAPI_CONNECTION_DATABASE",		'database' );

/**
 * Collection.
 *
 * This tag identifies the connected collection.
 *
 * Type: string.
 */
define( "kAPI_CONNECTION_COLLECTION",	'collection' );

/*=======================================================================================
 *	STATUS STATE ENUMERATIONS															*
 *======================================================================================*/

/**
 * IDLE.
 *
 * This tag identifies an idle state.
 */
define( "kAPI_STATE_IDLE",				'IDLE' );

/**
 * OK.
 *
 * This tag identifies a successful state.
 */
define( "kAPI_STATE_OK",				'OK' );

/**
 * ERROR.
 *
 * This tag identifies an unsuccessful state.
 */
define( "kAPI_STATE_ERROR",				'ERROR' );

/*=======================================================================================
 *	DEFAULT LIMITS																		*
 *======================================================================================*/

/**
 * Maximum records.
 *
 * This tag identifies the default maximum records count.
 *
 * Type: integer.
 */
define( "kAPI_DEFAULT_LIMIT",			100 );


?>
