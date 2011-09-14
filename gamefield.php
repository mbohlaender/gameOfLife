<?php

/**
 * Build the gamefield.
 *
 * @version $Id$
 * @copyright 2011
 * @author Marcel BohlŠnder
 */
class GameField
{

	private $gameFieldArray;

	function __construct($_gameFieldArray=array(
		array(" "," ","A"," "," "),
		array("A","A"," "," "," "),
		array(" "," ","A","A"," "),
		array(" ","A"," "," "," "),
		array(" "," "," "," "," ")
		))
	{
		$this->gameFieldArray=$_gameFieldArray;
	}


	/**
	 * gameField::ColumnsOfGameField()
	 *
	 * @return int number of columns
	 */
	public function columnsOfGameField()
	{
		return count($this->gameFieldArray[0]);
	}
	/**
	 * gameField::RowsOfGameField()
	 *
	 * @return int number of rows
	 */
	public function rowsOfGameField()
	{
		return count($this->gameFieldArray);
	}

	/**
	 * Creates a gamefield-array with given rows and columns
	 *
	 * @param mixed $_row
	 * @param mixed $_column
	 * @return
	 */
	public function setGamefield($_row,$_column)
	{
		if(is_numeric($_row) && is_numeric($_column))
		{
			if($_row < 20)
			{
				$_row= 20;

			}
			if($_row>100)
			{
				$_row=100;
			}
			if($_column < 20)
			{
				$_column=20;
			}
			if($_column>100)
			{
				$_column=100;
			}
			for($i=0;$i<(int)$_row;$i++)
			{
				for($j=0;$j<(int)$_column;$j++)
				{
					$this->gameFieldArray[$i][$j]=' ';
				}
			}
			$this->log("Gamefield with ".$_column." columns and ".$_row." rows was build.\n");
			return true;
		}
		else
		{
			$this->log("Gamefield with ".$_column." columns and ".$_row." rows couldn't build.\n");
			return false;
		}
	}

	/**
	 * Fill at a specific point the array
	 *
	 * @param mixed $_row
	 * @param mixed $_column
	 * @param mixed $_doa
	 * @return
	 */
	public function setGamefieldCell($_row,$_column,$_doa)
	{
		$this->gameFieldArray[$_row][$_column]=$_doa;
	}

	/**
	 * Check a given cell for dead or alive
	 *
	 * @param mixed $_row
	 * @param mixed $_column
	 * @return String alive or dead
	 */
	public function checkDeadOrAlive($_row,$_column)
	{
		$tempArray = $this->gameFieldArray();
		if($tempArray[$_row][$_column]=="A")
		{
			return "alive";
		}
		if($tempArray[$_row][$_column]==" ")
		{
			return "dead";
		}
	}

	/**
	 * GameField::gameFieldArray()
	 *
	 * @return array gamefield
	 */
	public function gameFieldArray()
	{
		return $this->gameFieldArray;
	}
	/**
	 * Gives the neighbor cells with their status
	 *
	 * @param int $_row
	 * @param int $_column
	 * @return array $neighborHoodPlaces
	 */
	public function neighborCells($_row,$_column)
	{
		$tempRow;
		$tempColumn;
		$neighborHoodPlaces = array();
		for($i=0;$i<3;$i++)
		{
			for($j=0;$j<3;$j++)
			{
				$x=$i-1+$_row;
				$y=$j-1+$_column;
				if (isset($this->gameFieldArray[$x][$y]))
				{ //wert aus gamefield übernehmen
					if($x==$_row && $y==$_column)
					{
						$neighborHoodPlaces[$i][$j]="C";
					}
					else
					{
						$neighborHoodPlaces[$i][$j]=$this->gameFieldArray[$x][$y];
					}
				}
				else
				{ //n muss rein
					$neighborHoodPlaces[$i][$j]="N";

				}

			}
		}
		return $neighborHoodPlaces;
	}

	public function log($_message)
	{
		$tempString=file("help/bool.txt",FILE_IGNORE_NEW_LINES);
		if($tempString[0]=="true")
		{
			$handle = fopen("help/logfiles.txt", "a+");
			fwrite($handle, "$_message ".date("d/m/Y; H:i:s")."\r\n");
			fclose($handle);
		}
	}



}

?>