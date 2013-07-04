/**
 * Structure definitions.
 *
 * This file contains the common structure definitions.
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
 * Area structure.
 *
 * This structure contains the bounding box of an area:
 *
 * <ul>
 *	<li><b>latMin</b>: Minimum latitude of the area.
 *	<li><b>latMax</b>: Maximum latitude of the area.
 *	<li><b>lonMin</b>: Minimum longitude of the area.
 *	<li><b>lonMin</b>: Maximum longitude of the area.
 * </ul>
 */
struct AREA_T
{
	double latMin;		// Minimum latitude.
	double latMax;		// Maxilum latitude.
	double lonMin;		// Minimum longitude.
	double lonMax;		// Maxilum longitude.
};

/**
 * Tiles structure.
 *
 * This structure contains the information regarding the tiles that comprise the world
 * projection, this structure is divided into the following elements:
 *
 * <ul>
 *	<li><b>tileName</b>: The name of the tile, it is used to locate the relevant file, the
 *		expected directory structure is as follows:
 *	 <ul>
 *		<li><i>{@link kTilesDir kTilesDir}</i>: The base directory containing the tiles.
 *		 <ul>
 *			<li><i>Tiles folders</i>: Each tile has a folder containing a series of files
 *				all related to that specific tile; the contained files have the same base
 *				name as the folder.
 *			 <ul>
 *				<li><i>XXX.DEM</i>: Digital elevation model data.
 *				<li><i>XXX.HDR</i>: Header file for DEM.
 *				<li><i>XXX.DMW</i>: World file.
 *				<li><i>XXX.STX</i>: Statistics file.
 *				<li><i>XXX.PRJ</i>: Projection information file.
 *				<li><i>XXX.GIF</i>: Shaded relief image.
 *				<li><i>XXX.SRC</i>: Source map.
 *				<li><i>XXX.SCH</i>: Header file for source map.
 *			 </ul>
 *		 </ul>
 *	 </ul>
 *	<li><b>area</b>: Tile area bounds:
 *	 <ul>
 *		<li><b>latMin</b>: Minimum latitude of the tile.
 *		<li><b>latMax</b>: Maximum latitude of the tile.
 *		<li><b>lonMin</b>: Minimum longitude of the tile.
 *		<li><b>lonMin</b>: Maximum longitude of the tile.
 *	 </ul>
 * </ul>
 */
struct TILES_T
{
	string name;		// Tile name.
	AREA_T area;		// Tile area.
};

/**
 * WORLDCLIM file structure.
 *
 * This structure contains the information regarding the WORLDCLIM file data:
 *
 * <ul>
 *	<li><b>latMin</b>: Minimum latitude of the tile.
 *	<li><b>latMax</b>: Maximum latitude of the tile.
 *	<li><b>lonMin</b>: Minimum longitude of the tile.
 *	<li><b>lonMin</b>: Maximum longitude of the tile.
 *	<li><b>countY</b>: Number of vertical points.
 *	<li><b>countX</b>: Number of horizontal points.
 * </ul>
 */
struct WORLDCLIM_T
{
	string name;		// Tile name.
	string source;		// Data source.
	int months;			// Number of months.
	double latMin;		// Minimum latitude.
	double latMax;		// Maximum latitude.
	double lonMin;		// Minimum longitude.
	double lonMax;		// Maximum longitude.
	double countY;		// Number of rows (latitude points).
	double countX;		// Number of columns (longitude points).
};
