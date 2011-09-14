<?php

include_once "baseoutput.php";
include_once("./help/logsaver.php");
require_once("help/validation.php");

/**
 * Gives the simulation process on the console
 *
 * @version $Id$
 * @copyright 2011
 */
class output_console extends BaseOutput
{

	private $logsaver;

	function __construct()
	{
		$this->weProvide="console";
		$this->logsaver = new LogSaver();
		$this->logsaver->log("Console-output-plugin loaded\n");
	}

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