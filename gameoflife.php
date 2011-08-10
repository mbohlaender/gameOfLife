<?php

require "simulator.php";
require "gamefield.php";
require "jpegoutput.php";
require "consoleoutput.php";
require "txtinput.php";
require_once("gifoutput.php");

/**
 * Game of Life.
 *
 * @version $Id$
 * @copyright 2011
 */

$gamefield = new Gamefield();
$simulator = new Simulator($gamefield);
$gamefield->setGamefield(10,10);

$input = new TxtInput();
$input->setFilename("blinker");
$input->readIntoGamefield($gamefield);

//$output = new GifOutput();
$output = new JpegOutput();
$output->setName("animation");
//$output = new ConsoleOutput();
for($i=0;$i<10;$i++)
{
	$output->setCounter($i+1);
	$output->outputGamefield($gamefield);
	$simulator->simulation();

}
$output->finishOutput();


?>