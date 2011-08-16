<?php

require_once ("simulator.php");
require_once ("gamefield.php");
require_once ("src/epf/epfpluginmanager.php");
/**
 * Game of Life.
 *
 * @version $Id$
 * @copyright 2011
 */


$gamefield = new Gamefield();
$simulator = new Simulator($gamefield);

$epfPManager = new epfPluginManager();
$in;
$out;
$speed=10;
$reps=10;
$x=30;
$y=20;
$form="blinker";
$bgcolor="Black";
$color="White";

if($argc >= 1)
{
	for($i=0;$i<$argc;$i++)
	{
		if($argv[$i] == "-in")
		{
			$in=$argv[$i+1];
		}
		if($argv[$i] == "-out")
		{
			$out=$argv[$i+1];
		}
		if($argv[$i] == "-x")
		{
			$x=$argv[$i+1];
		}
		if($argv[$i] == "-y")
		{
			$y=$argv[$i+1];
		}
		if($argv[$i] == "-speed")
		{
			$speed=$argv[$i+1];
		}
		if($argv[$i] == "-reps")
		{
			$reps=$argv[$i+1];
		}
		if($argv[$i]=="-form")
		{
			$form=$argv[$i+1];
		}
		if($argv[$i]=="-bgcolor")
		{
			$bgcolor=$argv[$i+1];
		}
		if($argv[$i]=="-color")
		{
			$color=$argv[$i+1];
		}
	}
}
try{
	$gamefield->setGamefield((int)$y,(int)$x);
	$epfPManager->loadPluginsOnDemand("input/","input_",".plugin.php","input_");
	if($epfPManager->hasPluginFor($in)==true)
	{
		$input = $epfPManager->getPluginFor($in);
		$input->setFilename($form);
		$input->readIntoGamefield($gamefield);
	}
	else
	{
		echo "No input plugin called ".$in." found, please type \"php gameoflife.php -help\" for help";
	}


	$epfPManager->loadPluginsOnDemand("output/","output_",".plugin.php","output_");
	if($epfPManager->hasPluginFor($out)==true)
	{
		$output = $epfPManager->getPluginFor($out);
		$output->setColor($color);
		$output->setBgColor($bgcolor);
		$output->setMs($speed);
		for($i=0;$i<$reps;$i++)
		{
			$output->setCounter($i+1);
			$output->outputGamefield($gamefield);
			$simulator->simulation();

		}
		$output->finishOutput();
	}
	else
	{
		echo "No output plugin called ".$out." found, please type \"php gameoflife.php -help\" for help";
	}

}
catch(Exception $e)
{
	echo "Wrong parameters, type -help";
}

?>