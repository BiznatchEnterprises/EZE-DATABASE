<?php

 /*|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 * |||           EZE-DATABASE 3.0 (PHP CLASS)              |||
 * ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| 
 * ||| © 2002 Biznatch Enterprises - www.Biznaturally.ca   |||
 * |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 
 * ||| DATA FORMAT: |||
 * <ææ[æ{PARTITIONID}æPARTITIONDATA]ææ[æ{DATAID}æDATA-SECTOR]ææ[æ{DATAID}æDATA-SECTOR]ææ>
 
 * ||||||||||||||||||||||||||||
 * ||| SUPPORTED FUNCTIONS: ||| 
 * ||||||||||||||||||||||||||||

 * -----------------------------------------------------------------------
 * XSS_Protect($COMMAND, $CUTOM_STRING)
 *
 * COMMAND: N = Normal   C = Custom                                                               (Required)
 * CUSTOM_STRING: RAWDATA string to be cleaned    (Custom  mode only)                                    (Optional)
 *
 * RETURNS: Normal = Cleans EZEDBS_RAWDATA, C = Returns Cleaned CUSTOM_RAWDATA string
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * Load_Database($FILENAME)
 *
 * USE: Reads RAW database file into $EZEDBS_RAWDATA
 * FILENAME: Full path to database file                                                           (Required)
 *
 * RETURNS: Success, ERROR
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * Close_Database()
 *
 * RETURNS: Clears EZEDBS_RAWDATA
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * Read_Database($FILENAME, $COMMAND, $PARTITION_ID)
 * 
 * USE: Load & Parse Database file into EZEDBS_DATASET
 * FILENAME: Full path to database file                                                           (Required)
 * COMMAND: F_ALL, F_P   (Read from Filename)       R_ALL, R_P  (Read From EZEDBS_RAWDATA)        (Required)
 * PARTITION_ID: Unique ID (Read one partition only)                                              (Optional)
 *
 * RETURNS: ARRAY OF PARITIONS/SECTORS (ROWS/LINES) PARSED FROM EZEDBS_RAWDATA
 * -----------------------------------------------------------------------

* -----------------------------------------------------------------------
 * Write_Database

 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * Delete_Database

 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * Clear_Database

 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * New_Database

 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * Update_Database($COMMAND, $ID, $DATA)
 *
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * FindIn_Database($COMMAND, $ID)
 * 
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * ParseDatabase($RAWDATASTRING, $START_SUBSTR, $END_SUBSTR)
 * 
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * Encrypt_Database

 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * Decrypt_Database

 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * ParsePartition

 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * ParseSector

 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * ParseSectors

 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * ParseString($STRING, $START_SUBSTR, $END_SUBSTR, $START_POS)
 * 
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * New_Partition($COMMAND, $ID)
	//COMMAND: M = Memory Array modification     (Required)
	//COMMAND: F = Database File Modification    (Required)
        //PARTITION_ID = ID of partition.            (Required)
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * New_Sector($COMMAND, $PARTITION_ID, $SECTORID, $DATA)
	//COMMAND: M = Memory Array modification      (Required)
	//COMMAND: F = Database File Modification     (Required)
	//PARITION_ID = ID of partition               (Required)
	//SECTOR_ID = ID of sector                    (Required)
	//SECTOR_DATA = Data for sector               (Required)
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * New_Sectors($COMMAND, $PARTITION_ID, $SECTOR_ARRAY)
	//COMMAND: M = Memory Array modification	(Required)
	//COMMAND: F = Database File Modification	(Required)
	//PARITION_ID = ID of partition                 (Required)
	//SECTOR_ARRAY = TempArray[SectorID][DATA]      (Required)
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * READ_FILECONTENTS($INPUT_FILENAME)
 *
 * INPUT_FILENAME: Full Filename to Read                                                             (Required)

 * RETURNS: String of RAW data from file, ERROR
 * -----------------------------------------------------------------------

 * -----------------------------------------------------------------------
 * WRITE_FILECONTENTS($OUTPUT_FILENAME, $CONTENTS)

 * OUTPUT_FILENAME = Full filename to write                                                          (Required)
 * CONTENTS = DATA STRING to write                                                                   (Required)
 * MODE: w = overwrite full file with string                                                         (Required)
 * MODE: a = Append string to end of file                                                            (Required)
 * MODE: x = Append string to start of file                                                          (Required)

 * RETURNS: Success, ERROR
 * -----------------------------------------------------------------------

 * ||||||||||||||||||||||||||||
 *
 * LOAD CLASS: include() or eval()
 */



// <<<< BEGIN EZE_DATABASE PHP CLASS FILE >>>>

	//-----------------------------------------------------------------------
	// A user-defined error handler function

		function myErrorHandler($errno, $errstr, $errfile, $errline) {
  			 echo "<b>Custom error:</b> [$errno] $errstr<br>";
   			 echo " Error on line $errline in $errfile<br>";
		}

	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// Set user-defined error handler function

		set_error_handler("myErrorHandler");

	//-----------------------------------------------------------------------


class EZE_DATABASE{

	//-----------------------------------------------------------------------
	// STATIC VARIABLES


	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// GLOBAL-CLASS VARIABLES

	var $EZEDBS_FILENAME; //DATABASE FILENAME IN USE

	var $EZEDBS_STATUS;   // DATABASE LAST FUNCTION STATUS OUTPUT
	var $EZEDBS_RAWDATA;  // DATABASE RAW DATA HELD IN MEMORY (GLOBAL)
	var $EZEDBS_DATASET;  // DATABASE PARSED INTO DOUBLE ARRAY HELD IN MEMORY (GLOBAL)
	var $EZEDBS_TEMPDATA; // DATABASE TEMP DATA STORAGE (GLOBAL)

	var $LEVEL1_START = '<æ'; //PARTITION START
	var $LEVEL1_END = 'æ>';   //PARTITION END

	var $LEVEL2_START = 'æ['; //DATA SECTOR START
	var $LEVEL2_END = ']æ';   //DATA SECTOR END

	var $LEVEL3_START = 'æ{'; //PARTITION/SECTOR ID START
	var $LEVEL3_END = '}æ';   //PARTITION/SECTOR ID END

	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// XSS Protect

		function XSS_PROTECT($STRING){

	
		}

	//-----------------------------------------------------------------------


	//-----------------------------------------------------------------------
	// Load_Database
	
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// Close_Database
	
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	function Read_Database($FILENAME, $COMMAND, $PARTITION_ID){
	//FILENAME: Full path to database  (Required)	
	//COMMAND: A = ALL                 (Required)
	//COMMAND: P = Only 1 Partition    (Required)
	//PARTITION_ID: To read only       (Optional)

		if ($COMMAND == 'ALL'){
			//Load Database
			//Parse Database
		}

		if ($COMMAND == 'P'){

		}
	}
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// Write_Database

		function Write_Database($COMMAND, $CUSTOM_DATASET){
			//COMMAND: O = OVERWRITE WITH EZEDBS_DATASET	          (Required)
			//COMMAND: E = Append EZEDBS_DATASET to End of File       (Required)
			//COMMAND: S = Append EZEDBS_DATASET to Start of File     (Required)
			//COMMAND: CO = OVERWRITE WITH CUSTOM_DATASET             (Required)
			//COMMAND: CE = Append CUSTOM_DATASET to End of File      (Required)
			//COMMAND: CS = Append CUSTOM_DATASET to Start of File    (Required)

		}

	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// Delete_Database
	
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// Clear_Database
	
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// New_Database
	
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	function Update_Database($COMMAND, $ID, $DATA){
	//COMMAND: M = Memory Array modification     (Required)
	//COMMAND: F = Database File Modification    (Required)

	}

	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	function FindIn_Database($COMMAND, $ID){
	//COMMAND: ID = By ID return partition and sector number        (Required)
	//COMMAND: ST = By String return partition and sector number    (Required)

	}
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	function ParseDatabase($RAWDATASTRING, $START_SUBSTR, $END_SUBSTR){
		//ParsePartition
		//ParseSectors
	}
	//-----------------------------------------------------------------------

 	//-----------------------------------------------------------------------
	// Encrypt_Database

	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// Decrypt_Database

	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	function ParsePartition(){

	}
	//-----------------------------------------------------------------------
	
	//-----------------------------------------------------------------------
	function ParseSector(){


	}
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	function ParseSectors(){


	}
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	function New_Partition($COMMAND, $PARTITION_ID){
	//COMMAND: M = Memory Array modification     (Required)
	//COMMAND: F = Database File Modification    (Required)
        //PARTITION_ID = ID of partition.            (Required)

	}
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	function New_Sector($COMMAND, $PARTITION_ID, $SECTOR_ID, $SECTOR_DATA){
	//COMMAND: M = Memory Array modification      (Required)
	//COMMAND: F = Database File Modification     (Required)
	//PARITION_ID = ID of partition               (Required)
	//SECTOR_ID = ID of sector                    (Required)
	//SECTOR_DATA = Data for sector               (Required)

	}
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	function New_Sectors($COMMAND, $PARTITION_ID, $SECTOR_ARRAY){
	//COMMAND: M = Memory Array modification	(Required)
	//COMMAND: F = Database File Modification	(Required)
	//PARITION_ID = ID of partition                 (Required)
	//SECTOR_ARRAY = TempArray[SectorID][DATA]      (Required)
	}
	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// READ FILE CONTENTS INTO STRING

		function READ_FILECONTENTS($INPUT_FILENAME){
		//INPUT_FILE = Full Filename to Read (Required)
			$FILE_CONTENTS = '';
			if (file_exists($INPUT_FILENAME)){ } else { 
				return 'ERROR: ' .  $INPUT_FILENAME . ' File Not Found!';
				}
			if (is_readable($INPUT_FILE)){
				$open_file = fopen($INPUT_FILE,"r");
				while ((!feof($open_file))){
				$FILE_CONTENTS = $FILE_CONTENTS . fgets($open_file,100000);
				}
				fclose($open_file);
			}
			else
			{
			return 'ERROR: ' .  $INPUT_FILENAME . ' Not Readable!';
			}
		return $FILE_CONTENTS;
		}

	//-----------------------------------------------------------------------

	//-----------------------------------------------------------------------
	// WRITE FILE CONTENTS FROM STRING
	
		function WRITE_FILECONTENTS($OUTPUT_FILENAME, $FILE_CONTENTS, $MODE){
		//OUTPUT_FILENAME = Full filename to write    (Required)
		//FILE_CONTENTS = DATA STRING to write        (Required)
		//MODE: w = overwrite full file with string   (Required)
		//MODE: a = Append string to end of file      (Required)
		//MODE: x = Append string to start of file    (Required)
		
			if (file_exists($INPUT_FILENAME)){ } else { 
				return 'ERROR: ' .  $OUTPUT_FILENAME . ' File Not Found!';
			}		

			if (is_writeable($OUTPUT_FILENAME)){
				if ($MODE == NULL) { $MODE = 'w'; }
				$handle = fopen($OUTPUT_FILENAME, $MODE);
				fputs($handle, $FILE_CONTENTS);
				fclose($handle);
				return 'Sucess Write File: ' . $OUTPUT_FILENAME;
			}
			else
			{
				return 'ERROR: ' . $OUTPUT_FILENAME . ' Not Writeable!';
			}
		}

	//-----------------------------------------------------------------------


	//-----------------------------------------------------------------------
	// PARSE DATA FROM STRING

	function ParseString($STRING, $START_SUBSTR, $END_SUBSTR, $START_POS){
	//STRING = 
	//START_SUBSTR = 
	//END_SUBSTR = 
	//START_POS = 

		$pos_begin = strpos($STRING, $START_SUBSTR, $START_POS);
		$pos_end = strpos($STRING, $END_SUBSTR, $posa + strlen($START_SUBSTR));
		return substr($STRING, $pos_begin + strlen($START_SUBSTR), $pos_end - $pos_begin - strlen($START_SUBSTR));
	}	

	//-----------------------------------------------------------------------

}

// <<<< END EZE_DATABASE PHP CLASS FILE >>>>









	////////////////////////
	// PARSE DATABASE - RAW DATA into Double String

	//function ParseDatabase($DATASTRING, $START_SUBSTR, $END_SUBSTR){  //RETURNS ARRAY OF PARSED TEXT ITEMS FROM DATASTRING
	//DATASTRING = 
	//START_SUBSTR = 
	//END_SUBSTR = 
	//	$TMP_DATA = array();
	//	$TMP_READPOS = 0;
	//	$TOTCOMPS = substr_count($DATASTRING, $START_SUBSTR);
       // 		for ($data_count = 1; $data_count <= $TOTCOMPS; $data_count++) {
	//			$TMP_DATA[$data_count + 1] = $this->ParseString($DATASTRING, $START_SUBSTR, $END_SUBSTR, $TMP_READPOS);
	//			$TMP_READPOS = $TMP_READPOS + strlen($START_SUBSTR) + 1;
	//		}
	//	$this->$EZEDBS_TEMPDATA = $TOTCOMPS; //TOTAL PARSED ITEMS IN RETURNED ARRAY
	//	return $TMP_DATA;
	//}

	////////////////////////





?>
