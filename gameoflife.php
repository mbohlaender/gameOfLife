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
$in = "txt";
$out = "gif";
$speed=10;
$reps=10;
$x=30;
$y=20;
$form="blinker";
$bgcolor="Black";
$color="White";

if($argc > 1)
{
	for($i=0;$i<$argc;$i++)
	{
		if($argv[$i] == "--in" || $argv[$i] == "-i")
		{
			$in=$argv[$i+1];
		}
		if($argv[$i] == "--out" || $argv[$i] == "-o")
		{
			$out=$argv[$i+1];
		}
		if($argv[$i] == "--width" || $argv[$i] == "-x")
		{
			$x=$argv[$i+1];
		}
		if($argv[$i] == "--height" || $argv[$i] == "-y")
		{
			$y=$argv[$i+1];
		}
		if($argv[$i] == "--speed" || $argv[$i] == "-s")
		{
			$speed=$argv[$i+1];
		}
		if($argv[$i] == "--reps" || $argv[$i] == "-r")
		{
			$reps=$argv[$i+1];
		}
		if($argv[$i]=="--form" || $argv[$i]=="-f")
		{
			$form=$argv[$i+1];
		}
		if($argv[$i]=="--bgcolor" || $argv[$i]=="-b")
		{
			$bgcolor=$argv[$i+1];
		}
		if($argv[$i]=="--color" || $argv[$i]=="-c")
		{
			$color=$argv[$i+1];
		}
		if($argv[$i]=="--help" || $argv[$i]=="-h")
		{
			$temp=$i;
			$rowArray = file ("help/help.txt");
			for($i=0;$i<count($rowArray);$i++)
			{
				echo $rowArray[$i];

			}
			$i=$temp;
			$temp=$i;
			$rowArray = file ("color/colors.txt");
			echo "\nPossible colors: \n________________\n";
			for($i=0;$i<count($rowArray);$i++)
			{
				$tempString = explode(" ",$rowArray[$i]);
				echo "-".$tempString[0]."\n";
			}
			$i=$temp;
			$temp=$i;
			echo "\nPossible forms: \n________________\n";
			foreach (glob("form/*.txt") as $filename)
			{
				$tempA = explode("/",$filename);
				$tempB = explode(".",$tempA[1]);
				echo "-".$tempB[0]."\n";
			}
			$i=$temp;

			echo "\n Example code: \n______________\n\"php gameoflife.php --in txt --out gif --speed 10 --width 30 --height 30 --reps 50 --form blinker --b Black -c DeepPink\"";
		}
	}
}
else
{
	echo "No parameters delivered, default parameters will set \n";
	$in = "txt";
	$out = "gif";
	$speed=10;
	$reps=10;
	$x=30;
	$y=20;
	$form="blinker";
	$bgcolor="Black";
	$color="White";
}
if($gamefield->setGamefield($y,$x)==true)
{
	$epfPManager->loadPluginsOnDemand("input/","input_",".plugin.php","input_");
	if($epfPManager->hasPluginFor($in)==true)
	{
		$input = $epfPManager->getPluginFor($in);
		if($input->setFilename($form)==true)
		{
			$input->readIntoGamefield($gamefield);
		}
		else
		{
			echo "Delivered form called ".$form." doesn't exists, check --help";
		}
	}
	else
	{
		echo "No input plugin called ".$in." found, please type \"php gameoflife.php --help\" for help";
	}

	$epfPManager->loadPluginsOnDemand("output/","output_",".plugin.php","output_");

	if($epfPManager->hasPluginFor($out)==true)
	{
		$output = $epfPManager->getPluginFor($out);
		if($output->setColor($color)==true && $output->setBgColor($bgcolor)==true)
		{
			if($output->setMs($speed)==true)
			{
				if(is_numeric($reps))
				{
					for($i=0;$i<(int)$reps;$i++)
					{
						$output->setCounter($i+1);
						$output->outputGamefield($gamefield);
						$simulator->simulation();
					}
					$output->finishOutput();
				}
				else echo "Invalid argument ".$reps." for repetitions, has to be an integer, check \"--help\" for help";

			}
			else
			{
				echo "Invalid argument ".$speed." for speed, has to be an integer, check \"--help\" for help";
			}
		}
	}
	else
	{
		echo "No output plugin called ".$out." found, please type \"php gameoflife.php --help\" for help";
	}
}
else echo $x." for x and/or ".$y." for y are invalid arguments. Check \"--help\" for help."
?>