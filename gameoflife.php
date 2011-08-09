<?php

require "simulator.php";
require "gamefield.php";
require "jpegoutput.php";
require "consoleoutput.php";
require_once("gifoutput.php");
/**
 * Game of Life.
 *
 * @version $Id$
 * @copyright 2011
 */


$gamefield = new Gamefield();
$simulator = new Simulator($gamefield);


$gamefield->setGamefield(40,40);
$gamefield->readFormsOutOfTxtInArray();
//$output=new ConsoleOutput();

$output = new GifOutput();
//$output->setName("Test");

for($i=0;$i<10;$i++)
{
	//$output->setCounter($i+1);
	$output->outputGamefield($gamefield);
	$simulator->simulation();

}
$output->finishOutput();


?>