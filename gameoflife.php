<?php

require "simulator.php";
require "gamefield.php";
require "jpegoutput.php";
require "consoleoutput.php";
/**
 * Game of Life.
 *
 * @version $Id$
 * @copyright 2011
 */


$gamefield = new Gamefield();
$gamefield->setGamefield(40,20);
$gamefield->readFormsOutOfTxtInArray();
$simulator = new Simulator($gamefield);
//$output=new ConsoleOutput();
$output = new JpegOutput();
$output->setName("Test");
for($i=0;$i<10;$i++)
{
	$output->setCounter($i+1);
	$output->outputGamefield($gamefield);
	$simulator->simulation();
}


//
//$jpegcreator->imagejpeg();



?>