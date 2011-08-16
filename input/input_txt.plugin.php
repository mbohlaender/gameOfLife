<?php

include_once("baseinput.php");

/**
 * Read out of a txt file a form for simulation process
 *
 * @version $Id$
 * @copyright 2011
 */
class input_txt extends baseinput
{
	private $gamefield;
	private $filename;

	function __construct()
	{
		$this->weProvide="txt";
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
		}
		else
		{
		echo "File doesn't exist, check right name\n";
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


}

?>