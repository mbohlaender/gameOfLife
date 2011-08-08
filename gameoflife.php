<?php

require "simulator.php";
require "gamefield.php";
require "jpegoutput.php";
require "consoleoutput.php";
require_once('GifCreator.php');
/**
 * Game of Life.
 *
 * @version $Id$
 * @copyright 2011
 */

// Instanciate the class (uses default options with the addition of width/height specified)
$gif = new GifCreator(0, 2, array(-1, -1, -1), 400, 200);
$gamefield = new Gamefield();
$simulator = new Simulator($gamefield);


$gamefield->setGamefield(40,40);
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

// Add each frame to the animation
$gif->addFrame(file_get_contents('img/Test1.jpg'), 200, true);
$gif->addFrame(file_get_contents('img/Test2.jpg'), 200, true);
$gif->addFrame(file_get_contents('img/Test3.jpg'), 200, true);
$gif->addFrame(file_get_contents('img/Test4.jpg'), 200, true);
$gif->addFrame(file_get_contents('img/Test5.jpg'), 200, true);
$gif->addFrame(file_get_contents('img/Test6.jpg'), 200, true);

// Output the animated gif
header('Content-type: imageg/gif');
echo $gif->getAnimation();

?>