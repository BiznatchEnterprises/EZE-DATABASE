<?php
// EZE-DATABASE-EXAMPLE
// (C) 2012 BIZNATCH ENTERPRISES
// Biznaturally.ca - https://github.com/BiznatchEnterprises 

include ("EZE-DATABASE-LOADER.php");

//-------------GENERAL SCRIPT START------------

//import_request_variables("gP", "r"); 
//echo isset($_GET["COMMAND"]);
//if (isset($_POST["COMMAND"]) == TRUE) { $COMMAND = htmlspecialchars($_POST["COMMAND"]); }
//if ($COMMAND == "") { $COMMAND = htmlspecialchars($_GET["COMMAND"]); }
//$COMMAND = $rCOMMAND;

if (isset($_GET["COMMAND"]) == TRUE) { $COMMAND = htmlspecialchars($_GET["COMMAND"]); }

echo '<h1>EZE-DATABASE-EXAMPLE 1.0</h1><br><br>';

$Run_Debug_Mode = "TRUE";

if (isset($COMMAND) == TRUE)
{

	//----------------------------
	if ($COMMAND == 'READ')
	{
		$DATABASE = array();

		EZE_DBS_LOADER('ReadDB', 'EZE-DATABASE.php', '/test-database.php', '', '', '', '', '', $Run_Debug_Mode);
		
		if ($EZE_DBS_OUTPUT[0][0] == 'Parse Database Completed')
		{ 
			$DATABASE = $EZE_DBS_OUTPUT;
		}
		else
		{
			$DATABASE = $EZE_DBS_OUTPUT[0][0];
		}

		echo "READ DATABASE: " . $DATABASE[1][2];  //ROW #1   COLUMN #1
	}
	//------------------------------

	//---------------------------------
	if ($COMMAND == 'ADD')
	{

		$DATABASE = array();

		//BELOW IS OPTIONAL--- 
		//--READ DATABASE---
		EZE_DBS_LOADER('ReadDB', 'EZE-DATABASE.php', '/test-database.php', '', '', '', '', '', $Run_Debug_Mode);
		
		if ($EZE_DBS_OUTPUT[0][0] == 'Parse Database Completed')
		{
			$DATABASE = $EZE_DBS_OUTPUT;
		}
		else
		{
			$DATABASE = $EZE_DBS_OUTPUT[0][0];
		}

		//------------------
		$CNT = count($DATABASE) + 1;   //INCREASES THE COUNTER FOR THE ROW #1 in COL #1 AUTOMATICALLY.
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

		EZE_DBS_LOADER('AddPartition', 'EZE-DATABASE.php', '/test-database.php', $NEWSECTORS, '', '', '', '', $Run_Debug_Mode);
	
		echo "WRITE DATABASE: " . $CNT . " Completed";

	}
	//-----------------------------------

	//----------------------
	if ($COMMAND == 'UPDATE')
	{

		$FINDSECTORS = array();
		$REPLACESECTORS = array();
	
		$FIRSTSECTOR = '1';         //DATA IN FIRST COL ON UNKNOWN ROW TO BE REPLACED
		$FINDSECTORS[1] = '%02%';   //COL DATA1 TO REPLACE ON ROW X
		$FINDSECTORS[2] = '%10%';   //COL DATA2 TO REPLACE ON ROW X

		$REPLACESECTORS[1] = '%02%DATA1';  //COL DATA1 REPLACE BY ON ROW X
		$REPLACESECTORS[2] = '%10%DATA2';  //COL DATA2 REPLACE BY ON ROW X

		EZE_DBS_LOADER('UpdatePartition', 'EZE-DATABASE.php', '/test-database.php', $FIRSTSECTOR, $FINDSECTORS, $REPLACESECTORS, '', '', $Run_Debug_Mode);

		echo "UPDATED: " . $FIRSTSECTOR;
	}
	//----------------------

}

echo 'EZE-DATABASE EXAMPLE: <br><br><a href="?COMMAND=READ">READ</a><br><a href="?COMMAND=ADD">ADD</a><br><a href="?COMMAND=UPDATE">UPDATE</a><br><BR>END OF SCRIPT';
//-------------GENERAL SCRIPT END------------




?>
