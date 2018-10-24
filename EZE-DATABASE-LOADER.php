<?php
/* ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 * |||      EZE-DATABASE BETA 1.7 - (C) 2002-2018 Biznatch Enterprises      |||
 * |||    Double Nested Database Parser/Synthesizer with unique data IDs    |||
 * |||           MIT License https://opensource.org/licenses/MIT          	|||
 * |||      Copyright (C) 2002 Biznatch Enterprises - Biznaturally.ca     	|||
 * |||                                                                      |||
 * ||| EZE-DATABASE LOADER - function INPUTS                       		    |||
 * ||| $DBS_COMMAND                                                         |||
 * ||| $LOADER_FILENAME     							                    |||
 * ||| $DBS_FILENAME                                                        |||
 * ||| $DBS_OPTION1 								                        |||
 * ||| $DBS_OPTION3 								                        |||
 * ||| $DBS_OPTION4  								                        |||
 * ||| $DBS_OPTION5 								                        |||
 * ||| $DBS_STATUS true/false (echo debuging)					            |||
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

$EZE_DBS_OUTPUT = array();

// -------------------- START --------------------
// @ Name: PHPSOCEC()
// @ Action: CLEANS IMPORTED PHP SCRIPTS
// @ Param: string Input
// @ Returns: string Input (cleaned)
//
function PHPSOCEC($INPUT)
{

	$INPUT = str_replace('<?php exit; ?>', '', $INPUT);
	$INPUT = str_replace('<?PHP EXIT; ?>', '', $INPUT);
	$INPUT = str_replace('<?php exit; ?>', '', $INPUT);
	$INPUT = str_replace('<?PHP EXIT; ?>', '', $INPUT);
	$INPUT = str_replace('<?php', '', $INPUT);
	$INPUT = str_replace('<?PHP', '', $INPUT);
    $INPUT = str_replace('?>', '', $INPUT);
    
    return $INPUT;

}
// --------------------- END ---------------------

// -------------------- START --------------------
// @ Name: XSS_PROTECT()
// @ Action: XSS INJECTION PROTECTION
// @ Param: string DATA
// @ Returns: string DATA (cleaned)
//
if (function_exists("XSS_PROTECT") == false){
    function XSS_PROTECT($DATA)
    {

    $DATA = str_replace(chr(13) . chr(10), '', $DATA);          // WIN/DOS: chr(13) . chr(10)
    $DATA = str_replace("\n", '', $DATA);                       // UNIX: "\n"
    $DATA = str_replace(chr(13), '', $DATA);                    // ASCII {ENTER}"

    $DATA = str_replace('<' . chr(225), '', $DATA);       		// PARTITION START
    $DATA = str_replace(chr(225) . '>', '', $DATA); 			// PARTITION END
    $DATA = str_replace(chr(224) . '[', '', $DATA); 		    // SECTOR START
    $DATA = str_replace(']' . chr(224), '', $DATA); 			// SECTOR END

    $DATA = str_replace(chr(223) . '{', '', $DATA); 			// SECTOR LABEL START
    $DATA = str_replace('}' . chr(223), '', $DATA); 			// SECTOR LABEL END

    return $DATA;
    }
}
// --------------------- END ---------------------

// -------------------- START --------------------
// @ Name: EZE_DBS_LOADER()
// @ Action: EZE_DBS_LOADER INIT
// @ Param: DBS_COMMAND
// @ Param: ENGINE_FILENAME
// @ Param: $DBS_OPTION1
// @ Param: DBS_OPTION2
// @ Param: DBS_OPTION3
// @ Param: DBS_OPTION4
// @ Param: DBS_OPTION5
// @ Param: DBS_STATUS string TRUE\FALSE
// @ Returns: Unable to open file, but permissions are OK
// @ Returns: Unable to open file, possible file permissions error
// @ Status: Unable to open file, but permissions are OK
// @ Status: Unable to open file, possible file permissions error
//
function EZE_DBS_LOADER($DBS_COMMAND, $ENGINE_FILENAME, $DBS_FILENAME, $DBS_OPTION1 = NULL, $DBS_OPTION2 = NULL, $DBS_OPTION3 = NULL, $DBS_OPTION4 = NULL, $DBS_OPTION5 = NULL, $DBS_STATUS = NULL)
{

	global $EZE_DBS_OUTPUT;
    global $LAST_DBS_ENGINE;

	$ERROR_MSG1 = '<b>EZE-DATABASE-LOADER ERROR:</b> PARSER FILE INVALID!';
    $ERROR_MSG1 .= '<br>(Unable to open file, but permissions are OK)<br>';
    $ERROR_MSG1 .= '<br>Command: ' . $DBS_COMMAND . '<br>Parser File: ' . $ENGINE_FILENAME . '<br>Databse File: ' . $DBS_FILENAME;
	
    $ERROR_MSG2 = '<b>EZE-DATABASE-LOADER ERROR:</b> PARSER FILE INVALID!';
    $ERROR_MSG2 .= '<br>(Unable to open file, possible file permissions error.)';
    $ERROR_MSG2 .= '<br><br>Command: ' . $DBS_COMMAND . '<br>Parser File: ' . $ENGINE_FILENAME . '<br>Databse File: ' . $DBS_FILENAME;

	if ($ENGINE_FILENAME <> 'LASTLOADER')
    {      
		if (is_readable($ENGINE_FILENAME))
        {
		    $DBS_ENGINE = '';
		    $open_file = fopen($ENGINE_FILENAME,"r+");

			if ($open_file <> "")
            {
				while ((!feof($open_file)))
                {
				    $DBS_ENGINE = $DBS_ENGINE. fgets($open_file,100000);
				}

			    fclose($open_file);
			    $LAST_DBS_ENGINE = $DBS_ENGINE;
			    
                if ($DBS_STATUS == 'TRUE')
                {
                    echo "<br><b>EZE-DATABASE-LOADER Started</b>: " . $ENGINE_FILENAME . "<br>";
                }
   			}
   			else
   			{
   			    if ($DBS_STATUS == 'TRUE')
                {
                   echo $ERROR_MSG1;
                }

                //Possible Permissions OTHER ERROR
   			    return $ERROR_MSG1;
			}
		}
		else
		{
		    if ($DBS_STATUS == 'TRUE')
            {
                echo $ERROR_MSG2;
            }

		    //Possible Permissions Error
 		    return $ERROR_MSG2;
		}
	}

	if ($DBS_ENGINE == '')
    {
        $DBS_ENGINE = $LAST_DBS_ENGINE;
    }

	if (isset($DBS_FILENAME) == TRUE)
    {
        $EZE_DBS_FILE = $DBS_FILENAME;
    }

	if (isset($DBS_COMMAND) == TRUE)
    {
        $EZE_DBS_CMD = $DBS_COMMAND;
    }

	if (isset($DBS_OPTION1) == TRUE)
    {
        $EZE_DBS_OPT1 = $DBS_OPTION1;
    }

	if (isset($DBS_OPTION2) == TRUE)
    {
        $EZE_DBS_OPT2 = $DBS_OPTION2;
    }

	if (isset($DBS_OPTION3) == TRUE)
    {
        $EZE_DBS_OPT3 = $DBS_OPTION3;
    }

	if (isset($DBS_OPTION4) == TRUE)
    {
        $EZE_DBS_OPT4 = $DBS_OPTION4;
    }

	if (isset($DBS_OPTION5) == TRUE)
    {
        $EZE_DBS_OPT5 = $DBS_OPTION5;
    }
	if (isset($DBSOPTION6) == TRUE)
    {
        $EZE_DBS_OPT6 = $DBSOPTION6;
    }

	if ($DBS_ENGINE <> '')
    {
        eval(PHPSOCEC($DBS_ENGINE));
    }

}
// --------------------- END ---------------------

// ********************* START *********************
class EZE_DATABASE
{

	var $EZEDBS_DATA;
	var $POSTMP;
	var $EZEDBS_ENTRIES;

    // -------------------- START --------------------
    // @ Name: ParseString()
    // @ Action: Parses text in a string between two sub-strings
    // @ Param: string START_SUBSTR
    // @ Param: string END_SUBSTR
    // @ Param: integer Character Number to start
    // @ Returns: string Parsed text
    //
	function ParseString($STRING, $START_SUBSTR, $END_SUBSTR, $START_POS)
    {
	    $tmp_start = strpos($STRING, $START_SUBSTR, $START_POS);
        $tmp_end = strpos($STRING, $END_SUBSTR, $tmp_start + strlen($START_SUBSTR));
        $this->POSTMP = $tmp_start;
        return substr($STRING, $tmp_start + strlen($START_SUBSTR), $tmp_end - $tmp_start - strlen($START_SUBSTR));
	}	

    // --------------------- END ---------------------

    // -------------------- START --------------------
    // @ Name: ParseDatabase()
    // @ Action: Parses 2-Dimensional array from database string
    // @ Param: string DATASTRING  parse data from here
    // @ Param: string START_SUBSTR  Parse starting from this position 
    // @ Param: string SEND_SUBSTR  Parse ending from this position 
    // @ Returns: string PARSED between starting and ending sub-strings ($this->EZEDBS_DATA)
    //
	function ParseDatabase($DATASTRING, $START_SUBSTR, $END_SUBSTR)
    {
        $this->EZEDBS_DATA = array();
        
	    $TOTCOMPS = substr_count($DATASTRING, $START_SUBSTR);
        for ($tmp_item = 1; $tmp_item <= $TOTCOMPS; $tmp_item++)
        {
		    $DATA[$tmp_item + 1] = $this->ParseString($DATASTRING, $START_SUBSTR, $END_SUBSTR, $this->POSTMP);
		    $this->POSTMP = $this->POSTMP + strlen($START_SUBSTR) + 1;
		
		}

	    $this->EZEDBS_ENTRIES = $TOTCOMPS;
	
	    if (isset($DATA) == TRUE)
        {
		    if (is_ARRAY($DATA) == 'TRUE')
            { 
			    $this->EZEDBS_DATA = array_merge($this->EZEDBS_DATA, $DATA);
		    }
	    }
	}
    // --------------------- END ---------------------

}
// ********************** END **********************

//-------------LOAD_RAWDATA START----------------
// -------------------- START --------------------
// @ Name: LOAD_RAWDATA()
// @ Action: Loads data from file into string
// @ Param: string INPUTFILE
// @ Returns: FNF
// @ Status: <br><u><b>ERROR:</b>' . $INPUTFILE . ' File Not Found!</u><br>
// @ Returns: <NoPermissions>
// @ Status: <br><u><b>ERROR:</b>' . $INPUTFILE . ' Not Readable!</u><br>
// @ Returns: <EmptyContents>
// @ Status: <br><u><b>ERROR:</b>' . $INPUTFILE . ' Empty File!</u><br>
//
function LOAD_RAWDATA($INPUTFILE)
{

    $FILECONTENTS = '';

	if (!file_exists( $INPUTFILE))
    {
        if ($DBS_STATUS == 'TRUE')
        {
            echo '<br><u><b>ERROR:</b>' . $INPUTFILE . ' File Not Found!</u><br>';
        }

        return 'FNF';
    }

	if (is_readable($INPUTFILE))
    {
	    $open_file = fopen($INPUTFILE,"r+");

	    while ((!feof($open_file)))
        {
	        $FILECONTENTS = $FILECONTENTS . fgets($open_file,100000);
	    }

	    fclose($open_file);
	}
	else
	{
        if ($DBS_STATUS == 'TRUE')
        {
	        echo '<br><u><b>ERROR:</b>' . $INPUTFILE . ' Not Readable!</u><br>';
        }
        
        return '<NoPermissions>';
	}

    if ($FILECONTENTS <> '')
    {
        return $FILECONTENTS;
    }
    else
    {
        if ($DBS_STATUS == 'TRUE')
        {
	        echo '<br><u><b>ERROR:</b>' . $INPUTFILE . ' Empty File!</u><br>';
        }

        return '<EmptyContents>';
    }
}
// --------------------- END ---------------------

// -------------------- START --------------------
// @ Name: WRITE_RAWDATA()
// @ Action: Writes raw data to file
// @ Param: string OUTPUTFILE 
// @ Param: string OUTPUTFILE contents
//
function WRITE_RAWDATA($OUTPUTFILE, $CONTENTS)
{
	$handle = fopen( $OUTPUTFILE, "w");
	fputs($handle, $CONTENTS);
	fclose($handle);
}
// --------------------- END ---------------------

// -------------------- START --------------------
// @ Name: DBS_FindSector()
// @ Action: Finds first occurance of Sector (search) returns partition and sector array key numbers
// @ Returns: array  Key 0: = true/false (function status)
// @ Returns: array  Key 1: = Partition ID
// @ Returns: array  Key 2: = Sector ID
//
function DBS_FindSector($INPUTARRAY, $FINDSTRING)
{
    $OUTPUT = array();
	$OUTPUT[0] = false; // FUNCTION STATUS
    $OUTPUT[1] = 0; // Partition Array KEY
    $OUTPUT[2] = 0; // Sector Array KEY

	for ($temp_dim1 = 1; $temp_dim1<= count($INPUTARRAY) - 1; $temp_dim1++)
    {
		for ($temp_dim2 = 1; $temp_dim2 <= count($INPUTARRAY[$temp_dim1]) - 1; $temp_dim2++)
        {
            if (isset($INPUTARRAY[$temp_dim1][$temp_dim2]) == true)
            {
                if ($INPUTARRAY[$temp_dim1][$temp_dim2] == $FINDSTRING)
                {
                    $OUTPUT[0] = true;  
                    $OUTPUT[1] = $temp_dim1; // Partition
                    $OUTPUT[2] = $temp_dim2; // Sector

                    return $OUTPUT;
			    }
            }
		}
	}

        return $OUTPUT;
}
// --------------------- END ---------------------

// -------------------- START --------------------
// @ Name: DBS_FindLastSector()
// @ Action: Finds last occurance of Sector (search) returns partition and sector array key numbers
// @ Param: string array INPUTARRAY    (Parsed Database Array)
// @ Param: string FINDSTRING
// @ Returns: array  Key 0: = true/false (function status)
// @ Returns: array  Key 1: = Partition ID
// @ Returns: array  Key 2: = Sector ID
//
function DBS_FindLastSector($INPUTARRAY, $FINDSTRING)
{
        $OUTPUT = array();
	    $OUTPUT[0] = false; // FUNCTION STATUS
        $OUTPUT[1] = 0; // Partition Array KEY
        $OUTPUT[2] = 0; // Sector Array KEY

	    for ($temp_dim1 = 1; $temp_dim1<= count($INPUTARRAY) - 1; $temp_dim1++)
        {
		    for ($temp_dim2 = 1; $temp_dim2 <= count($INPUTARRAY[$temp_dim1]) - 1; $temp_dim2++)
            {
                if (isset($INPUTARRAY[$temp_dim1][$temp_dim2]) == true)
                {
                    if ($INPUTARRAY[$temp_dim1][$temp_dim2] == $FINDSTRING)
                    {
                        $OUTPUT[0] = true;  
                        $OUTPUT[1] = $temp_dim1; // Partition
                        $OUTPUT[2] = $temp_dim2; // Sector

			        }
                }
		    }
	    }

        return $OUTPUT;
    }
// --------------------- END ---------------------

// -------------------- START --------------------
// @ Name: DBS_FindPartition()
// @ Action: Finds first occurance of Partition ID (search) returns array key number
// @ Param: string array INPUTARRAY   (Parsed Database Array)
// @ Param: string SEARCH find
// @ Returns: Partition ID
//
function DBS_FindPartition($INPUTARRAY, $SEARCH)
{
    for ($temp_dim1 = 1; $temp_dim1 <= count($INPUTARRAY) - 1; $temp_dim1++)
    {
        if ($INPUTARRAY[$temp_dim1][1] == $SEARCH) // find Partition ID
        {
            return $temp_dim1;
        }
    }
     return false;
}
// --------------------- END ---------------------

// -------------------- START --------------------
// @ Name: DBS_FindLastPartition()
// @ Action: Finds last occurance of Partition ID (search) returns array key number
// @ Param: string array INPUTARRAY   (Parsed Database Array)
// @ Param: string SEARCH find
// @ Returns: Partition ID
//
function DBS_FindLastPartition($INPUTARRAY, $SEARCH)
{
    $found = 0;

    for ($temp_dim1 = 1; $temp_dim1 <= count($INPUTARRAY) - 1; $temp_dim1++)
    {
        if ($INPUTARRAY[$temp_dim1][1] == $SEARCH) // find Partition ID
        {
            $found = $temp_dim1;
        }
    }

    if ($found > 0)
    {
        return $found;
    }

    return false;

}
// --------------------- END ---------------------


?>
