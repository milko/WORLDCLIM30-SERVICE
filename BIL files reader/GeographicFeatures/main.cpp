/**
 * Get coordinate geographic features.
 *
 * This file contains the main procedure for the geographic features web service.
 *
 * 
 *
 *	@package	WebServices
 *	@subpackage	GeographicFeatures
 *
 *	@author		Milko A. Škofič <m.skofic@cgiar.org>
 *	@version	1.00 06/01/2010
 */

/*=======================================================================================
 *																						*
 *										main.cpp										*
 *																						*
 *======================================================================================*/

/**
 * System includes.
 */
#include <iostream>
#include <fstream>
#include <sstream>
#include <string>
#include <CoreServices/CoreServices.h>

using namespace std;

/**
 * Local includes.
 */
#include "Errors.h"											// Error codes.
#include "Constants.h"										// Constants.

/**
 * WriteHeader.
 *
 * Write XML header to output.
 */
void WriteHeader( bool doClose = false );

/**
 * WriteLegend.
 *
 * Write XML legend to output.
 */
void WriteLegend();

/**
 * CheckArguments.
 *
 * Check provided arguments.
 */
int CheckArguments( const int theCount, char * const theArguments[] );

/**
 * GetLatitude.
 *
 * Parse latitude.
 */
int GetLatitude( char * const theArgument, double * theCoordinate );

/**
 * GetLongitude.
 *
 * Parse longitude.
 */
int GetLongitude( char * const theArgument, double * theCoordinate );

/**
 * SetCoordinate.
 *
 * Write coordinate element (and get altitude).
 */
int SetCoordinate( char * const theDirectory, double theLatitude, double theLongitude,
				  int * theAltitude );

/**
 * SetWORLDCLIMFeature.
 *
 * Write WORLDCLIM feature.
 */
int SetWORLDCLIMFeature( char * const theDirectory, double theLatitude, double theLongitude,
						 const int theFeature );


/**
 * MAIN.
 *
 * This command line tool expects three arguments:
 *
 * <ul>
 *	<li><b>Base directory</b> <i>[string]</i>: This string represents the base directory of
 *		the geographic features files, the path must be terminated by a '/' character and
 *		the referenced directory has the following structure:
 *	 <ul>
 *		<li><i>GTOPO30</i>: This directory contains the GTOPO 30 seconds elevation tiles,
 *			the directory contains 34 tiles covering the whole earth, each tile is
 *			represented by a directory containing a series of files named as the enclosing
 *			directory, of which the <i>.DEM</i> file represents the elevation raw data.
 *		<li><i>WORLDCLIM30</i>: This directory contains the WORLDCLIM climate datasets, it
 *			contains a series of directories each containin a <i>.bil</i> file representing
 *			the raw data and a <i>.hdr</i> file representing the header:
 *		 <ul>
 *			<li><i>alt</i>: Elevation in meters.
 *			<li><i>tmean</i>: Average monthly mean temperature in centigrades.
 *			<li><i>tmin</i>: Average monthly minimum temperature in centigrades.
 *			<li><i>tmax</i>: Average monthly maximum temperature in centigrades.
 *			<li><i>prec</i>: Average monthly precipitation in millimeters.
 *			<li><i>bio</i>: Bioclimatic variables, each variable is in a separate directory:
 *			 <ul>
 *				<li><i>BIO1</i>: Annual Mean Temperature.
 *				<li><i>BIO2</i>: Mean Diurnal Range (Mean of monthly (max temp - min temp)).
 *				<li><i>BIO3</i>: Isothermality (P2/P7) (* 100).
 *				<li><i>BIO4</i>: Temperature Seasonality (standard deviation *100).
 *				<li><i>BIO5</i>: Max Temperature of Warmest Month.
 *				<li><i>BIO6</i>: Min Temperature of Coldest Month.
 *				<li><i>BIO7</i>: Temperature Annual Range (P5-P6).
 *				<li><i>BIO8</i>: Mean Temperature of Wettest Quarter.
 *				<li><i>BIO9</i>: Mean Temperature of Driest Quarter.
 *				<li><i>BIO10</i>: Annual Mean Temperature.
 *				<li><i>BIO11</i>: Mean Temperature of Coldest Quarter.
 *				<li><i>BIO12</i>: Annual Precipitation.
 *				<li><i>BIO13</i>: Precipitation of Wettest Month.
 *				<li><i>BIO14</i>: Precipitation of Driest Month.
 *				<li><i>BIO15</i>: Precipitation Seasonality (Coefficient of Variation).
 *				<li><i>BIO16</i>: Precipitation of Wettest Quarter.
 *				<li><i>BIO17</i>: Precipitation of Driest Quarter.
 *				<li><i>BIO18</i>: Precipitation of Warmest Quarter.
 *				<li><i>BIO19</i>: Precipitation of Coldest Quarter.
 *			 </ul>
 *		 </ul>
 *	 </ul>
 *	<li><b>Latitude</b> <i>[double]</i>: The latitude expressed in decimal degrees.
 *	<li><b>Longitude</b> <i>[double]</i>: The longitude expressed in decimal degrees.
 * </ul>
 *
 * The function will return an XML
 * {@link http://schema.grinfo.net/Documentation/HTML/WSLocationGeographicFeatures.xsd.html
 * schema} structured as follows:
 *
 * <ul>
 *	<li><i>Coordinate</i>: This element will hold the provided coordinates and will resolve
 *		the coordinates elevation.
 *	<li><i>Feature</i>: This collection of elements will contain the geographic features
 *		described above, the element will hold the following information:
 *	 <ul>
 *		<li><i>value</i>: The value will hold the feature value.
 *		<li><i>Predicate</i>: This attribute holds the feature type as expressed above.
 *		<li><i>Reference</i>: This attribute holds the eventual month for the feature value,
 *			the month is expressed numerically.
 *		<li><i>Collection</i>: This attribute holds the feature data source.
 *	 </ul>
 * </ul>
 *
 * If there are any errors, these will be returned in a <i>Status</i> element in which the
 * <i>value</i> will be the error message and the <i>Severity</i> attribute will hold the
 * kind of exception: <i>NOTICE</i>, <i>WARNING</i> and <i>ERROR</i>.
 *
 * The function will return the XML structure to the standard output and the result code as
 * the function output.
 *
 * @package		WebServices
 * @subpackage	GeographicFeatures
 */
int main( int argc, char * const argv[] )
{
	//
	// Local storage.
	//
	int error, feature, theAltitude;
	double theLatitude, theLongitude;
	
	//
	// Check arguments.
	//
	if( (error = CheckArguments( argc, argv )) )
		return error;															// ==>
	
	//
	// Get latitude.
	//
	error = GetLatitude( argv[ 2 ], &theLatitude );
	if( error )
		return error;															// ==>
	
	//
	// Get longitude.
	//
	error = GetLongitude( argv[ 3 ], &theLongitude );
	if( error )
		return error;															// ==>
	
	//
	// Set coordinate.
	//
	error = SetCoordinate( argv[ 1 ], theLatitude, theLongitude, &theAltitude );
	if( error )
		return error;															// ==>
	
	//
	// Iterate WORLDCLIM features.
	//
	for( feature = 0; feature < kWORLDCLIM_FilesCount; feature++ )
	{
		//
		// Get feature.
		//
		error = SetWORLDCLIMFeature( argv[ 1 ], theLatitude, theLongitude, feature );
		if( error )
			return error;														// ==>
		
	} // Iterating features.
	
	//
	// Close XML message.
	//
	std::cout << "</WSLocationGeographicFeatures>";
	
	//
	// Exit.
	//
	return kERROR_OK;															// ==>
	
} // main.


/*===================================================================================
 *	WriteHeader																		*
 *==================================================================================*/

/**
 * Write XML header.
 *
 * This function will write the XML header to std::cout.
 *
 * @param boolean			doClose				TRUE means close element.
 *
 * @access public
 * @return void
 */
void WriteHeader( bool doClose )
{
	//
	// Write XML header.
	//
	std::cout << "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	
	//
	// Write root element.
	//
	std::cout << "<WSLocationGeographicFeatures ";
	std::cout << "xmlns=\"urn:bioversityinternational.org:schemas:standards\" ";
	std::cout << "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ";
	std::cout << "xsi:schemaLocation=\"urn:bioversityinternational.org:schemas:standards ";
	std::cout << "http://schema.grinfo.net/elements/WSLocationGeographicFeatures.xsd\"";
	
	//
	// Close element.
	//
	if( doClose )
		std::cout << ">\n";
	
} // WriteHeader.


/*===================================================================================
 *	WriteLegend																		*
 *==================================================================================*/

/**
 * Write XML header legend.
 *
 * This function will write the XML comment legend to std::cout.
 *
 * @access public
 * @return void
 */
void WriteLegend()
{
	//
	// Write legend.
	//
	std::cout << "\t<!--\n";
	std::cout << "\t\tThe Feature value contains the value.\n";
	std::cout << "\t\n";
	std::cout << "\t\tPredicate attribute:\n";
	
	//
	// Write predicates.
	//
	string tabs;
	for( int feature = 0; feature < kWORLDCLIM_FilesCount; feature++ )
	{
		//
		// Adjust TAB.
		//
		tabs = ( kWORLDCLIM_Tiles[ feature ].name.size() < 4 )
			 ? "\t\t"
			 : "\t";
		
		//
		// Output legend line.
		//
		std::cout << "\t\t\t" << kWORLDCLIM_Tiles[ feature ].name << tabs
							  << kWORLDCLIM_Tiles[ feature ].source << "\n";
		
	} // Iterating WORDCLIM features.

	//
	// Write other elements.
	//
	std::cout << "\t\n";
	std::cout << "\t\tReference attribute:\n";
	std::cout << "\t\t\tNumeric month [1 - 12].\n";
	std::cout << "\t\n";
	std::cout << "\t\tThe elevation in the Coordinate element is from GTOPO-30.\n";
	std::cout << "\t-->\n";
	
} // WriteLegend.


/*===================================================================================
 *	CheckArguments																	*
 *==================================================================================*/

/**
 * Check provided arguments.
 *
 * This function will check if the function received the correct number of arguments.
 *
 * @param const int			theCount			Arguments count.
 * @param char * const		theArguments		Arguments.
 *
 * @access public
 * @return int
 */
int CheckArguments( const int theCount, char * const theArguments[] )
{
	//
	// Check argument count.
	//
	if( theCount != 4 )
	{
		//
		// Write header.
		//
		WriteHeader( true );
		
		//
		// Write status exception.
		//
		std::cout << "\t<Status Severity=\"ERROR\">"
				  << "Invalid number of arguments, "
				  << "USAGE: WORDLCLIM directory latitude longitude"
				  << "</Status>\n";
		
		//
		// Close message.
		//
		std::cout << "</WSLocationGeographicFeatures>";
		
		return kERROR_INVALID_ARGUMENTS_COUNT;									// ==>
		
	} // Invalid argument count.
	
	return kERROR_OK;															// ==>
	
} // CheckArguments.


/*===================================================================================
 *	GetLatitude																		*
 *==================================================================================*/

/**
 * Parse latitude.
 *
 * This function will parse the provided latitude and return the value in the provided
 * argument.
 *
 * @param char * const		theArgument			Argument.
 * @param double *			theCoordinate		Receives latitude.
 *
 * @access public
 * @return int
 */
int GetLatitude( char * const theArgument, double * theCoordinate )
{
	//
	// Check latitude format.
	//
	if( EOF == sscanf( theArgument, "%lf", theCoordinate ) )
	{
		//
		// Write header.
		//
		WriteHeader( true );
		
		//
		// Send result.
		//
		std::cout << "\t<Status Severity=\"ERROR\">"
				  << "Invalid latitude format"
				  << "</Status>\n";
		
		//
		// Close message.
		//
		std::cout << "</WSLocationGeographicFeatures>";
		
		return kERROR_INVALID_LATITUDE_FORMAT;									// ==>
	
	} // Invalid format
	
	//
	// Check latitude range.
	//
	else
	{
		//
		// Check range.
		//
		if( (*theCoordinate > 90.0)
		 || (*theCoordinate <= -90.0) )
		{
			//
			// Write header.
			//
			WriteHeader( true );
			
			//
			// Send result.
			//
			std::cout << "\t<Status Severity=\"ERROR\">"
					  << "Invalid latitude range"
					  << "</Status>\n";
			
			//
			// Close message.
			//
			std::cout << "</WSLocationGeographicFeatures>\n";
			
			return kERROR_INVALID_LATITUDE_RANGE;								// ==>
			
		} // Invalid range.
		
	} // Valid coordinate format.

	return kERROR_OK;															// ==>
	
} // GetLatitude.


/*===================================================================================
 *	GetLongitude																	*
 *==================================================================================*/

/**
 * Parse longitude.
 *
 * This function will parse the provided longitude and return the value in the provided
 * argument.
 *
 * @param char * const		theArgument			Argument.
 * @param double *			theCoordinate		Receives longitude.
 *
 * @access public
 * @return int
 */
int GetLongitude( char * const theArgument, double * theCoordinate )
{
	//
	// Check longitude format.
	//
	if( EOF == sscanf( theArgument, "%lf", theCoordinate ) )
	{
		//
		// Write header.
		//
		WriteHeader( true );
		
		//
		// Send result.
		//
		std::cout << "\t<Status Severity=\"ERROR\">"
				  << "Invalid longitude format"
				  << "</Status>\n";
		
		//
		// Close message.
		//
		std::cout << "</WSLocationGeographicFeatures>";
		
		return kERROR_INVALID_LONGITUDE_FORMAT;									// ==>
		
	} // Invalid format
	
	//
	// Check latitude range.
	//
	else
	{
		//
		// Check range.
		//
		if( (*theCoordinate >= 180)
		 || (*theCoordinate < -180) )
		{
			//
			// Write header.
			//
			WriteHeader( true );
			
			//
			// Send result.
			//
			std::cout << "\t<Status Severity=\"ERROR\">"
					  << "Invalid longitude range"
					  << "</Status>\n";
			
			//
			// Close message.
			//
			std::cout << "</WSLocationGeographicFeatures>";
			
			return kERROR_INVALID_LONGITUDE_RANGE;								// ==>
			
		} // Invalid range.
		
	} // Valid coordinate format.
	
	return kERROR_OK;															// ==>
	
} // GetLongitude.


/*===================================================================================
 *	SetCoordinate																	*
 *==================================================================================*/

/**
 * Set coordinate element.
 *
 * This function will parse the altitude corresponding to the provided coordinates and
 * write the coordinate to output, the elevation will be retrieved from the GTOPO-30
 * dataset.
 *
 * If the coordinate lies in the sea, the function will write a <i>WARNING</i>
 * <i>Status</i> element.
 *
 * @param char * const		theDirectory		Base dataset directory path.
 * @param double			theLatitude			Latitude.
 * @param double			theLongitude		Longitude.
 * @param int *				theAltitude			Receives elevation.
 *
 * @access public
 * @return int
 */
int SetCoordinate( char * const theDirectory,
				   double theLatitude, double theLongitude,
				   int * theAltitude )
{
	//
	// Init local storage.
	//
	int tile = -1;
	
	//
	// Find tile.
	//
	while( ++tile < kGTOPO30_TilesCount )
	{
		//
		// Break if point is in tile.
		//
		if( (theLatitude > (double) kGTOPO30_Tiles[ tile ].area.latMin)
		 && (theLatitude <= (double) kGTOPO30_Tiles[ tile ].area.latMax)
		 && (theLongitude >= (double) kGTOPO30_Tiles[ tile ].area.lonMin)
		 && (theLongitude < (double) kGTOPO30_Tiles[ tile ].area.lonMax) )
			break;															// =>
		
	} // Iterating tiles.
	
	//
	// Check tile.
	//
	if( tile >= kGTOPO30_TilesCount )
	{
		//
		// Write header.
		//
		WriteHeader( true );
		
		//
		// Send result.
		//
		std::cout << "\t<Status Severity=\"ERROR\">"
				  << "Coordinates out of map"
				  << "</Status>\n";
		
		//
		// Close message.
		//
		std::cout << "</WSLocationGeographicFeatures>";
		
		return kERROR_COORDINATES_OUT_OF_MAP;									// ==>
		
	} // Out of map.
	
	//
	// Init local storage.
	//
	UInt64 lat_tiles, lon_tiles, offset_lat, offset_lon, offset_file;
	double unit_lat, unit_lon, lat_min, lat_max, lon_min, lon_max;
	
	//
	// Save tile rect.
	//
	lat_min = (double) kGTOPO30_Tiles[ tile ].area.latMin;
	lat_max = (double) kGTOPO30_Tiles[ tile ].area.latMax;
	lon_min = (double) kGTOPO30_Tiles[ tile ].area.lonMin;
	lon_max = (double) kGTOPO30_Tiles[ tile ].area.lonMax;
	
	//
	// Get tiles count.
	//
	lat_tiles = (double) (lat_max - lat_min) * ((double) kPointsLatDegree);
	lon_tiles = (double) (lon_max - lon_min) * ((double) kPointsLonDegree);
	
	//
	// Get tile units.
	//
	unit_lat = (lat_max - lat_min) / lat_tiles;
	unit_lon = (lon_max - lon_min) / lon_tiles;
	
	//
	// Calculate offsets.
	//
	offset_lat = ceil( (lat_max - theLatitude) * kPointsLatDegree );
	offset_lon = floor( (theLongitude - lon_min) * kPointsLonDegree );
	offset_file = (offset_lat * lon_tiles) + offset_lon;
	
	//
	// Get rect.
	//
	lat_min = lat_max - (offset_lat * unit_lat);
	lat_max = lat_min + unit_lat;
	
	lon_min = lon_min + (offset_lon * unit_lon);
	lon_max = lon_min + unit_lon;
	
	//
	// Write header.
	//
	WriteHeader();
	
	//
	// Write rect.
	//
	std::cout << " LatMin=\"" << lat_min << "\""
			  << " LatMax=\"" << lat_max << "\""
			  << " LonMin=\"" << lon_min << "\""
			  << " LonMax=\"" << lon_max << "\">\n";
	
	//
	// Write coordinate, latitude and longitude.
	//
	std::cout << "\t<Coordinate>\n"
			  << "\t\t<Latitude Degrees=\""
			  << theLatitude
			  << "\"/>\n"
			  << "\t\t<Longitude Degrees=\""
			  << theLongitude
			  << "\"/>\n";
	
	//
	// Init local storage.
	//
	SInt8 source;
	SInt16 altitude;
	ifstream file_dem, file_src;
	string datasource = "";
	string directory( theDirectory );
	string tmp_dir, file_dem_name, file_src_name;
	
	//
	// Create filenames.
	//
	tmp_dir = directory
			+ "GTOPO30/"
			+ kGTOPO30_Tiles[ tile ].name
			+ "/" + kGTOPO30_Tiles[ tile ].name;
	file_dem_name = tmp_dir + ".DEM";
	file_src_name = tmp_dir + ".SRC";
	
	//
	// Open files.
	//
	file_dem.open( file_dem_name.c_str(), ios::binary );
	file_src.open( file_src_name.c_str(), ios::binary );
	
	//
	// Read elevation.
	//
	bool done_val = false;
	if( file_dem.is_open() )
	{
		//
		// Read altitude.
		//
		file_dem.seekg( offset_file * 2 );
		file_dem.read( (char *) &altitude, kDataPointSize );
		file_dem.close();
		
		//
		// Rotate.
		//
		altitude = EndianS16_BtoN( altitude );
		
		//
		// Signal.
		//
		done_val = true;
		
	} // Opened DEM file.
	
	//
	// Read source.
	//
	bool done_src = false;
	if( file_src.is_open() )
	{
		//
		// Read altitude.
		//
		file_src.seekg( offset_file );
		file_src.read( (char *) &source, kSourcePointSize );
		file_src.close();
		
		//
		// Set data source.
		//
		datasource = kGTOPO30_Sources[ (UInt8) source ];
		
		//
		// Signal.
		//
		done_src = true;
		
	} // Opened SRC file.
	
	//
	// Write altitude.
	//
	if( done_val
	 || done_src )
	{
		//
		// Open element.
		//
		std::cout << "\t\t<Elevation";
		
		//
		// Write source.
		//
		if( done_src )
			std::cout << " Collection=\"GTOPO-30 " << datasource << "\"";
		
		//
		// Write value.
		//
		if( done_val )
		{
			//
			// Handle land.
			//
			if( altitude != kSeaToken )
			{
				//
				// Write value.
				//
				std::cout << ">" << altitude << "</Elevation>\n";
				
				//
				// Close coordinate.
				//
				std::cout << "\t</Coordinate>\n";
				
				//
				// Write legend.
				//
				WriteLegend();
				
			} // Coordinates in land.
				
			//
			// Handle sea.
			//
			else
			{
				//
				// Close element.
				//
				std::cout << "/>\n";

				//
				// Close coordinate.
				//
				std::cout << "\t</Coordinate>\n";
				
				//
				// Signal in sea.
				//
				if( altitude == kSeaToken )
					std::cout << "\t<Status Severity=\"WARNING\">"
							  << "Coordinates are out of land"
							  << "</Status>\n";
			
			} // Coordinates in sea.
			
		} // Got value.
		
	} // Got value or source.
	
	//
	// Signal missing altitude.
	//
	else
	{
		//
		// Close coordinate.
		//
		std::cout << "\t</Coordinate>\n";
		
		//
		// Signal warning.
		//
		std::cout << "\t<Status Severity=\"WARNING\">"
				  << "Unable to access GTOPO-30 files"
				  << "</Status>\n";
		
	} // Unable to open GTOPO-30 files.
	
	return kERROR_OK;															// ==>
	
} // SetCoordinate.


/*===================================================================================
 *	SetWORLDCLIMFeature																*
 *==================================================================================*/

/**
 * Parse WORLDCLIM feature.
 *
 * This function will retrieve the feature referenced by <i>theFeature</i> and the eventual
 * <i>theMonth</i> from the WORLDCLIM dataset and return it in a <i>Feature</i> element.
 *
 * @param char * const		theDirectory		Base dataset directory path.
 * @param double			theLatitude			Latitude.
 * @param double			theLongitude		Longitude.
 * @param const int			theFeature			Feature index.
 * @param const int			theMonth			Feature month.
 *
 * @access public
 * @return int
 */
int SetWORLDCLIMFeature( char * const theDirectory, double theLatitude, double theLongitude,
						 const int theFeature )
{
	//
	// Check feature.
	//
	if( (theFeature < 0)
	 || (theFeature >= kWORLDCLIM_FilesCount) )
	{
		//
		// Send result.
		//
		std::cout << "\t<Status Severity=\"BUG\">"
				  << "Invalid feature index ["
				  << theFeature
				  << "]</Status>\n";
		
		//
		// Close message.
		//
		std::cout << "</WSLocationGeographicFeatures>";
		
		return kERROR_COORDINATES_OUT_OF_MAP;									// ==>
		
	} // Invalid feature index.
	
	//
	// Init local storage.
	//
	SInt16 feature;
	UInt64 lat_tiles, lon_tiles, offset_lat, offset_lon, offset_file;
	double unit_lat, unit_lon, lat_min, lat_max, lon_min, lon_max;
	
	//
	// Save tile rect.
	//
	lat_min = (double) kWORLDCLIM_Tiles[ theFeature ].latMin;
	lat_max = (double) kWORLDCLIM_Tiles[ theFeature ].latMax;
	lon_min = (double) kWORLDCLIM_Tiles[ theFeature ].lonMin;
	lon_max = (double) kWORLDCLIM_Tiles[ theFeature ].lonMax;
	
	//
	// Save tiles count.
	//
	lat_tiles = kWORLDCLIM_Tiles[ theFeature ].countY;
	lon_tiles = kWORLDCLIM_Tiles[ theFeature ].countX;
	
	//
	// Get tile units.
	//
	unit_lat = (lat_max - lat_min) / lat_tiles;
	unit_lon = (lon_max - lon_min) / lon_tiles;
	
	//
	// Calculate offsets.
	//
	offset_lat = ceil( (lat_max - theLatitude) * kPointsLatDegree );
	offset_lon = floor( (theLongitude - lon_min) * kPointsLonDegree );
	offset_file = (offset_lat * lon_tiles) + offset_lon;
	
	//
	// Create filename.
	//
	string directory( theDirectory );
	string file_name = directory + "WORLDCLIM30/"
								 + kWORLDCLIM_Tiles[ theFeature ].name
								 + "/"
								 + kWORLDCLIM_Tiles[ theFeature ].name;
	
	//
	// Handle months.
	//
	if( kWORLDCLIM_Tiles[ theFeature ].months )
	{
		//
		// Iterate months.
		//
		int month;
		string cur_name;
		char buffer [64];
		for( month = 1; month <= kWORLDCLIM_Tiles[ theFeature ].months; month++ )
		{
			//
			// Get month string.
			//
			sprintf( buffer, "%d", month );
			string month_string( buffer );
			
			//
			// Update file name.
			//
			cur_name = file_name + "_" + month_string + ".bil";
			
			//
			// Open file.
			//
			ifstream file;
			file.open( cur_name.c_str(), ios::binary );
			if( file.is_open() )
			{
				//
				// Read feature.
				//
				file.seekg( offset_file * 2 );
				file.read( (char *) &feature, kDataPointSize );
				file.close();
				
				//
				// Handle land.
				//
				if( feature != kSeaToken )
				{
					//
					// Write feature.
					//
					std::cout << "\t<Feature Predicate=\""
							  << kWORLDCLIM_Tiles[ theFeature ].name
							  << "\" Reference=\""
							  << month
					//		  << "\" Collection=\""
					//		  << kWORLDCLIM_Tiles[ theFeature ].source
							  << "\">"
							  << feature
							  << "</Feature>\n";
					
				} // In land.
				
			} // Opened file.
			
		} // Iterating months.
		
	} // Has months.
	
	//
	// Handle no months.
	//
	else
	{
		//
		// Add extension.
		//
		file_name += ".bil";
		
		//
		// Open file.
		//
		ifstream file;
		file.open( file_name.c_str(), ios::binary );
		if( file.is_open() )
		{
			//
			// Read feature.
			//
			file.seekg( offset_file * 2 );
			file.read( (char *) &feature, kDataPointSize );
			file.close();
			
			//
			// Handle land.
			//
			if( feature != kSeaToken )
			{
				//
				// Write feature.
				//
				std::cout << "\t<Feature Predicate=\""
						  << kWORLDCLIM_Tiles[ theFeature ].name
				//		  << "\" Collection=\""
				//		  << kWORLDCLIM_Tiles[ theFeature ].source
						  << "\">"
						  << feature
						  << "</Feature>\n";
				
			} // In land.
			
		} // Opened file.
		
	} // Has no months.
	
	return kERROR_OK;															// ==>

} // SetWORLDCLIMFeature.
