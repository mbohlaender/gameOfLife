<?php

include_once("baseinput.php");
include_once("./help/logsaver.php");
/**
 * Read out of a txt file a form for simulation process
 *
 * @version $Id$
 * @copyright 2011
 */
class input_txt extends baseinput
{
	private $gamefield;
	private $filename="blinker";
	private $logsaver;
	function __construct()
	{
		$this->weProvide="txt";
		$this->logsaver = new LogSaver();
		$this->logsaver->log("Txt-Input-plugin loaded\n");
	}

	/**
	 * Sets filename for the file which shall be load
	 *
	 * @param mixed $_filename
	 * @return
	 */
	function setFilename($_filename)
	{
		if(file_exists("form/".$_filename.".txt"))
		{
			$this->filename = $_filename;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Reads the coordinates out of the txt and wrote them into the gamefield-array
	 *
	 * @param GameField $_gf
	 * @return
	 */
	function readIntoGamefield(GameField $_gf)
	{
		$this->gamefield = $_gf;

		$rowArray = file ("form/".$this->filename.".txt");
		for($i=0;$i<count($rowArray);$i++)
		{
			$tempString = $rowArray[$i];
			$coords =explode(" ",$tempString,2);
			$this->gamefield->setGamefieldCell((int)$coords[0],(int)$coords[1],"A");
		}

	}

	public function setParameter($_ArrayToCheck)
	{
		foreach($_ArrayToCheck as $opt => $value)
		{
			if($opt=="form"||$opt=="f")
			{
				if($this->setFilename($value)==true)
				{
					$this->logsaver->log($value." as form was loaded");
				}
				else
				{
					$this->logsaver->log($value." is no valid form to load");
					return false;
				}
			}
		}
		return true;
	}
}

?>