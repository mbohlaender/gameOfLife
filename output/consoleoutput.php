<?php

include_once "baseoutput.php";

/**
 * Gives the simulation process on the console
 *
 * @version $Id$
 * @copyright 2011
 */
class ConsoleOutput extends BaseOutput
{
	  /**
	   * Gives out the array, for every simulation step a new one
	   *
	   * @param gameField $_gf
	   * @return
	   */
	  function outputGameField(gameField $_gf)
	  {
	  	$tempArray = $_gf->gameFieldArray();
	  	$tempString="";
	  	for($i=0;$i<$_gf->rowsOfGameField();$i++)
	  	{
	  		for($j=0;$j<$_gf->columnsOfGameField();$j++)
	  		{
	  			$tempString = $tempString.$tempArray[$i][$j]."|";
	  		}
	  		echo $tempString."\n";
	  		$tempString ="";
	  	}
	  	echo "\n";
	  }
}


?>