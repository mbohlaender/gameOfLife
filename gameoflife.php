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
$simulator = new Simulator($gamefield);


$gamefield->setGamefield(20,40);
$gamefield->readFormsOutOfTxtInArray();
//$output=new ConsoleOutput();

$output = new JpegOutput();
$output->setName("Test");

for($i=0;$i<10;$i++)
{
	$output->setCounter($i+1);
	$output->outputGamefield($gamefield);
	$simulator->simulation();
}





?>