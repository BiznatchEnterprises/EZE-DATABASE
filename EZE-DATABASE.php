<?php
/* ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 * |||      EZE-DATABASE BETA 1.7 - (C) 2002-2018 Biznatch Enterprises      |||
 * |||    Double Nested Database Parser/Synthesizer with unique data IDs    |||
 * |||           MIT License https://opensource.org/licenses/MIT          	|||
 * |||      Copyright (C) 2002 Biznatch Enterprises - Biznaturally.ca     	|||
 * |||                                                                      |||
 * ||| EZE-DATABASE LOADER - function INPUTS                       			|||
 * ||| $DBS_COMMAND                                                         |||
 * ||| $LOADER_FILENAME     												|||
 * ||| $DBS_FILENAME                                                        |||
 * ||| $DBS_OPTION1 														|||
 * ||| $DBS_OPTION3 													 	|||
 * ||| $DBS_OPTION4  														|||
 * ||| $DBS_OPTION5 														|||
 * |||																		|||
 * ||| EZE-DATABASE INPUTS (This Script file):							  	|||
 * ||| $EZE_DBS_CMD = $DBSCOMMAND;											|||
 * ||| $EZE_DBS_FILE = $DBSFILENAME;										|||
 * ||| $EZE_DBS_OPT1 														|||
 * ||| $EZE_DBS_OPT2 														|||
 * ||| $EZE_DBS_OPT3 														|||
 * ||| $EZE_DBS_OPT4 														|||
 * ||| $EZE_DBS_OPT5 														|||
 * ||| $DBS_STATUS true/false (echo debuging)								|||
 * |||                                                                      |||
 * |||   Permission is hereby granted, free of charge, to any person      	|||
 * |||   obtaining a copy of this software and associated documentation   	|||
 * |||   files (the "Software"), to deal in the Software without          	|||
 * |||   restriction, including without limitation the rights to use,     	|||
 * |||   copy, modify, merge, publish, distribute, sublicense, and/or     	|||
 * |||   sell copies of the Software, and to permit persons to whom the   	|||
 * |||   Software is furnished to do so, subject to the following         	|||
 * |||   conditions: The above copyright notice and this permission       	|||
 * |||   notice shall be included in all copies or substantial portions   	|||
 * |||   of the Software. THE SOFTWARE IS PROVIDED "AS IS", WITHOUT       	|||
 * |||   WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT      	|||
 * |||   LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A      	|||
 * |||   PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE    	|||
 * |||   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES    	|||
 * |||   OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR    	|||
 * |||   OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE        	|||
 * |||   SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.           	|||
 * ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/

$NEWLINE = chr(13) . chr(10);           // WIN/DOS: chr(13) . chr(10)    UNIX: "\n"

$LEVEL1_START = '<á'; 		// PARTITION START
$LEVEL1_END = 'á>'; 		// PARTITION END
$LEVEL2_START = 'à['; 		// SECTOR START
$LEVEL2_END = ']à'; 		// SECTOR END

$LABEL_START = chr(223) . '{'; 			// SECTOR LABEL START
$LABEL_END = '}' . chr(223); 			// SECTOR LABEL END

// ^^^^^^^^^^^^ START ^^^^^^^^^^^^
// @ Command: AddPartition
// @ Action: Adds New Partition (with data sectors) To Database
// @ Param string EZE_DBS_File
// @ Param: array New Sector Data
// @ Param: string DBS_STATUS TRUE\FALSE
// @ Returns: EZE-DATABASE ERROR: Partition Exists!
// @ Returns: EZE-DATABASE OK
// @ Returns: EZE-DATABASE ERROR: Write Failed!
// @ Returns: EZE-DATABASE ERROR: Invalid Partition Selected!
// @ Status: Invalid Partition Selected!
// @ Status: Partition Already Exists!
// @ Status: Add New Partition + Sectors to Database Completed
// @ Status: Database Update Error, Contact Admin!
// 
if ($EZE_DBS_CMD == 'AddPartition')
{
	//SYNTHESIZE DATABASE (Assemble and update database file with new partition containing sectors)
	$NEWSECTORS = $EZE_DBS_OPT1;
	$DBS_CONTENT = LOAD_RAWDATA($EZE_DBS_FILE);
	
	//no duplicate first sectors
	if ($NEWSECTORS[1] == '')
	{ 
		$EZE_DBS_OUTPUT[0][0] = 'Invalid Partition Selected!';
		return 'EZE-DATABASE ERROR: Invalid Partition Selected!';
 	}
	
	$FIND_ID = $LEVEL1_START . $LEVEL2_START . $NEWSECTORS[1] . $LEVEL2_END;
	$FIRST_SECTOR = strpos($DBS_CONTENT, $FIND_ID);  //search for first partition sector (identifier) no duplicate first sectors
	if ($FIRST_SECTOR > 0)
	{ 
		$EZE_DBS_OUTPUT[0][0] = 'Partition Already Exists!';
		return 'EZE-DATABASE ERROR: Partition Exists!';
	}

	//add new partition to end of dbs... add  chr(13) chr(10) after each partition
	$TEMP_DATA = $LEVEL1_START;
	$SECTOR_COUNT = 1;
	foreach ($NEWSECTORS as $TEMP_SECTORS)
	{
		$TEMP_DATA = $TEMP_DATA . $LEVEL2_START . $TEMP_SECTORS . $LEVEL2_END;
		$SECTOR_COUNT = $SECTOR_COUNT + 1;
	}
	$TEMP_DATA = $TEMP_DATA . $LEVEL1_END . chr(13) . chr(10);
	$DBS_CONTENT = $DBS_CONTENT . $TEMP_DATA;

	//write dbs file
	WRITE_RAWDATA($EZE_DBS_FILE, $DBS_CONTENT);
	
	//verify database addition + confirm with msg
	$DBS_CONTENT = LOAD_RAWDATA($EZE_DBS_FILE);

	$FIND_ID = $LEVEL1_START . $LEVEL2_START . $NEWSECTORS[1] . $LEVEL2_END;
	$FIRST_SECTOR = strpos($DBS_CONTENT, $FIND_ID);  //search for first partition sector (identifier)

	if ($FIRST_SECTOR > 0)
	{ 
		if ($DBS_STATUS == 'TRUE')
		{
			echo "<br><b>Database Call-ADD</b>: " . $EZE_DBS_FILE . " - Add New Partition + Sectors to Database Completed<br>";
		}

		$EZE_DBS_OUTPUT[0][0] = 'Add New Partition + Sectors to Database Completed';

		return 'EZE-DATABASE OK';
	}
	else
	{
		$EZE_DBS_OUTPUT[0][0] = 'Database Update Error, possible file permissions or hard drive error';
		return 'EZE-DATABASE ERROR: Write Failed!';
	}

}
// ^^^^^^^^^^^^^ END ^^^^^^^^^^^^^

// ^^^^^^^^^^^^ START ^^^^^^^^^^^^
// @ Command: UpdatePartition
// @ Action: Replace Sectors in Partition
// @ Param string EZE_DBS_File
// @ Param: FirstSector (Partition ID)
// @ Param: array Find_Data
// @ Param: array Replace_Data
// @ Param: string DBS_STATUS TRUE\FALSE
// @ Returns: EZE-DATABASE ERROR - Invalid Partition Selected!
// @ Status: Invalid Partition Selected!
// @ Returns: Partition Does Not Exist: Partition_Number
// @ Status: EZE-DATABASE ERROR: Partition Does Exist!
// @ Returns: Update Partition Sectors Database Completed
// @ Status: EZE-DATABASE Updated
// 
if ($EZE_DBS_CMD == 'UpdatePartition')
{

	// SYNTHESIZE DATABASE (Assemble and update database file with changes only)
	//change multiple sectors in the SAME partition as FIRSTSECTOR input... replaces only found sectors and not entire partition (will not delete sectors)
	$FIRSTSECTOR = $EZE_DBS_OPT1;  //partition identify (first sector)
	$SECTORSOURCES = $EZE_DBS_OPT2;
	$SECTORDESTINATIONS = $EZE_DBS_OPT3;

	//read dbs file (unparsed)
	$DBS_CONTENT = LOAD_RAWDATA($EZE_DBS_FILE);

	if ($FIRSTSECTOR == '')
	{ 
		$EZE_DBS_OUTPUT[0][0] = 'Invalid Partition Selected!';
		return 'EZE-DATABASE ERROR - Invalid Partition Selected!';
	}

	$find_string = $LEVEL1_START . $LEVEL2_START . $FIRSTSECTOR . $LEVEL2_END;

	//search for first partition sector (identity) no duplicate first sectors
	$FIRST_SECTOR = strpos(strtolower($DBS_CONTENT), strtolower($findme));
	if ($FIRST_SECTOR > 0)
	{ 
		$find_string = $LEVEL1_END;
		$FIRST_SECTORSTART = strpos(strtolower($DBS_CONTENT), strtolower($find_string), $FIRST_SECTOR);
		//parse partition and first sector into string
		$PARTITIONO_RAW = substr($DBS_CONTENT, $FIRST_SECTOR, $FIRST_SECTORSTART - $FIRST_SECTOR + 4);
	}
	else
	{
		$EZE_DBS_OUTPUT[0][0] = 'Partition Does Not Exist: ' . $FIRSTSECTOR;
		return 'EZE-DATABASE ERROR: Partition Does Exist!';
	}

	$PARTITION = $PARTITIONO_RAW;

	//replace partition searches only not entire partition
	for ($tmp_partition = 1; $tmp_partition <= count($SECTORSOURCES); $tmp_partition++)
	{	
		if ($SECTORSOURCES[$tmp_partition] <> '')
		{
			$find_string = $LEVEL2_START . $SECTORSOURCES[$tmp_partition] . $LEVEL2_END;
			$change_string = $LEVEL2_START . $SECTORDESTINATIONS[$tmp_partition] . $LEVEL2_END;
			//it doesn't verify if the search sectors are found in the partition so the error msg doesn't reflect that..
			//custom finder needed add not found ones to an error log and display after (not found)
			//echo $find_string . ' ' . $changeme . '<br>';
			$PARTITION = str_replace($find_string, $change_string, $PARTITION);
			//echo $SECTORSOURCES[$tmp_partition] . " -> " . $SECTORDESTINATIONS[$tmp_partition] . "<br>";
		}
	}

	$DBS_CONTENT = str_replace($PARTITIONO_RAW, $PARTITION, $DBS_CONTENT);
	//verify changed sectors by refinding them... generate log (not changed)

	//write dbs file (add error checking later)
	WRITE_RAWDATA($EZE_DBS_FILE, $DBS_CONTENT);

	if ($DBS_STATUS == 'TRUE'){ 
		echo "<br><b>Database Call-UPDATE</b>: " . $EZE_DBS_FILE . " - Update Partition Sectors Database Completed: " . $FIRSTSECTOR . " " . $temp_partition;
	}

	$EZE_DBS_OUTPUT[0][0] = 'Update Partition Sectors Database Completed';
	return 'EZE-DATABASE Updated';

}
// ^^^^^^^^^^^^^ END ^^^^^^^^^^^^^

// ^^^^^^^^^^^^ START ^^^^^^^^^^^^
// @ Command: ReadDB
// @ Action: Import from file, Parse into Double-Array
// @ Returns: array (Dual Dimension) of Parsed Database File
// @ Status: Parse Database Completed
//
if ($EZE_DBS_CMD == 'ReadDB')
{
	//PARSE DATABASE (Open database file and Disassemble into global array $EZE_DBS_OUTPUT)
	$DBScontent = LOAD_RAWDATA($EZE_DBS_FILE);
	$DBSoutput = array();
	$tmp_partitions = 1;
	$tmp_sectors = 1;
	$DBS_Layer1 = New EZE_DATABASE;
	$DBS_Layer1->ParseDatabase($DBScontent, $LEVEL1_START, $LEVEL1_END);
	$DBS_Layer1_Total = $DBS_Layer1->EZEDBS_ENTRIES;

	for ($tmp_partnumber = 0; $tmp_partnumber <= $DBS_Layer1_Total - 1; $tmp_partnumber++)
	{
		$DBS_Layer2 = New EZE_DATABASE;
		$DBS_Layer2->ParseDatabase($DBS_Layer1->EZEDBS_DATA[$tmp_partnumber], $LEVEL2_START, $LEVEL2_END);
		$DBS_Layer2_Total = $DBS_Layer2->EZEDBS_ENTRIES;

		for ($tmp_sectornumber = 0; $tmp_sectornumber <= $DBS_Layer2_Total - 1; $tmp_sectornumber++)
		{
			if ($DBS_Layer2->EZEDBS_DATA[$tmp_sectornumber] <> '')
			{
				$DBSoutput[$tmp_partitions][$tmp_sectors] = $DBS_Layer2->EZEDBS_DATA[$tmp_sectornumber];
			}
				$tmp_sectors = $tmp_sectors + 1;
		}

	$tmp_sectors = 1;
	$tmp_partitions = $tmp_partitions + 1;
	}

	$tmp_partitions = null;
	$tmp_sectors = null;
	$EZE_DBS_OUTPUT = $DBSoutput;
	$EZE_DBS_OUTPUT[0][0] = 'Parse Database Completed';
	$EZE_DBS_OUTPUT[0][1] = $DBS_Layer1_Total;

	if ($DBS_STATUS == 'TRUE')
	{
		echo "<br><b>Database Call-READ</b>: " . $EZE_DBS_FILE . " - Read + Parse Database Completed<br>";
	}

	return 'Read-DBS Completed';
}
// ^^^^^^^^^^^^^ END ^^^^^^^^^^^^^


// ^^^^^^^^^^^^ START ^^^^^^^^^^^^
// @ Command: WriteDB
// @ Action: Full raw save to file (2-levels)
// @ Param: DATABASE_DATA array
// @ Returns: Write-DBS Completed
// @ Status: Write Database Completed
//
if ($EZE_DBS_CMD == 'WriteDB')
{
	$RAW_DATA = urldecode('%3C%3Fphp+exit%3B+%3F%3E') . "\n";
	$RAW_DATATMP = "";
	$DATABASE_DATA = $EZE_DBS_OPT1;

	for ($tmp_partnum = 0; $tmp_partnum <= count($DATABASE_DATA); $tmp_partnum++)
	{
		$RAW_DATATMP = "";

		if (isset($DATABASE_DATA[$tmp_partnum]) == true)
		{
			for ($tmp_sectornum = 0; $tmp_sectornum <= count($DATABASE_DATA[$tmp_partnum]); $tmp_sectornum++)
			{
				if (isset($DATABASE_DATA[$tmp_partnum][$tmp_sectornum]) == true)
				{
					if ($DATABASE_DATA[$tmp_partnum][$tmp_sectornum] != "")
					{
						$RAW_DATATMP = $RAW_DATATMP . $LEVEL2_START . $DATABASE_DATA[$tmp_partnum][$tmp_sectornum] . $LEVEL2_END;	
					}
				}
			}
	
			if ($RAW_DATATMP != "")
			{
				$RAW_DATA = $RAW_DATA . $LEVEL1_START . $RAW_DATATMP . $LEVEL1_END . "\n";
			}
		}

	}

	WRITE_RAWDATA($EZE_DBS_FILE, $RAW_DATA);

	$EZE_DBS_OUTPUT[0][0] = 'Write Database Completed';
	$EZE_DBS_OUTPUT[0][1] = $tmp_partnum - 1;

	if ($DBS_STATUS == 'TRUE')
	{
		echo "<br><b>Database Call-Write</b>: " . $EZE_DBS_FILE . " - Completed<br>";
	}

	return 'Write-DBS Completed';
}
// ^^^^^^^^^^^^^ END ^^^^^^^^^^^^^

// ^^^^^^^^^^^^ START ^^^^^^^^^^^^
// @ Command: FindPartition
// @ Action: NOT WORKING
// @ Param: 
// @ Returns: 
//
if ($EZE_DBS_CMD == 'FindPartition')
{


	//FindPartition by first sector name
	//$FIRSTSECTOR = $EZE_DBS_OPT1;

	//FindSector in Partition

	//FindSector (display all parition names[sector1] that have sector with string in it)

	//rawsearchdbs (advanced)

}
// ^^^^^^^^^^^^^ END ^^^^^^^^^^^^^

// ^^^^^^^^^^^^ START ^^^^^^^^^^^^
// @ Command: DelPartition
// @ Action: NOT WORKING
// @ Param: 
// @ Returns: 
//
if ($EZE_DBS_CMD == 'DelPartiton')
{

	// SYNTHESIZE DATABASE (Assemble and update database file with delete partitions (and all sectors))
	//$FIRSTSECTOR = string;  //partition ident
	//read dbs file (unparsed)
	//search for first partition sector (identifier)
	//no duplicate first sectors
	//add new partition to end chr(13) chr(10) after each partition
	//write dbs file

}
// ^^^^^^^^^^^^^ END ^^^^^^^^^^^^^

?>
