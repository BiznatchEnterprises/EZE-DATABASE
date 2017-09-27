<?php
/* ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 * |||      EZE-DATABASE BETA 1.6 - (C) 2002-2017 Biznatch Enterprises      |||
 * |||    Double Nested Database Parser/Synthesizer with unique data IDs    |||
 * |||           MIT License https://opensource.org/licenses/MIT            |||
 * |||      Copyright (C) 2017 Biznatch Enterprises - Biznaturally.ca       |||
 * |||                                                          	    |||
 * ||| EZE-DATABASE LOADER - function INPUTS                   	            |||
 * ||| $DBS_COMMAND                                     	            |||
 * ||| $LOADER_FILENAME     						    |||
 * ||| $DBS_FILENAME                                                        |||
 * ||| $DBS_OPTION1 							    |||
 * ||| $DBS_OPTION3 							    |||
 * ||| $DBS_OPTION4  							    |||
 * ||| $DBS_OPTION5 							    |||
 * |||									    |||
 * ||| EZE-DATABASE INPUTS (This Script file):				    |||
 * ||| $EZE_DBS_CMD = $DBSCOMMAND;					    |||
 * ||| $EZE_DBS_FILE = $DBSFILENAME;					    |||
 * ||| $EZE_DBS_OPT1 							    |||
 * ||| $EZE_DBS_OPT2 							    |||
 * ||| $EZE_DBS_OPT3 							    |||
 * ||| $EZE_DBS_OPT4 							    |||
 * ||| $EZE_DBS_OPT5 							    |||
 * ||| $DBS_STATUS true/false (echo debuging)				    |||
 * |||                                                                      |||
 * |||   Permission is hereby granted, free of charge, to any person        |||
 * |||   obtaining a copy of this software and associated documentation     |||
 * |||   files (the "Software"), to deal in the Software without            |||
 * |||   restriction, including without limitation the rights to use,       |||
 * |||   copy, modify, merge, publish, distribute, sublicense, and/or       |||
 * |||   sell copies of the Software, and to permit persons to whom the     |||
 * |||   Software is furnished to do so, subject to the following           |||
 * |||   conditions: The above copyright notice and this permission         |||
 * |||   notice shall be included in all copies or substantial portions     |||
 * |||   of the Software. THE SOFTWARE IS PROVIDED "AS IS", WITHOUT         |||
 * |||   WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT        |||
 * |||   LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A        |||
 * |||   PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE      |||
 * |||   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES      |||
 * |||   OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR      |||
 * |||   OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE          |||
 * |||   SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.             |||
 * ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/


$NEWLINE = chr(13) . chr(10);           // WIN/DOS: chr(13) . chr(10)    UNIX: "\n"

$LEVEL1_START = '<' . chr(225); 		// PARTITION START
$LEVEL1_END = chr(225) . '>'; 			// PARTITION END
$LEVEL2_START = chr(224) . '['; 		// SECTOR START
$LEVEL2_END = ']' . chr(224); 			// SECTOR END

$LABEL_START = chr(223) . '{'; 			// SECTOR LABEL START
$LABEL_END = '}' . chr(223); 			// SECTOR LABEL END


//-----------------------------------------------------------------
if ($EZE_DBS_CMD == 'AddPartition')
{
	//SYNTHESIZE DATABASE (Assemble and update database file with new partition containing sectors)
	$NEWSECTORS = $EZE_DBS_OPT1;
	$DBScontent = LOAD_RAWDATA($EZE_DBS_FILE);
	
	//no duplicate first sectors
	if ($NEWSECTORS[1] == '')
	{ 
	$EZE_DBS_OUTPUT[0][0] = 'Invalid Partition Selected!';
	return 'EZE-DATABASE ERROR: Invalid Partition Selected!';
 	}
	
	$findme = $LEVEL1_START . $LEVEL2_START . $NEWSECTORS[1] . $LEVEL2_END;
	$fs = strpos($DBScontent, $findme);  //search for first partition sector (identifyr) no duplicate first sectors
		if ($fs > 0)
		{ 
			$EZE_DBS_OUTPUT[0][0] = 'Partition Already Exists!';
			return 'EZE-DATABASE ERROR: Partition Exists!';
		}

	$tt = $LEVEL1_START;
	//add new partition to end of dbs... add  chr(13) chr(10) after each partition
	$x = 1;
		foreach ($NEWSECTORS as $tmp1)
		{
			$tt = $tt . $LEVEL2_START . $tmp1 . $LEVEL2_END;
			$x = $x + 1;
		}
	$tt = $tt . $LEVEL1_END . chr(13) . chr(10);
	$DBScontent = $DBScontent . $tt;

	//write dbs file
	WRITE_RAWDATA($EZE_DBS_FILE, $DBScontent);
	
	//verify database addition + confirm with msg
	$DBScontent = LOAD_RAWDATA($EZE_DBS_FILE);

	$findme = $LEVEL1_START . $LEVEL2_START . $NEWSECTORS[1];
	$fs = strpos($DBScontent, $findme);  //search for first partition sector (identifyr)
	if ($fs > 0)
	{ 
		$EZE_DBS_OUTPUT[0][0] = 'Add New Partition + Sectors to Database Completed';
		if ($DBS_STATUS == 'TRUE')
		{
			echo "<br><b>Database Call-ADD</b>: " . $EZE_DBS_FILE . " - Add New Partition + Sectors to Database Completed<br>";
		}

		return 'EZE-DATABASE OK';
	}
	else
	{
		$EZE_DBS_OUTPUT[0][0] = 'Database Update Error, Contact Admin!';
		return 'EZE-DATABASE ERROR: Write Failed!';
	}

}
//-----------------------------------------------------------------

//-----------------------------------------------------------------
if ($EZE_DBS_CMD == 'UpdatePartition')
{

	// SYNTHESIZE DATABASE (Assemble and update database file with changes only)
	//change multiple sectors in the SAME partition as FIRSTSECTOR input... replaces only found sectors and not entire partition (will not delete sectors)
	$FIRSTSECTOR = $EZE_DBS_OPT1;  //partition identify (first sector)
	$SECTORSOURCES = $EZE_DBS_OPT2;
	$SECTORDESTINATIONS = $EZE_DBS_OPT3;

	//read dbs file (unparsed)
	$DBScontent = LOAD_RAWDATA($EZE_DBS_FILE);

	if ($FIRSTSECTOR == '')
	{ 
		$EZE_DBS_OUTPUT[0][0] = 'Invalid Partition Selected!';
		return 'EZE-DATABASE ERROR - Invalid Partition Selected!';
	}

	$findme = $LEVEL1_START . $LEVEL2_START . $FIRSTSECTOR . $LEVEL2_END;
	$fs = strpos(strtolower($DBScontent), strtolower($findme));  //search for first partition sector (identity) no duplicate first sectors

	if ($fs > 0)
	{ 
		$findme = $LEVEL1_END;
		$fss = strpos(strtolower($DBScontent), strtolower($findme), $fs);
		//parse partition and all sectors into string
		$PARTITIONORIG = substr($DBScontent, $fs, $fss - $fs + 4);
	}
	else
	{
		$EZE_DBS_OUTPUT[0][0] = 'Partition Does Not Exist: ' . $FIRSTSECTOR;
		return 'EZE-DATABASE ERROR';
	}

	$PARTITION = $PARTITIONORIG;

	//replace partition searches only not entire partition
	for ($x = 1; $x <= count($SECTORSOURCES); $x++) {	
		if ($SECTORSOURCES[$x] <> ''){
			$findme = $LEVEL2_START . $SECTORSOURCES[$x] . $LEVEL2_END;
			$changeme = $LEVEL2_START . $SECTORDESTINATIONS[$x] . $LEVEL2_END;
			//it doesnt verify if the search sectors are found in the partition so the error msg doesnt reflect that..
			//custom finder needed add not found ones to an error log and display after (not found)
			//echo $findme . ' ' . $changeme . '<br>';
			$PARTITION = str_replace($findme, $changeme, $PARTITION);
			//echo $SECTORSOURCES[$x] . " -> " . $SECTORDESTINATIONS[$x] . "<br>";
		}
	}

	//$DBScontentt = $DBScontent;
	$DBScontent = str_replace($PARTITIONORIG, $PARTITION, $DBScontent);
	//verify changed sectors by refinding them... generate log (not changed)

	//write dbs file (add error checking later)
	WRITE_RAWDATA($EZE_DBS_FILE, $DBScontent);
	//if ($DBScontentt == $DBScontent) { $DBS_STATUS = ''; }
	if ($DBS_STATUS == 'TRUE'){ 
		echo "<br><b>Database Call-UPDATE</b>: " . $EZE_DBS_FILE . " - Update Partition Sectors Database Completed: " . $FIRSTSECTOR . " " . $x;
	}

	$EZE_DBS_OUTPUT[0][0] = 'Update Partition Sectors Database Completed';
	return 'EZE-DATABASE Updated';

}
//-----------------------------------------------------------------

//-----------------------------------------------------------------
if ($EZE_DBS_CMD == 'ReadDB')
{
	//PARSE DATABASE (Open database file and Disassemble into global array $EZE_DBS_OUTPUT)
	$DBScontent = LOAD_RAWDATA($EZE_DBS_FILE);
	$DBSoutput = array();
	$x = 1;
	$y = 1;
	$DBS_Layer1 = &New EZE_DATABASE;
	$DBS_Layer1->ParseDatabase($DBScontent, $LEVEL1_START, $LEVEL1_END);
	$DBS_Layer1_Total = $DBS_Layer1->EZEDBS_ENTRIES;

	for ($tmp1 = 0; $tmp1 <= $DBS_Layer1_Total - 1; $tmp1++)
	{
		$DBS_Layer2 = &New EZE_DATABASE;
		$DBS_Layer2->ParseDatabase($DBS_Layer1->EZEDBS_DATA[$tmp1], $LEVEL2_START, $LEVEL2_END);
		$DBS_Layer2_Total = $DBS_Layer2->EZEDBS_ENTRIES;

		for ($tmp2 = 0; $tmp2 <= $DBS_Layer2_Total - 1; $tmp2++)
		{
			if ($DBS_Layer2->EZEDBS_DATA[$tmp2] <> '')
			{
				$DBSoutput[$x][$y] = $DBS_Layer2->EZEDBS_DATA[$tmp2];
			}
				$y = $y + 1;
		}

	$y = 1;
	$x = $x + 1;
	}

	$x = null;
	$y = null;
	$EZE_DBS_OUTPUT = $DBSoutput;
	$EZE_DBS_OUTPUT[0][0] = 'Parse Database Completed';
	$EZE_DBS_OUTPUT[0][1] = $DBS_Layer1_Total;

	if ($DBS_STATUS == 'TRUE')
	{
		echo "<br><b>Database Call-READ</b>: " . $EZE_DBS_FILE . " - Read + Parse Database Completed<br>";
	}

return 'Read-DBS Completed';
}
//-----------------------------------------------------------------

//-----------------------------------------------------------------

if ($EZE_DBS_CMD == 'FindPartition')
{

//FindPartition by first sector name
$FIRSTSECTOR = $EZE_DBS_OPT1;

//FindSector in Partition

//FindSector (display all parition names[sector1] that have sector with string in it)

//rawsearchdbs (advanced)

}
//-----------------------------------------------------------------

//-----------------------------------------------------------------
if ($EZE_DBS_CMD == 'DelPartiton')
{

// SYNTHESIZE DATABASE (Assemble and update database file with delete partitions (and all sectors))
//$FIRSTSECTOR = string;  //partition ident
//read dbs file (unparsed)
//search for first partition sector (identifyr)
//no duplicate first sectors
//add new partition to end chr(13) chr(10) after each partition
//write dbs file

}
//-----------------------------------------------------------------

?>
