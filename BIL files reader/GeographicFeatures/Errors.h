/**
 * Errors definitions.
 *
 * This file contains the error and exception definitions.
 *
 *	@package	WebServices
 *	@subpackage	GeographicFeatures
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 06/01/2010
 */

#include <iostream>
#include <fstream>
#include <sstream>
#include <string>
#include <CoreServices/CoreServices.h>

using namespace std;


/**
 * Result codes.
 *
 * These constants hold the result codes.
 */
const int kERROR_OK									= 0;
const int kERROR_INVALID_ARGUMENTS_COUNT			= 1;
const int kERROR_INVALID_LATITUDE_FORMAT			= 10;
const int kERROR_INVALID_LATITUDE_RANGE				= 12;
const int kERROR_INVALID_LONGITUDE_FORMAT			= 18;
const int kERROR_INVALID_LONGITUDE_RANGE			= 20;
const int kERROR_COORDINATES_OUT_OF_MAP				= 32;
const int kERROR_INVALID_FEATURE_REFERENCE			= 64;
