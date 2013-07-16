<?php

/*=======================================================================================
 *																						*
 *									local.inc.php										*
 *																						*
 *======================================================================================*/
 
/**
 *	Local include file.
 *
 *	This file sincludes the default values used by the service, it can be customised.
 *
 *	@package	WORLDCLIM30
 *	@subpackage	Defaults
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 04/07/2013
 */

/*=======================================================================================
 *	DEFAULT DEFINITIONS																	*
 *======================================================================================*/

/**
 * Default server DSN.
 *
 * This tag indicates the default MongoDB server connection string.
 */
define( "kDEFAULT_SERVER",					"mongodb://192.168.181.101:27017" );

/**
 * Default database name.
 *
 * This tag indicates the default database name.
 */
define( "kDEFAULT_DATABASE",				"GEO" );

/**
 * Default collection name.
 *
 * This tag indicates the default collection name.
 */
define( "kDEFAULT_COLLECTION",				"WORLDCLIM30" );

?>
