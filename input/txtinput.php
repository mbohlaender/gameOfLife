<?php

include_once("baseinput.php");

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */
class TxtInput extends baseinput
{
	private $gamefield;
	private $filename;

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