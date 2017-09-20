<?php
 /*||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 * |||               EZE-DATABASE 2.0 ALPHA (PHP CLASS)                   |||
 * ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 * |||           MIT License https://opensource.org/licenses/MIT          |||
 * |||      Copyright (C) 2017 Biznatch Enterprises - Biznaturally.ca     |||
 * |||                                                                    |||
 * |||   Permission is hereby granted, free of charge, to any person      |||
 * |||   obtaining a copy of this software and associated documentation   |||
 * |||   files (the "Software"), to deal in the Software without          |||
 * |||   restriction, including without limitation the rights to use,     |||
 * |||   copy, modify, merge, publish, distribute, sublicense, and/or     |||
 * |||   sell copies of the Software, and to permit persons to whom the   |||
 * |||   Software is furnished to do so, subject to the following         |||
 * |||   conditions: The above copyright notice and this permission       |||
 * |||   notice shall be included in all copies or substantial portions   |||
 * |||   of the Software. THE SOFTWARE IS PROVIDED "AS IS", WITHOUT       |||
 * |||   WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT      |||
 * |||   LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A      |||
 * |||   PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE    |||
 * |||   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES    |||
 * |||   OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR    |||
 * |||   OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE        |||
 * |||   SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.           |||
 * ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/
 
 /*||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
 * |||                       DATABASE DATA FORMAT:                        |||
 * ||| «ææ|æ¦SECTOR¦æ|ææ|æ¦SECTOR¦ææ¦SECTOR¦æ|ææ|æ¦SECTOR¦ææ¦SECTOR¦æ|ææ» |||
 * |||    | Cluster0 |  |      Cluster1      |  |      Cluster2      |    |||
 * |||                                                                    |||
 * ||| DBS_DATA[P#][0][0] = Partition ID (Cluster 0, Sector 0)            |||
 * ||| DBS_DATA[P#][1][0] = Cluster 1 ID (Sector 0)                       |||
 * ||| DBS_DATA[P#][1][1] = Cluster 1, Sector 1 Data                      |||
 * ||| DBS_DATA[P#][1][2] = Cluster 1, Sector 2 Data                      |||
 * ||| DBS_DATA[P#][2][0] = Cluster 2 ID (Sector 0)                       |||
 * ||| DBS_DATA[P#][2][1] = Cluster 2, Sector 1 Data                      |||
 * ||| DBS_DATA[P#][2][2] = Cluster 2, Sector 2 Data                      |||
 * ||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||*/

//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& START EZE_DBS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
class EZE_DBS
{
    public var $DBS_PATH = null;
    public var $DBS_DATA = array();

    public var $PARTITION_START = '«æ';		// PARTITION START
	public var $PARTITION_END = 'æ»';		// PARTITION END

	public var $CLUSTER_START = 'æ|';		// CLUSTER SECTOR START
	public var $CLUSTER_END = '|æ';			// CLUSTER SECTOR END

	public var $SECTOR_START = 'æ¦';		// SECTOR START
	public var $SECTOR_END = '¦æ';			// SECTOR END

    //----------------------------------
    // Load_Database()
    //  Desc: 
    //  Args: $FILENAME -> (Full file path for EZE-DATABASE)
    //  Actions: 
    //  Returns: String -> Status Message

        public function Load_Database($FILENAME)
        {
            if ($FILENAME !== null)
            {
                $this->Parse_Database($this->Read_Filecontents($FILENAME));
                return "Database: " . $FILENAME . " Loaded";
                $this->$DBS_PATH = $FILENAME;
            }
            else
            {
                return "Invalid Filename";
            }

        }
    //----------------------------------

    //----------------------------------
    // Save_Database()
    //  Desc: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Save_Database($FILENAME)
        {
            $this->Write_Filecontents($this->Compile_Datebase($FILENAME));
        }
    //----------------------------------

    //----------------------------------
    // Unload_Database()
    //  Desc: 
    //  Actions: 
    //  Returns: Success message

        public function Unload_Database()
        {
            $this->DBS_PATH = null;
            unset($DBS_DATA);
            $this->DBS_DATA = array();
            return "Database Unloaded"
        }
    //----------------------------------

    //----------------------------------
    // Add_Partition()
    //  Desc: 
    //  Args: PARTITION_ID -> string
    //  Args: CLUSTERS_SECTORS -> Array containing Cluster/Sector pairings EX: $CLUSTER_SECTORS[ClusterNumer][SectorNumber] = "Data1"
    //  Actions: 
    //  Returns: Success or error

        public function Add_Partition($PARTITION_ID, $CLUSTERS_SECTORS)
        {
            if ($PARTITION_ID == null)
            {
        return "Partition ID can't be null";
            }
            
            if (!$CLUSTER_SECTORS == null)
            {
        return "Partition ID can't be null";
            }

            $PARTITION_NEXT = count($this->DBS_DATA);
            $this->DBS_DATA[$PARTITION_NEXT][0][0] = $this->XSS_Protect($PARTITION_ID);

                for ($cluster_current = 1; $cluster_current<= count($CLUSTERS_SECTORS); $cluster_current++)
                {
                    for ($sector_current = 0; $sector_current<= count($CLUSTERS_SECTORS[$cluster_current]) - 1; $sector_current++)
                    {
                        $this->DBS_DATA[$PARTITION_NEXT][$cluster_current][$sector_current] = $CLUSTERS_SECTORS[$cluster_current][$sector_current]:
                    }
                }

  
        return "Partitions Added: " . $PARTITION_NEXT;
        }
    //----------------------------------

    //----------------------------------
    // Add_Partitions() *not finished
    //  Desc: Multi-function of Add_Partition
    //  Actions: 
    //  Args: PARTITION_IDS -> Array containing Partition IDs and referenced for PartitionIDNumber
    //  Args: CLUSTERS_SECTORS -> Array containing Cluster/Sector pairings EX: $CLUSTER_SECTORS[PartitionIDNumber][ClusterNumer][SectorNumber] = "Data1"
    //  Returns: Success or error

        public function Add_Partitions($PARTITION_IDS, $CLUSTERS_SECTORS)
        {
        return "N/A";
        }

    //----------------------------------

    //----------------------------------
    // Change_PartitionID()
    //  Desc: 
    //  Args: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Change_PartitionID($OLDID, $NEWID)
        {
            if ($NEWID !== "")
            {
                $PARTITION_TEMP = $this->Find_Partition($OLDID);

                if ($PARTITION_TEMP !== null)
                {
                    $DBS_DATA[$PARTITION_TEMP] = $this->XSS_Protect($NEWID);
                }
            }
        }
    //----------------------------------

    //----------------------------------
    // Change_PartitionIDS()   *NOT FINISHED*
    //  Desc: Multi-function of Change_PartitionID
    //  Args: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Change_PartitionIDS($OLDIDS, $NEWIDS)
        {
        return "N/A";
        }
    //----------------------------------

    //----------------------------------
    // Remove_Partition()   *MUST FINISH!*
    //  Desc: 
    //  Args: PARTITION_ID -> string 
    //  Actions: 
    //  Returns: none

        public function Remove_Partition($PARTITION_ID)
        {
        return "N/A";
        
            for ($cluster_current = 1; $cluster_current<= count($this->DBS_DATA[$PARTITION_ID]); $cluster_current++)
			{
                for ($sector_current = 0; $sector_current<= count($this->DBS_DATA[$PARTITION_ID][$cluster_current]) - 1; $sector_current++)
			    {
                    $this->DBS_DATA[$PARTITION_ID][$cluster_current][$sector_current] = null;
                }
            }

            $this->DBS_DATA[$PARTITION_ID][$cluster_current][$sector_current] = null;
        }
    //----------------------------------

    //----------------------------------
    // Remove_Partitions()   *NOT FINISHED*
    //  Desc: Multi-function of Remove_Partition
    //  Args: PARTITION_IDS -> array
    //  Actions: 
    //  Returns: none

        public function Remove_Partitions($PARTITION_IDS)
        {
        return "N/A";
        }
    //----------------------------------

    //----------------------------------
    // Find_Partition()
    //  Desc: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Find_Partition($PARTITION_ID)
        {
            for ($partition_current = 1; $partition_current<= count($this->$DBS_DATA); $partition_current++)
		    {
                if ($PARTITION_ID == $this-$DBS_DATA)
                {
                    return $partition_current;   
                }
            }
        return null;
        }
    //----------------------------------

    //----------------------------------
    // Add_Cluster()
    //  Desc: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Add_Cluster($PARTITION_ID, $CLUSTER, $SECTORS, $MODE = 0)
        {
            switch ($MODE)
            {  
                case 0: // Cluster Number
                    $CLUSTER_TEMP = $CLUSTER;
                    break;
                case 1: // Find Cluster by First Sector
                    // Not implemented
                    break;
            }

            $PARTITION_TEMP = $this->Find_Partition($PARTITION_ID);

            if ($PARTITION_TEMP !== null)
            {    
                for ($i = 0; $i<= count($SECTORS) - 1; $i++)
                {
                    $SECTOR_NUMBER = count($DBS_DATA[$PARTITION_TEMP][$CLUSTER_TEMP]);
                    $SECTOR_DATA = $SECTORS[i];
                    $DBS_DATA[$PARTITION_TEMP][$CLUSTER_TEMP][$SECTOR_NUMBER] = $this->XSS_Protect($SECTOR_DATA);
                }
            
            }
        }
    //----------------------------------

    //----------------------------------
    // Add_Clusters()    *NOT FINISHED*
    //  Desc: Multi-function of Add_Cluster()
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Add_Clusters($PARTITION_IDS, $CLUSTERS, $SECTORS, $MODE = 0)
        {
        return "N/A";
        }
    //----------------------------------
   
    //----------------------------------
    // Change_Cluster()      *NOT FINISHED*
    //  Desc: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Change_Cluster($PARTITION_ID, $OLD_CLUSTER, $NEW_CLUSTER, $MODE = 0)
        {
        return "N/A"; 
        }
    //----------------------------------

    //----------------------------------
    // Change_Clusters()   *NOT FINISHED*
    //  Desc: Multi-function of Chane_Cluster()
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Change_Clusters($PARTITION_IDS, $OLD_CLUSTERS, $NEW_CLUSTERS, $MODE = 0)
        {
        return "N/A";
        }
    //----------------------------------

    //----------------------------------
    // Remove_Cluster()     *NOT FINISHED*
    //  Desc:
    //  Args: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Remove_Cluster($PARTITON_ID, $CLUSTER_NUMBER)
        {
        return "N/A";
        }
    //----------------------------------

    //----------------------------------
    // Remove_Clusters()    *NOT FINISHED*
    //  Desc: Multi-function of Remove_Cluster()
    //  Args: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Remove_Clusters($PARTITON_ID, $CLUSTER_NUMBER)
        {
        return "N/A";
        }
    //----------------------------------

    //----------------------------------
    // Add_Sector()   *MUST FINISH!*
    //  Desc: 
    //  Args: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Add_Sector($PARTITION_ID, $CLUSTER_NUMBER, $SECTOR_DATA, $MODE = 0)
        {
            $PARTITION_TEMP = $this->Find_Partition($PARTITION_ID);

            if ($PARTITION_TEMP !== null)
            {    
                for ($i = 0; $i<= count($SECTORS) - 1; $i++)
                {
                    $SECTOR_NUMBER = count($DBS_DATA[$PARTITION_TEMP][$CLUSTER_TEMP]);
                    $SECTOR_DATA = $SECTORS[i];
                    $DBS_DATA[$PARTITION_TEMP][$CLUSTER_TEMP][$SECTOR_NUMBER] = $this->XSS_Protect($SECTOR_DATA);
                }
            
            }
        }
    //----------------------------------

    //----------------------------------
    // Add_Sectors()    *NOT FINISHED*
    //  Desc: 
    //  Args: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Add_Sectors($PARTITION_IDS, $CLUSTERS, $SECTORS, $MODE = 0)
        {
        return "N/A";
        }
    //----------------------------------

    //----------------------------------
    // Change_Sector()   *MUST FINISH!*
    //  Desc: 
    //  Args: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Change_Sector($PARTITION_ID, $CLUSTER_NUMBER, $SECTOR_NUMBER, $SECTOR_NEW)
        {
            $PARTITION_TEMP = $this->Find_Partition($PARTITION_ID);

            if ($PARTITION_TEMP !== null)
            {    
                for ($i = 0; $i<= count($SECTORS) - 1; $i++)
                {
                    $DBS_DATA[$PARTITION_TEMP][$CLUSTER_NUMBER][$SECTOR_NUMBER] = $this->XSS_Protect($SECTOR_NEW);
                }
            }
        }
    //----------------------------------

    //----------------------------------
    // Change_Sectors()   *NOT FINISHED*
    //  Desc: Multo-function of Change_Sector
    //  Args: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Change_Sectors($PARTITION_ID, $CLUSTER_NUMBER, $SECTOR_DATA)
        {
        return "N/A";
        }
    //----------------------------------

    //----------------------------------
    // Remove_Sector()   *MUST FINISH!*
    //  Desc: 
    //  Args: 
    //  Args: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Remove_Sector($PARTITION_ID, $CLUSTER, $SECTOR, $MODE = 0)
        {
            $this->$DBS_DATA[$PARTITION_ID][$CLUSTER_NUMBER][$SECTOR_NUMBER] = null;
        }
    //----------------------------------

    //----------------------------------
    // Remove_Sectors()   *NOT FINISHED*
    //  Desc: Multi-function of Remove_Sector()
    //  Args: 
    //  Args: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Remove_Sectors($PARTITION_IDS, $CLUSTER_NUMBER, $SECTOR_NUMBER)
        {
        return "N/A";
        }
    //----------------------------------

    //----------------------------------
    // Parse_Partitions()
    //  Desc: 
    //  Args: RAW_DATA string
    //  Actions:     
    //  Returns: ARRAY CONTAINING PARTITION RAW_DATA 

        public function Parse_Partitions($RAW_DATA)
        {
            $OUTPUT = array();
            $TMP_POS = 1;
            $TMP_CNT = 0;
            $TMP_STR = "";

            	for ($i = 0; $i<= substr_count($RAW_DATA) - 1; $i++)
				{
                    $TMP_STR = Parse_String($RAW_DATA, $this->$PARTITION_START, $this->$PARTITION_END, $TMP_POS);

                    if ($TMP_STR[0] !== ""){
                        $OUTPUT[$TMP_CNT] = $TMP_STR[0];
                        $TMP_CNT++;
                        $TMP_POS = $TMP_STR[2];
                    }
                }
        
        return $OUTPUT;
        }
    //----------------------------------

    //----------------------------------
    // Parse_Clusters()
    //  Desc: 
    //  Args: 
    //  Actions: 
    //  Returns: 

        public function Parse_Clusters($RAW_DATA)
        {
            $OUTPUT = array();
            $TMP_POS = 1;
            $TMP_CNT = 0;
            $TMP_STR = "";

            	for ($i = 0; $i<= substr_count($RAW_DATA) - 1; $i++)
				{
                    $TMP_STR = Parse_String($RAW_DATA, $this->$CLUSTER_START, $this->$CLUSTER_END, $TMP_POS);

                    if ($TMP_STR[0] !== ""){
                        $OUTPUT[$TMP_CNT] = $TMP_STR[0];
                        $TMP_CNT++;
                        $TMP_POS = $TMP_STR[2];
                    }
                }

        return $OUTPUT;
        }
    //----------------------------------

    //----------------------------------
    // Parse_Sectors()
    //  Desc: 
    //  Args: 
    //  Actions:     
    //  Returns: 

        public function Parse_Sectors($RAW_DATA)
        {
            $OUTPUT = array();
            $TMP_POS = 1;
            $TMP_CNT = 0;
            $TMP_STR = "";

            	for ($i = 0; $i<= substr_count($RAW_DATA) - 1; $i++)
				{
                    $TMP_STR = Parse_String($RAW_DATA, $this->$SECTOR_START, $this->$SECTOR_END, $TMP_POS);

                    if ($TMP_STR[0] !== ""){
                        $OUTPUT[$TMP_CNT] = $TMP_STR[0];
                        $TMP_CNT++;
                        $TMP_POS = $TMP_STR[2];
                    }
                }

        return $OUTPUT;
        }
    //----------------------------------

    //----------------------------------
    // Parse_Database()
    //  Desc: 
    //  Args: 
    //  Actions:     
    //  Returns: 

        public function Parse_Database($RAW_DATA)
        {
            $OUTPUT = array();
			$PARTITIONS_TEMP = array();
			$CLUSTERS_TEMP = array();
			$SECTORS_TEMP = array();

            // Extract all Partitions into an array
            $PARTITIONS_TEMP = Parse_Paritions($RAW_DATA);

				for ($partition_current = 0; $partition_current<= count($PARTITIONS_TEMP) - 1; $partition_current++)
				{
                    // Extract all Clusters into an array
                    $CLUSTERS_TEMP = Parse_Clusters($PARTITIONS_TEMP[$partition_current]);

					if (count($CLUSTERS_TEMP) > 0)
					{
                        for ($cluster_current = 0; $cluster_current<= count($CLUSTERS_TEMP) - 1; $cluster_current++)
						{
                            // Extract all Sectors into an array
                            $SECTORS_TEMP = Parse_Sectors($CLUSTERS_TEMP[$cluster_current]);

                            if (count($SECTORS_TEMP) > 0)
							{
								for ($sector_current = 0; $sector_current<= count($SECTORS_TEMP) - 1; $sector_current++)
								{
									// EXTRACT SECTORS INTO OUTPUT[P#][C#][S#]
									$OUTPUT[$partition_current][$cluster_current][$sector_current] = $SECTORS_TEMP[$sector_current];
								}
							}

                        }
                    }
                }

			unset($PARTITIONS_TEMP);
			unset($CLUSTERS_TEMP);
			unset($SECTORS_TEMP);

        return $OUTPUT;
        }
    //----------------------------------

    //----------------------------------
    // Compile_Database()
    //  Desc: 
    //  Actions:     
    //  Returns: RAW FORMATTED DATA PARTITIONS/CLUSTERS/SECTORS

        public function Compile_Database()
        {
            $RAW_DATA;

            for ($partition_current = 0; $partition_current<= count($DBS_DATA) - 1; $partition_current++)
            {
                $CLUSTER_TEMP = null;

                for ($cluster_current = 0; $cluster_current<= count($DBS_DATA[$partition_current]) - 1; $cluster_current++)
                {
                    $SECTOR_TEMP = null;

                    for ($sector_current = 0; $sector_current<= count($DBS_DATA[$partition_current][$cluster_current]) - 1; $sector_current++)
                    {
                        if ($DBS_DATA[$partition_current][$cluster_current][$sector_current] !== null)
                        {
                            $SECTOR_TEMP = $SECTOR_TEMP . $this->$SECTOR_START . $DBS_DATA[$partition_current][$cluster_current][$sector_current] . $this->$SECTOR_END;
                        }
                    }                    

                    if ($SECTOR_TEMP !== null)
                    {
                        $CLUSTER_TEMP = $CLUSTER_TEMP . $this->$CLUSTER_START . $SECTOR_TEMP . $this->$CLUSTER_END;
                    }
                }
                
                if ($CLUSTER_TEMP !== null)
                {
                    // add new partition to raw_data
                    $RAW_DATA = $RAW_DATA . $this->$PARTITION_START . $CLUSTER_TEMP . $this->$PARTITION_END . "\n";
                }
            }

        return $RAW_DATA;
        }
    //----------------------------------

    //----------------------------------
    // Import_Database()   *NOT FINISHED*
    //  Desc: 
    //  Args: none
    //  Actions: 
    //  Returns: none

        public function Import_Database()
        {
       return "N/A";
        }

    //----------------------------------

}
//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& END EZE_DBS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& START DBS_TOOLS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
class DBS_TOOLS extends EZE_DBS
{
	//----------------------------------
	// XSS_Protect()
    //  Desc: 
    //  Args: 
    //  Actions: 
    //  Returns: 

	    public function XSS_Protect($STRING)
        {
			$STRING = str_replace($PARTITION_START, "", $STRING);
			$STRING = str_replace($PARTITION_END, "", $STRING);
			$STRING = str_replace($CLUSTER_START, "", $STRING);
			$STRING = str_replace($CLUSTER_END, "", $STRING);
			$STRING = str_replace($SECTOR_START, "", $STRING);
			$STRING = str_replace($SECTOR_END, "", $STRING);
			return $STRING;
	    }
	//----------------------------------

	//----------------------------------
	// Parse_String()
    //  Desc: text from string using open / close substrings
    //  Args: 
    //  Actions: 
    //  Returns: 

	    public function Parse_String($STRING, $START_SUBSTR, $END_SUBSTR, $START_POS)
		{
            $OUTPUT = array();
            $TMP_STR = "";

		    $pos_begin = strpos($STRING, $START_SUBSTR, $START_POS);
		    $pos_end = strpos($STRING, $END_SUBSTR, $posa + strlen($START_SUBSTR));

            $TMP_STR = substr($STRING, $pos_begin + strlen($START_SUBSTR), $pos_end - $pos_begin - strlen($START_SUBSTR));

                if ($TMP_STR !== "")
                {
                    $OUTPUT[0] = $TMP_STR;
                    $OUTPUT[1] = $pos_begin;
                    $OUTPUT[2] = $pos_end;
                }

		    return $OUTPUT;
	    }	
	//----------------------------------

	//----------------------------------
	// Delete_File()
    //  Desc: 
    //  Args: 
    //  Actions: 
    //  Returns: 

		public function Delete_File($FILENAME)
		{
			return unlink($FILENAME);
		}
	//----------------------------------

	//----------------------------------
	// Read_Filecontents()
    //  Desc: READ FILE CONTENTS INTO STRING
    //  Args: INPUT_FILE = Full Filename to Read (Required)
    //  Actions: 
    //  Returns: 

		public function Read_Filecontents($INPUT_FILENAME)
		{
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
	//----------------------------------

	//----------------------------------
	// Write_Filecontents()
    //  Desc: WRITE FILE CONTENTS FROM STRING
    //  Args: OUTPUT_FILENAME = Full filename to write    (Required)
    //  Args: FILE_CONTENTS = DATA STRING to write        (Required)
    //  Args: MODE: w = overwrite full file with string   (Required)
    //  Args: MODE: a = Append string to end of file      (Required)
    //  Args: MODE: x = Append string to start of file    (Required)
    //  Actions: 
    //  Returns: 
	
		public function Write_Filecontents($OUTPUT_FILENAME, $FILE_CONTENTS, $MODE = null)
		{
			if (file_exists($INPUT_FILENAME)){ } else { 
		return 'ERROR: ' .  $OUTPUT_FILENAME . ' File Not Found!';
			}		

			if (is_writeable($OUTPUT_FILENAME)){
				if ($MODE == null) { $MODE = 'w'; }
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
	//----------------------------------

//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& END DBS_TOOLS &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&



 ?>