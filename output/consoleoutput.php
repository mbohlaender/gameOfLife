<?php

include_once "baseoutput.php";

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */
class ConsoleOutput extends BaseOutput
{
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