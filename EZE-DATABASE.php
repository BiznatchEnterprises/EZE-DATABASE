<?php
//                                           ---------------------------------------------------------
//                                           EZE-DATABASE-HELPER � 2002-2008 Biznatch Enterprises
//					     Biznaturally.ca - https://github.com/BiznatchEnterprises/
//                                                    EZE-DATABASE (BETA) Version: 1.05
//                                                    Double Nested Database Parser/Synthesizer
//                                           ---------------------------------------------------------
//                                           EZE-CORES LOADER - function EZE_DBS_HELPER INPUTS (EZE-Cores)
//                                             $DBSCOMMAND
//                                             $HELPERFILENAME
//                                             $DBSFILENAME
//                                             $DBSOPTION1
//                                             $DBSOPTION2
//                                             $DBSOPTION3
//                                             $DBSOPTION4
//                                             $DBSOPTION5
//
//                                           EZE-DATABASE-HELPER INPUTS (This Script file):
//                                             $EZE_DBS_CMD = $DBSCOMMAND;
//                                             $EZE_DBS_FILE = $DBSFILENAME;
//                                             $EZE_DBS_OPT1
//                                             $EZE_DBS_OPT2
//                                             $EZE_DBS_OPT3
//                                             $EZE_DBS_OPT4
//                                             $EZE_DBS_OPT5
//
//FUTURE GOAL: ADVANCE ERROR CHECKING INTO DEBUG MODE OF CORES!!!
//
$LEVEL1_START = '<�';
$LEVEL1_END = '�>';
$LEVEL2_START = '�[';
$LEVEL2_END = ']�';
//-----------------------------------------------------------------
if ($EZE_DBS_CMD == 'AddDBP'){

	//SYNTHESIZE DATABASE (Assemble and update database file with new partition containing sectors)
	$NEWSECTORS = $EZE_DBS_OPT1;
	$DBScontent = LOAD_FILECONTENTS($EZE_DBS_FILE);
	
	//no duplicate first sectors
	if ($NEWSECTORS[1] == ''){ 
	$EZE_DBS_OUTPUT[0][0] = 'Invalid Partition Selected!';
	return 'EZE-DATABASE ERROR: Invalid Partition Selected!';
 	}
	
$findme = $LEVEL1_START . $LEVEL2_START . $NEWSECTORS[1] . $LEVEL2_END;
$fs = strpos($DBScontent, $findme);  //search for first partition sector (identifyr) no duplicate first sectors
if ($fs > 0){ 
$EZE_DBS_OUTPUT[0][0] = 'Partition Already Exists!';
return 'EZE-DATABASE ERROR: Partition Exists!';
}

$tt = $LEVEL1_START;
//add new partition to end of dbs... add  chr(13) chr(10) after each partition
$x = 1;
foreach ($NEWSECTORS as $tmp1) {
$tt = $tt . $LEVEL2_START . $tmp1 . $LEVEL2_END;
$x = $x + 1;
}
$tt = $tt . $LEVEL1_END . chr(13) . chr(10);
$DBScontent = $DBScontent . $tt;

//write dbs file
WRITE_FILECONTENTS($EZE_DBS_FILE, $DBScontent);
	
//verify database addition + confirm with msg
$DBScontent = LOAD_FILECONTENTS($EZE_DBS_FILE);


$findme = $LEVEL1_START . $LEVEL2_START . $NEWSECTORS[1];
$fs = strpos($DBScontent, $findme);  //search for first partition sector (identifyr)
if ($fs > 0){ 
$EZE_DBS_OUTPUT[0][0] = 'Add New Partition + Sectors to Database Completed';
if ($DBSSTATUS == 'TRUE1'){ echo "<br><b>Database Call-ADD</b>: " . $EZE_DBS_FILE . " - Add New Partition + Sectors to Database Completed<br>"; }
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
if ($EZE_DBS_CMD == 'AddDBS'){
// SYNTHESIZE DATABASE (Assemble and update database file with new sectors only, add to existing partition)
//$FIRSTSECTOR = string;  //partition ident
//$NEWSECTOR[sector]   //new sectors to add to partition (after last sector)     //FUTURE: select any sector
//search for first partition sector (identifyr), search for last sector... add new sector
//UNDER CONTRUCTION//    <--------------
}
//-----------------------------------------------------------------

if ($EZE_DBS_CMD == 'FindDBP'){
//FindPartition by first sector name
$FIRSTSECTOR = $EZE_DBS_OPT1;

}

//FindSector in Partition

//FindSector (display all parition names[sector1] that have sector with string in it)

//rawsearchdbs (advanced)

//-----------------------------------------------------------------
if ($EZE_DBS_CMD == 'DelDBP'){ $EZE_DBS_CMD = 'DeleteDBP'; }
if ($EZE_DBS_CMD == 'DeleteDBP'){
// SYNTHESIZE DATABASE (Assemble and update database file with delete partitions (and all sectors))
//$FIRSTSECTOR = string;  //partition ident
//read dbs file (unparsed)
//search for first partition sector (identifyr)
//no duplicate first sectors
//add new partition to end chr(13) chr(10) after each partition
//write dbs file
}
//-----------------------------------------------------------------

//-----------------------------------------------------------------
if ($EZE_DBS_CMD == 'DelDBS'){ $EZE_DBS_CMD = 'DeleteDBS'; }
if ($EZE_DBS_CMD == 'DeleteDBS'){
// SYNTHESIZE DATABASE (Assemble and update database file with delete sectors only)
//UNDER CONTRUCTION//
}
//-----------------------------------------------------------------

//-----------------------------------------------------------------
if ($EZE_DBS_CMD == 'UpdateDBPS'){
// SYNTHESIZE DATABASE (Assemble and update database file with changes only)
//change multiple sectors in the SAME partition as FIRSTSECTOR input... replaces only found sectors and not entire partition (will not delete sectors)
$FIRSTSECTOR = $EZE_DBS_OPT1;  //partition identify (first sector)
$SECTORSOURCES = $EZE_DBS_OPT2;
$SECTORDESTINATIONS = $EZE_DBS_OPT3;
//echo $SECTORDESTINATIONS[1];
//$SECTORSOURCES[1] = ''; //number is not order of stored in dbs file it is string based searching intead of numberical sequential.
//$SECTORSOURCES[2] = '';
//$SECTORDESTINATIONS[1] = '';
//$SECTORDESTINATIONS[2] = '';

//read dbs file (unparsed)

$DBScontent = LOAD_FILECONTENTS($EZE_DBS_FILE);

if ($FIRSTSECTOR == ''){ 
$EZE_DBS_OUTPUT[0][0] = 'Invalid Partition Selected!';
return 'EZE-DATABASE ERROR - Invalid Partition Selected!';
}

$findme = $LEVEL1_START . $LEVEL2_START . $FIRSTSECTOR . $LEVEL2_END;
$fs = strpos(strtolower($DBScontent), strtolower($findme));  //search for first partition sector (identifyr) no duplicate first sectors

if ($fs > 0){ 
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
WRITE_FILECONTENTS($EZE_DBS_FILE, $DBScontent);
//if ($DBScontentt == $DBScontent) { $DBSSTATUS = ''; }
if ($DBSSTATUS == 'TRUE1'){ 
echo "<br><b>Database Call-UPDATE</b>: " . $EZE_DBS_FILE . " - Update Partition Sectors Database Completed: " . $FIRSTSECTOR . " " . $x;

}
$EZE_DBS_OUTPUT[0][0] = 'Update Partition Sectors Database Completed';
return 'EZE-DATABASE OK';

//}
}
//-----------------------------------------------------------------
//-----------------------------------------------------------------
if ($EZE_DBS_CMD == 'ReadDBPS'){
//PARSE DATABASE (Open database file and Disassemble into global array $EZE_DBS_OUTPUT)
$DBScontent = LOAD_FILECONTENTS($EZE_DBS_FILE);
$DBSoutput = array();
$x = 1;
$y = 1;
$DBS_Layer1 = &New EZE_DATABASE;
$DBS_Layer1->ParseDatabase($DBScontent, $LEVEL1_START, $LEVEL1_END);
$DBS_Layer1_Total = $DBS_Layer1->EZEDBS_ENTRIES;
	for ($tmp1 = 0; $tmp1 <= $DBS_Layer1_Total - 1; $tmp1++) {
	$DBS_Layer2 = &New EZE_DATABASE;
	$DBS_Layer2->ParseDatabase($DBS_Layer1->EZEDBS_DATA[$tmp1], $LEVEL2_START, $LEVEL2_END);
	$DBS_Layer2_Total = $DBS_Layer2->EZEDBS_ENTRIES;
		for ($tmp2 = 0; $tmp2 <= $DBS_Layer2_Total - 1; $tmp2++) {
		if ($DBS_Layer2->EZEDBS_DATA[$tmp2] <> ''){ $DBSoutput[$x][$y] = $DBS_Layer2->EZEDBS_DATA[$tmp2]; }
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
if ($DBSSTATUS == 'TRUE1'){ echo "<br><b>Database Call-READ</b>: " . $EZE_DBS_FILE . " - Read + Parse Database Completed<br>"; }
return 'handled';
}
//-----------------------------------------------------------------
