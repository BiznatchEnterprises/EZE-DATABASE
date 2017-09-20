<?php
//EZE-DATABASE-EXAMPLE
//(C) 2012 BIZNATCH ENTERPRISES 

$CURRENTPATH = dirname(__FILE__);
$CURRENTPATH = str_replace("index.php", "", $CURRENTPATH);



// ---------- PHP SCRIPT OPENCLOSEEXIT CLEANER START ---------------
//CLEANS IMPORTED PHP SCRIPTS
function PHPSOCEC($INPUT){
	$INPUT = str_replace('<?php exit; ?>', '', $INPUT); 
	$INPUT = str_replace('<?PHP EXIT; ?>', '', $INPUT); 
	$INPUT = str_replace('<?php exit; ?>', '', $INPUT); 
	$INPUT = str_replace('<?PHP EXIT; ?>', '', $INPUT); 
	$INPUT = str_replace('<?php', '', $INPUT); 
	$INPUT = str_replace('<?PHP', '', $INPUT); 
	$INPUT = str_replace('?>', '', $INPUT); 
return $INPUT;
}
// ---------- PHP SCRIPT OPENCLOSEEXIT CLEANER END ---------------

// ---------------------- EZE_DBS_HELPER INIT START ----------------------

function EZE_DBS_HELPER($DBSCOMMAND, $HELPERFILENAME, $DBSFILENAME, $DBSOPTION1, $DBSOPTION2, $DBSOPTION3, $DBSOPTION4, $DBSOPTION5, $DBSSTATUS){

	$HELPERERRORPMOK = '<b>EZE-DATABASE-HELPER ERROR:</b> PARSER FILE INVALID!<br>(Unable to open file, but permissions are OK)<br><br>Command: ' . $DBSCOMMAND . '<br>Parser File: ' . $HELPERFILENAME . '<br>Databse File: ' . $DBSFILENAME;
	$HELPERERRORPMNOK = '<b>EZE-DATABASE-HELPER ERROR:</b> PARSER FILE INVALID!<br>(Unable to open file, possible file permissions error.)<br><br>Command: ' . $DBSCOMMAND . '<br>Parser File: ' . $HELPERFILENAME . '<br>Databse File: ' . $DBSFILENAME;
	global $EZE_DBS_OUTPUT;
	//call to EZE-DATABASE-HELPER External Sector
	//open database file, parse, return array of data
	$EZE_DBS_OUTPUT = null;
	global $LASTDBSHELPER;
	if ($HELPERFILENAME <> 'LASTHELPER') {
		$CURRENTPATH = dirname(__FILE__);
		$CURRENTPATH = str_replace("index.php", "", $CURRENTPATH);
		if (is_readable($CURRENTPATH . '/' . $HELPERFILENAME)) {
		$DATABASEHELPER = '';
		$open_file = fopen($CURRENTPATH . '/' . $HELPERFILENAME,"r+");
			if ($open_file <> ""){
				while ((!feof($open_file))){
				$DATABASEHELPER = $DATABASEHELPER. fgets($open_file,100000);
				}
			fclose($open_file);
			$LASTDBSHELPER = $DATABASEHELPER;
			if ($DBSSTATUS == 'TRUE1'){ echo "<br><b>EZE-DATABASE-HELPER Loaded</b>: " . $HELPERFILENAME . "<br>"; }
   			}
   			else
   			{
			//Possible Permissions OTHER ERROR
   			return $HELPERERRORPMOK;
   			if ($DBSSTATUS == 'TRUE1'){ echo $HELPERERRORPMOK; }
			}
		}
		else
		{
		//Possible Permissions Error
 		return $HELPERERRORPMNOK;
		if ($DBSSTATUS == 'TRUE1'){ echo $HELPERERRORPMNOK; }
		}
	}
	if ($DATABASEHELPER == '') { $DATABASEHELPER = $LASTDBSHELPER; }
	if (isset($DBSFILENAME) == TRUE) { $EZE_DBS_FILE = $DBSFILENAME; }
	if (isset($DBSCOMMAND) == TRUE) { $EZE_DBS_CMD = $DBSCOMMAND; }
	if (isset($DBSCHNGSOURCE) == TRUE) { $EZE_DBS_CHANGESOURCE = $DBSCHNGSOURCE; }
	if (isset($DBSCHNGDEST) == TRUE) { $EZE_DBS_CHANGEDEST = $DBSCHNGDEST; }
	if (isset($DBSOPTION1) == TRUE) { $EZE_DBS_OPT1 = $DBSOPTION1; }
	if (isset($DBSOPTION2) == TRUE) { $EZE_DBS_OPT2 = $DBSOPTION2; }
	if (isset($DBSOPTION3) == TRUE) { $EZE_DBS_OPT3 = $DBSOPTION3; }
	if (isset($DBSOPTION4) == TRUE) { $EZE_DBS_OPT4 = $DBSOPTION4; }
	if (isset($DBSOPTION5) == TRUE) { $EZE_DBS_OPT5 = $DBSOPTION5; }
	if (isset($DBSOPTION6) == TRUE) { $EZE_DBS_OPT6 = $DBSOPTION6; }
	if ($DATABASEHELPER <> ''){ eval(PHPSOCEC($DATABASEHELPER));}
}
// ---------------------- EZE_DBS_HELPER INIT END ----------------------


//-----------------EZE_DATABASE PARSER START--------------------
class EZE_DATABASE{
	var $EZEDBS_DATA;
	var $POSTMP;
	var $EZEDBS_ENTRIES;

	function ParseString($STRING, $START_SUBSTR, $END_SUBSTR, $START_POS){
	$posa = strpos($STRING, $START_SUBSTR, $START_POS);
	$posb = strpos($STRING, $END_SUBSTR, $posa + strlen($START_SUBSTR));
	$this->POSTMP = $posa;
	return substr($STRING, $posa + strlen($START_SUBSTR), $posb - $posa - strlen($START_SUBSTR));
	}	


	function ParseDatabase($DATASTRING, $START_SUBSTR, $END_SUBSTR){
	$this->EZEDBS_DATA = array();
	$TOTCOMPS = substr_count($DATASTRING, $START_SUBSTR);
        	for ($bb = 1; $bb <= $TOTCOMPS; $bb++) {
		$DATA[$bb + 1] = $this->ParseString($DATASTRING, $START_SUBSTR, $END_SUBSTR, $this->POSTMP);
		$this->POSTMP = $this->POSTMP + strlen($START_SUBSTR) + 1;
		
		}
	$this->EZEDBS_ENTRIES = $TOTCOMPS;
	
	if (isset($DATA) == TRUE) {
		 if (is_ARRAY($DATA) == 'TRUE'){ 
			$this->EZEDBS_DATA = array_merge($this->EZEDBS_DATA, $DATA);
		 }
	}
	}
}
//-----------------EZE_DATABASE PARSER END--------------------

//-------------LOAD_FILECONTENTS START----------------
function LOAD_FILECONTENTS($INPUTFILE){
$CURRENTPATH = dirname(__FILE__);
$CURRENTPATH = str_replace("index.php", "", $CURRENTPATH);

$FILECONTENTS = '';
	if (file_exists($CURRENTPATH . $INPUTFILE)){ echo ''; } else { 
echo '<br><u><b>ERROR:</b>' . $CURRENTPATH . $INPUTFILE . ' File Not Found!</u><br>';
return 'FNF'; 
}
	if (is_readable($CURRENTPATH . $INPUTFILE)){
	$open_file = fopen($CURRENTPATH . $INPUTFILE,"r+");
	while ((!feof($open_file))){
	$FILECONTENTS = $FILECONTENTS . fgets($open_file,100000);
	}
	fclose($open_file);
	}
	else
	{
	echo '<br><u><b>ERROR:</b>' . $CURRENTPATH . $INPUTFILE . ' Not Readable!</u><br>';
	return '<NoPermissions>';
	}
if ($FILECONTENTS <> ''){ return $FILECONTENTS; } else { return '<EmptyContents>'; }
}
//-------------LOAD_FILECONTENTS END----------------

//-------------WRITE_FILECONTENTS START----------------
function WRITE_FILECONTENTS($OUTPUTFILE, $CONTENTS){
	$CURRENTPATH = dirname(__FILE__);
	$CURRENTPATH = str_replace("index.php", "", $CURRENTPATH);
	$handle = fopen($CURRENTPATH . $OUTPUTFILE, "w");
	fputs($handle, $CONTENTS);
	fclose($handle);
}
//-------------WRITE_FILECONTENTS END----------------


//-------------GENERAL SCRIPT START------------
//import_request_variables("gP", "r"); 
//echo isset($_GET["COMMAND"]);
//if (isset($_POST["COMMAND"]) == TRUE) { $COMMAND = htmlspecialchars($_POST["COMMAND"]); }
if (isset($_GET["COMMAND"]) == TRUE) { $COMMAND = htmlspecialchars($_GET["COMMAND"]); }
//if ($COMMAND == "") { $COMMAND = htmlspecialchars($_GET["COMMAND"]); }
//$COMMAND = $rCOMMAND;

echo '<h1>EZE-DATABASE-EXAMPLE 1.0</h1><br><br>';

$EZECORES_Run_Debug_Mode = "FALSE";
$EZECORES_Run_Debug_MTP = "2";

//----------------------------
if ($COMMAND == 'READ'){

	EZE_DBS_HELPER('ReadDBPS', 'EZE-DATABASE.php', '/test-database.php', '', '', '', '', '', $EZECORES_Run_Debug_Mode . $EZECORES_Run_Debug_MTP);
	if ($EZE_DBS_OUTPUT[0][0] == 'Parse Database Completed'){ $DATABASE = $EZE_DBS_OUTPUT; } else { $DATABASE = $EZE_DBS_OUTPUT[0][0]; }

	echo "READ DATABASE: " . $DATABASE[1][2];  //ROW #1   COLUMN #1

}
//------------------------------


//---------------------------------
if ($COMMAND == 'WRITE'){


	//BELOW IS OPTIONAL--- 
	//--READ DATABASE---
	EZE_DBS_HELPER('ReadDBPS', 'EZE-DATABASE.php', '/test-database.php', '', '', '', '', '', $EZECORES_Run_Debug_Mode . $EZECORES_Run_Debug_MTP);
	if ($EZE_DBS_OUTPUT[0][0] == 'Parse Database Completed'){ $DATABASE = $EZE_DBS_OUTPUT; } else { $DATABASE = $EZE_DBS_OUTPUT[0][0]; }
	//------------------
	$CNT = count($DATABASE);   //INCREASES THE COUNTER FOR THE ROW #1 in COL #1 AUTOMATICALLY.
	//ABOVE IS OPTIONAL----


	$NEWSECTORS = array();
	$NEWSECTORS[1] = $CNT;
	$NEWSECTORS[2] = '%02%';
	$NEWSECTORS[3] = '%03%';
	$NEWSECTORS[4] = '%04%';
	$NEWSECTORS[5] = '%05%';
	$NEWSECTORS[6] = '%06%';
	$NEWSECTORS[7] = '%07%';
	$NEWSECTORS[8] = '%08%';
	$NEWSECTORS[9] = '%09%';
	$NEWSECTORS[10] = '%10%';
	$NEWSECTORS[11] = '%11%';
	$NEWSECTORS[12] = '%12%';

	EZE_DBS_HELPER('AddDBP', 'EZE-DATABASE.php', '/test-database.php', $NEWSECTORS, '', '', '', '', $EZECORES_Run_Debug_Mode . $EZECORES_Run_Debug_MTP);
	
	echo "WRITE DATABASE: " . $CNT . " Completed";

}
//-----------------------------------


//----------------------
if ($COMMAND == 'UPDATE'){

	$FINDSECTORS = array();
	$REPLACESECTORS = array();
	
	$FIRSTSECTOR = '1';         //DATA IN FIRST COL ON UNKNOWN ROW TO BE REPLACED
	$FINDSECTORS[1] = '%02%';   //COL DATA1 TO REPLACE ON ROW X
	$FINDSECTORS[2] = '%10%';   //COL DATA2 TO REPLACE ON ROW X

	$REPLACESECTORS[1] = '%02%DATA1';  //COL DATA1 REPLACE BY ON ROW X
	$REPLACESECTORS[2] = '%10%DATA2';  //COL DATA2 REPLACE BY ON ROW X

	EZE_DBS_HELPER('UpdateDBPS', 'EZE-DATABASE.php', '/test-database.php', $FIRSTSECTOR, $FINDSECTORS, $REPLACESECTORS, '', '', $EZECORES_Run_Debug_Mode . $EZECORES_Run_Debug_MTP);

	echo "UPDATED: " . $FIRSTSECTOR;
}
//----------------------



echo '<br><br><a href="?COMMAND=READ">READ</a><br><a href="?COMMAND=WRITE">WRITE</a><br><a href="?COMMAND=UPDATE">UPDATE</a><br><BR>END OF SCRIPT';
//-------------GENERAL SCRIPT END------------




?>
