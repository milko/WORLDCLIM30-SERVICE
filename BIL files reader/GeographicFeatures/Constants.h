/**
 * Constants definitions.
 *
 * This file contains the common constants definitions.
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

#include "Structures.h"


/**
 * GTOPO-30 tiles count.
 *
 * This constant holds the number of tiles.
 */
const int kGTOPO30_TilesCount = 34;

/**
 * WORLDCLIM files count.
 *
 * This constant holds the number of files.
 */
const int kWORLDCLIM_FilesCount = 24;

/**
 * Sources count.
 *
 * This constant holds the number of sources.
 */
const int kGTOPO30_SourcesCount = 10;

/**
 * Sea altitude.
 *
 * This constant holds the altitude value of coordinates in the sea for the <i>DEM</i>
 * files.
 */
const SInt16 kSeaToken = -9999;

/**
 * Number of data points per latitude degree.
 *
 * This constant holds the number of data points per latitude degree.
 */
const int kPointsLatDegree = 120;

/**
 * Number of data points per longitude degree.
 *
 * This constant holds the number of data points per longitude degree.
 */
const int kPointsLonDegree = 120;

/**
 * Source point size.
 *
 * This constant holds the size in bytes of the source points.
 */
const ifstream::pos_type kSourcePointSize = 1;

/**
 * Data point size.
 *
 * This constant holds the size in bytes of the data points.
 */
const ifstream::pos_type kDataPointSize = 2;

/**
 * GTOPO-30 tiles data.
 *
 * Here we allocate and fill the tiles information.
 */
TILES_T kGTOPO30_Tiles [ kGTOPO30_TilesCount ] =
{
	"ANTARCPS", -90, -60, -180, 180,
	"W180S60", -90, -60, -180, -120,
	"W120S60", -90, 60, -120, -60,
	"W060S60", -90, -60, -60, 0,
	"W000S60", -90, -60, 0, 60,
	"E060S60", -90, -60, 60, 120,
	"E120S60", -90, -60, 120, 180,
	"W180S10", -60, -10, -180, -140,
	"W180N90", 40, 90, -180, -140,
	"W180N40", -10, 40, -180, -140,
	"W140S10", -60, -10, -140, -100,
	"W140N90", 40, 90, -140, -100,
	"W140N40", -10, 40, -140, -100,
	"W100S10", -60, -10, -100, -60,
	"W100N90", 40, 90, -100, -60,
	"W100N40", -10, 40, -100, -60,
	"W060S10", -60, -10, -60, -20,
	"W060N90", 40, 90, -60, -20,
	"W060N40", -10, 40, -60, -20,
	"W020S10", -60, -10, -20, 20,
	"W020N90", 40, 90, -20, 20,
	"W020N40", -10, 40, -20, 20,
	"E020S10", -60, -10, 20, 60,
	"E020N90", 40, 90, 20, 60,
	"E020N40", -10, 40, 20, 60,
	"E020N40", -60, -10, 60, 100,
	"E060N90", 40, 90, 60, 100,
	"E060N40", -10, 40, 60, 100,
	"E100S10", -60, -10, 100, 140,
	"E100N90", 40, 90, 100, 140,
	"E100N40", -10, 40, 100, 140,
	"E100N40", -60, -10, 140, 180,
	"E140N90", 40, 90, 140, 180,
	"E140N40", -10, 40, 140, 180
};

/**
 * GTOPO-30 data sources list.
 *
 * Here we allocate the WORLDCLIM tiles information.
 */
const string kGTOPO30_Sources [ kGTOPO30_SourcesCount ] = 
{
	"Ocean",
	"Digital Terrain Elevation Data",
	"Digital Chart of the World",
	"USGS 1-degree DEM",
	"Army Map Service 1:1,000,000-scale maps",
	"International Map of the World 1:1,000,000-scale maps",
	"Peru 1:1,000,000-scale map",
	"New Zealand DEM",
	"Antarctic Digital Database",
	"SRTM data"
};

/**
 * WORLDCLIM tiles data.
 *
 * This array of strings contains the sources of the data.
 */
WORLDCLIM_T kWORLDCLIM_Tiles [ kWORLDCLIM_FilesCount ] =
{
	{
		"alt",
		"Shuttle Radar Topography Mission (SRTM) (30 sec.)",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"tmean",
		"WORLDCLIM 30 sec. average monthly mean temperature [C° * 10]",
		12,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"tmin",
		"WORLDCLIM 30 sec. average monthly minimum temperature [C° * 10]",
		12,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"tmax",
		"WORLDCLIM 30 sec. average monthly maximum temperature [C° * 10]",
		12,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"prec",
		"WORLDCLIM 30 sec. average monthly precipitation [mm.]",
		12,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio1",
		"WORLDCLIM 30 sec. Annual Mean Temperature [C° * 10]",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio2",
		"WORLDCLIM 30 sec. Mean Diurnal Range (Mean of monthly (max temp - min temp)) [C° * 10]",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio3",
		"WORLDCLIM 30 sec. Isothermality (P2/P7) (* 100)",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio4",
		"WORLDCLIM 30 sec. Temperature Seasonality (standard deviation *100)",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio5",
		"WORLDCLIM 30 sec. Maximum Temperature of Warmest Month [C° * 10]",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio6",
		"WORLDCLIM 30 sec. Minimum Temperature of Coldest Month [C° * 10]",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio7",
		"WORLDCLIM 30 sec. Temperature Annual Range (P5-P6)",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio8",
		"WORLDCLIM 30 sec. Mean Temperature of Wettest Quarter [C° * 10]",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio9",
		"WORLDCLIM 30 sec. Mean Temperature of Driest Quarter [C° * 10]",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio10",
		"WORLDCLIM 30 sec. Mean Temperature of Warmest Quarter [C° * 10]",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio11",
		"WORLDCLIM 30 sec. Mean Temperature of Coldest Quarter [C° * 10]",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio12",
		"WORLDCLIM 30 sec. Annual Precipitation",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio13",
		"WORLDCLIM 30 sec. Precipitation of Wettest Month",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio14",
		"WORLDCLIM 30 sec. Precipitation of Driest Month",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio15",
		"WORLDCLIM 30 sec. Precipitation Seasonality (Coefficient of Variation)",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio16",
		"WORLDCLIM 30 sec. Precipitation of Wettest Quarter",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio17",
		"WORLDCLIM 30 sec. Precipitation of Driest Quarter",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio18",
		"WORLDCLIM 30 sec. Precipitation of Warmest Quarter",
		0,
		-60, 90, -180, 180, 18000, 43200
	},
	{
		"bio19",
		"WORLDCLIM 30 sec. Precipitation of Coldest Quarter",
		0,
		-60, 90, -180, 180, 18000, 43200
	}
};
