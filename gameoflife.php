<?php

require_once ("simulator.php");
require_once ("gamefield.php");
require_once ("src/epf/epfpluginmanager.php");
require_once("help/logsaver.php");
require_once("help/validation.php");
/**
* Game of Life.
*
* @version $Id$
* @copyright 2011
*/
$handle = fopen("help/logfiles.txt", "w+");
fwrite($handle, "");
fclose($handle);
$handle = fopen("help/bool.txt", "w+");
fwrite($handle, "false");
fclose($handle);

$gamefield = new Gamefield();
$simulator = new Simulator($gamefield);
$epfPManager = new epfPluginManager();
$logsaver= new LogSaver();
$validater= new Validation();

$in = "txt";
$out = "gif";
$speed=10;
$reps=10;
$x=30;
$y=20;
$form="blinker";
$bgcolor="Black";
$color="White";
$ArrayToDeliver="";


$shortopts="";
$handler = file("help/parameterlist.txt",FILE_IGNORE_NEW_LINES);
$longopts;

for($w=0;$w<count($handler);$w++)
{
	$parameter=explode(" ",$handler[$w]);
	$shortopts=$shortopts.$parameter[1];
	$longopts[$w]=$parameter[0];
}

$options = getopt($shortopts,$longopts);
$ArrayToDeliver=$options;


foreach($options as $opt => $value)
{
	if($opt=="form"||$opt=="f")
	{
		$form = $value;
	}
	if($opt=="in"||$opt=="i")
	{
		$in = $value;
	}
	if($opt=="out"||$opt=="o")
	{
		$out = $value;
	}
	if($opt=="width"||$opt=="x")
	{
		$x = $value;
	}
	if($opt=="height"||$opt=="y")
	{
		$y = $value;
	}
	if($opt=="reps"||$opt=="r")
	{
		$reps = $value;
	}
	if($opt=="debug"||$opt=="d")
	{
		$handle = fopen("help/bool.txt", "w+");
		fwrite($handle, "true");
		fclose($handle);
	}
	if($opt=="help" || $opt=="h")
	{
		echo "________________________GameOfLife__________________________________________\n";
		echo "Instead of : or :: you have to type in front of the option - or --\n\n\nOptions:\n\n";
		for($p=0;$p<count($handler);$p++)
		{
			echo $handler[$p]."\n";
		}
		$rowArray = file ("color/colors.txt");
		echo "\nPossible colors: \n________________\n";
		for($i=0;$i<count($rowArray);$i++)
		{
			$tempString = explode(" ",$rowArray[$i]);
			echo "-".$tempString[0]."\n";
		}
		echo "\nPossible forms: \n________________\n";
		foreach (glob("form/*.txt") as $filename)
		{
			$tempA = explode("/",$filename);
			$tempB = explode(".",$tempA[1]);
			echo "-".$tempB[0]."\n";
		}
		echo "____________________________________________________________________________\n";
	}
}


//Validation_process of reps, width and height
$validationIntArray= array(
	"repitations" => $reps,
	"width" => $x,
	"height" => $y
);
$cA=0;
foreach($validater->validateInt($validationIntArray) as $var => $value2)
{
	$tempArray[$cA]=$value2;
	$cA++;
}
$reps = $tempArray[0];
$x = $tempArray[1];
$y = $tempArray[2];

//Build gamefield

$gamefield->setGamefield($x,$y);

//Input Plugin load
$epfPManager->loadPluginsOnDemand("input/","input_",".plugin.php","input_");
if($epfPManager->hasPluginFor($in)==true)
{
	$input = $epfPManager->getPluginFor($in);
	if($input->setParameter($ArrayToDeliver)==true)
	{
		$input->readIntoGamefield($gamefield);
	}
}
else
{
	$logsaver->log("No input plugin called ".$in." found, please type \"php gameoflife.php --help\" for help");
}

//Output Plugin load
$epfPManager->loadPluginsOnDemand("output/","output_",".plugin.php","output_");
if($epfPManager->hasPluginFor($out)==true)
{
	$output = $epfPManager->getPluginFor($out);
	if($output->setParameters($ArrayToDeliver)==true)
	{
		$logsaver->log($reps." simulation processes will generated");
		for($i=0;$i<$reps;$i++)
		{
			$output->setCounter($i+1);
			$output->outputGamefield($gamefield);
			$simulator->simulation();
		}
		$output->finishOutput();
	}

}
else
{
	$logsaver->log("No output plugin called ".$out." found, please type \"php gameoflife.php --help\" for help");
}

//
$txtArray = file ("help/logfiles.txt");
for($e=0;$e<count($txtArray);$e++)
{
	echo $txtArray[$e];
}
$handle = fopen("help/bool.txt", "w+");
fwrite($handle, "false");
fclose($handle);
?>