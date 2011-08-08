<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
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

	public function setGamefield($_row,$_column)
	{
		if($_row < 10)
		{
			$_row=10;

		}
		if($_row>20)
		{
			$_row=20;
		}
		if($_column < 10)
		{
			$_column=10;
		}
		if($_column>40)
		{
			$_column=40;
		}
		for($i=0;$i<$_row;$i++)
		{
			for($j=0;$j<$_column;$j++)
			{
				$this->gameFieldArray[$i][$j]=' ';
			}
		}
	}

	public function setGamefieldCell($_row,$_column,$_doa)
	{
		$this->gameFieldArray[$_row][$_column]=$_doa;
	}

	public function countA()
	{
		for($k=0;$k<40;$k++)
		{
			for($j=0;$j<20;$j++)
			{
				if ($this->gameFieldArray[$k][$j] == "A") echo "A".$k.'-'.$j;
			}
		}
		echo "\n";
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

	public function readFormsOutOfTxtInArray()
	{
		$rowArray = file ("form/hour.txt",FILE_IGNORE_NEW_LINES);
		for($i=0;$i<count($rowArray);$i++)
		{
			$tempString = $rowArray[$i];
			$coords =explode(" ",$tempString,2);
			$this->setGamefieldCell($coords[0],$coords[1],"A");
		}
	}

	public function gameFieldArray()
	{
		return $this->gameFieldArray;
	}
	/**
	 * Gives in an array the coordinates from the neighbor cells back
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





}

?>