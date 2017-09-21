<?php
/* ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 * |||      EZE-DATABASE BETA 1.6 - (C) 2002-2017 Biznatch Enterprises            |||
 * |||    Double Nested Database Parser/Synthesizer with unique data IDs          |||
 * |||       Biznaturally.ca - https://github.com/BiznatchEnterprises             |||
 * |||                                                                            |||
 * ||| EZE-DATABASE LOADER - function INPUTS                       			 	  |||
 * ||| $DBS_COMMAND                                                               |||
 * ||| $LOADER_FILENAME     													  |||
 * ||| $DBS_FILENAME                                                              |||
 * ||| $DBS_OPTION1 															  |||
 * ||| $DBS_OPTION3 													 	      |||
 * ||| $DBS_OPTION4  															  |||
 * ||| $DBS_OPTION5 															  |||
 * ||| $DBS_STATUS true/false (echo debuging)									  |||
 * |||                                                                            |||
 * ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/

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

// ---------- XSS INJECTION PROTECTION START ---------
function XSS_PROTECT($DATA)
{
    $DATA = str_replace(chr(13) . chr(10), '', $DATA);          // WIN/DOS: chr(13) . chr(10)    UNIX: "\n"

    $DATA = str_replace('<' . chr(225), '', $DATA);       		// PARTITION START
    $DATA = str_replace(chr(225) . '>', '', $DATA); 			// PARTITION END
    $DATA = str_replace(chr(224) . '[', '', $DATA); 		    // SECTOR START
    $DATA = str_replace(']' . chr(224), '', $DATA); 			// SECTOR END

    $DATA = str_replace(chr(223) . '{', '', $DATA); 			// SECTOR LABEL START
    $DATA = str_replace('}' . chr(223), '', $DATA); 			// SECTOR LABEL END

    return $DATA;
}
// ---------- XSS INJECTION PROTECTION END ---------

// ---------------------- EZE_DBS_LOADER INIT START ----------------------

function EZE_DBS_LOADER($DBS_COMMAND, $ENGINE_FILENAME, $DBS_FILENAME, $DBS_OPTION1 = NULL, $DBS_OPTION2 = NULL, $DBS_OPTION3 = NULL, $DBS_OPTION4 = NULL, $DBS_OPTION5 = NULL, $DBS_STATUS = NULL)
{

	global $EZE_DBS_OUTPUT;
    global $LAST_DBS_ENGINE;

    $CURRENTPATH = dirname(__FILE__);
    $CURRENTPATH = str_replace("index.php", "", $CURRENTPATH);

	$ERROR_MSG1 = '<b>EZE-DATABASE-LOADER ERROR:</b> PARSER FILE INVALID!';
    $ERROR_MSG1 .= '<br>(Unable to open file, but permissions are OK)<br>';
    $ERROR_MSG1 .= '<br>Command: ' . $DBS_COMMAND . '<br>Parser File: ' . $ENGINE_FILENAME . '<br>Databse File: ' . $DBS_FILENAME;
	
    $ERROR_MSG2 = '<b>EZE-DATABASE-LOADER ERROR:</b> PARSER FILE INVALID!';
    $ERROR_MSG2 .= '<br>(Unable to open file, possible file permissions error.)';
    $ERROR_MSG2 .= '<br><br>Command: ' . $DBS_COMMAND . '<br>Parser File: ' . $ENGINE_FILENAME . '<br>Databse File: ' . $DBS_FILENAME;

	if ($ENGINE_FILENAME <> 'LASTLOADER')
    {
		$CURRENTPATH = dirname(__FILE__);
		$CURRENTPATH = str_replace("EZE-DATABASE-LOADER.php", "", $CURRENTPATH);
        
		if (is_readable($CURRENTPATH . '/' . $ENGINE_FILENAME))
        {
		    $DBS_ENGINE = '';
		    $open_file = fopen($CURRENTPATH . '/' . $ENGINE_FILENAME,"r+");

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
// ---------------------- EZE_DBS_LOADER INIT END ----------------------

//-----------------EZE_DATABASE PARSER START--------------------
class EZE_DATABASE
{

	var $EZEDBS_DATA;
	var $POSTMP;
	var $EZEDBS_ENTRIES;

	function ParseString($STRING, $START_SUBSTR, $END_SUBSTR, $START_POS)
    {
	    $posa = strpos($STRING, $START_SUBSTR, $START_POS);
	    $posb = strpos($STRING, $END_SUBSTR, $posa + strlen($START_SUBSTR));
	    $this->POSTMP = $posa;
	    return substr($STRING, $posa + strlen($START_SUBSTR), $posb - $posa - strlen($START_SUBSTR));
	}	


	function ParseDatabase($DATASTRING, $START_SUBSTR, $END_SUBSTR)
    {
	    $this->EZEDBS_DATA = array();
	    $TOTCOMPS = substr_count($DATASTRING, $START_SUBSTR);
        	for ($bb = 1; $bb <= $TOTCOMPS; $bb++)
            {
		        $DATA[$bb + 1] = $this->ParseString($DATASTRING, $START_SUBSTR, $END_SUBSTR, $this->POSTMP);
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

}
//-----------------EZE_DATABASE PARSER END--------------------

//-------------LOAD_RAWDATA START----------------
function LOAD_RAWDATA($INPUTFILE)
{

    $CURRENTPATH = dirname(__FILE__);
    $CURRENTPATH = str_replace("index.php", "", $CURRENTPATH);

    $FILECONTENTS = '';

	if (!file_exists($CURRENTPATH . $INPUTFILE))
    {
        if ($DBS_STATUS == 'TRUE')
        {
            echo '<br><u><b>ERROR:</b>' . $CURRENTPATH . $INPUTFILE . ' File Not Found!</u><br>';
        }

        return 'FNF';
    }

	if (is_readable($CURRENTPATH . $INPUTFILE))
    {
	    $open_file = fopen($CURRENTPATH . $INPUTFILE,"r+");

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
	        echo '<br><u><b>ERROR:</b>' . $CURRENTPATH . $INPUTFILE . ' Not Readable!</u><br>';
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
	        echo '<br><u><b>ERROR:</b>' . $CURRENTPATH . $INPUTFILE . ' Empty File!</u><br>';
        }

        return '<EmptyContents>';
    }
}
//-------------LOAD_RAWDATA END----------------

//-------------WRITE_RAWDATA START----------------
function WRITE_RAWDATA($OUTPUTFILE, $CONTENTS)
{
	$CURRENTPATH = dirname(__FILE__);
	$CURRENTPATH = str_replace("index.php", "", $CURRENTPATH);
	$handle = fopen($CURRENTPATH . $OUTPUTFILE, "w");
	fputs($handle, $CONTENTS);
	fclose($handle);
}
//-------------WRITE_RAWDATA END----------------

?>