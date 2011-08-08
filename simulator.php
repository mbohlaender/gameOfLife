<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */


class Simulator{

	private $gamefield;
	private $gamefieldArrays;

	function __construct($_gamefield){
		$this->gamefield = $_gamefield;
	}

	function countDeadOrAliveCells($_row,$_column)
	{
		$tempArray = $this->gamefield->neighborCells($_row,$_column);
		$statisticArray;
		$tempAlive=0;
		$tempDead=0;
		for($i=0;$i<3;$i++)
		{
			for($j=0;$j<3;$j++)
			{
				if($tempArray[$i][$j]=="A")
				{
					$tempAlive++;
				}
				if($tempArray[$i][$j]==" ")
				{
					$tempDead++;
				}
			}
		}
		$statisticArray[0]=$tempAlive;
		$statisticArray[1]=$tempDead;
		return $statisticArray;
	}

	function checkDeadOrAlive($_row,$_column)
	{
		$tempArray = $this->gamefield->gameFieldArray();
		if($tempArray[$_row][$_column]=="A")
		{
			return "alive";
		}
		if($tempArray[$_row][$_column]==" ")
		{
			return "dead";
		}
	}

	function simulation(){
		$changings;
		$counter=0;
		$this->gamefieldArrays = $this->gamefield->gameFieldArray();
		for($i=0;$i<$this->gamefield->RowsOfGameField();$i++)
		{

			for($j=0;$j<$this->gamefield->ColumnsOfGameField();$j++)
			{
				$deadOrAlive=$this->checkDeadOrAlive($i,$j);
				$neighbors = $this->countDeadOrAliveCells($i,$j);

				if($deadOrAlive=="dead" && $neighbors[0]==3)
				{
					$changings[$counter]=array($i,$j,"A");
					$counter++;
				}
				elseif($deadOrAlive=="alive" && $neighbors[0]<2)
				{
					$changings[$counter]=array($i,$j," ");
					$counter++;

				}
				elseif($deadOrAlive=="alive" && $neighbors[0]>3)
				{
					$changings[$counter]=array($i,$j," ");
					$counter++;
				}

			}
		}


		for($k=0;$k<$counter;$k++)
		{
			$temp1=$changings[$k][0];
			$temp2=$changings[$k][1];
			$temp3=$changings[$k][2];
			$this->gamefield->setGamefieldCell($temp1,$temp2,$temp3);
		}

	}


}

?>