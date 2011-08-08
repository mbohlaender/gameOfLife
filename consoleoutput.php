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
	  	$tempString="";
	  	$tempArray=$_gf->gameFieldArray();
	  	for($i=0;$i<count($_gf->gameFieldArray());$i++)
	  	{
	  		for($j=0;$j<count($_gf->gameFieldArray());$j++)
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