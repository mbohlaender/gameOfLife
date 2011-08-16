<html>
	<head>
		<title>Game of Life</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>
<?php

	require_once ("simulator.php");
	require_once ("gamefield.php");
	require_once ("src/epf/epfpluginmanager.php");

	$epfPManager = new epfPluginManager();
	$epfPManager->loadPluginsOnDemand("output/","output_",".plugin.php","output_");
	$output = $epfPManager->getPluginFor("gif");
	$epfPManager->loadPluginsOnDemand("input/","input_",".plugin.php","input_");
	$input = $epfPManager->getPluginFor("txt");


	if(isset($_GET['selectForm'])) $selectForm = $_GET['selectForm'];
	else $selectForm = "beacon";

	if(isset($_GET['selectColor'])) $selectColor = $_GET['selectColor'];
	else $selectColor = "White";

	if(isset($_GET['selectBgColor'])) $selectBgColor = $_GET['selectBgColor'];
	else $selectBgColor = "Black";



	$x=40;
	$y=20;
	$sim=40;

	if(isset($_GET['x']))
	{
		$x = $_GET['x'];
	}
	if(isset($_GET['y']))
	{
		$y = $_GET['y'];
	}
	if(isset($_GET['sim']))
	{
		$sim = $_GET['sim'];
	}
	if(isset($_GET['ms']))
	{

		$output->setMs($_GET['ms']);

	}
	else
	{
		$output->setMs(10);
	}


?>
	<h1>Game of Life</h1>
	<form action="?" method="GET" id="form">
	Form: <select name="selectForm" size="1" class="blub">
		<?php
			foreach (glob("form/*.txt") as $filename)
			{
		  		$tempA = explode("/",$filename);
				$tempB = explode(".",$tempA[1]);
		  		?><option value="<?php echo $tempB[0] ?>" <?php if($selectForm==$tempB[0]) echo "selected" ?>><?php echo $tempB[0] ?></option><?php
			}


	  ?>
    </select><br />
    <input name="name" type="text" size="30" maxlength="30" value="Name for Gif-Animation"/><br />
    Breite x:		<input name="x" type="text" size="3" maxlength="3" value="<?php echo $x ?>" /><br />
	H&ouml;he y:	<input name="y" type="text" size="3" maxlength="3" value="<?php echo $y ?>" /><br />
	Anzahl Simulationsprozesse:<br /><input name="sim" type="text" size="3" maxlength="3" value="<?php echo $sim ?>" /><br />
	Zeit zwischen Prozessen in ms: <br /><input name="ms" type="text" size="3" maxlength="3" value="<?php echo $output->getMs() ?>" /><br />
	Farbe der lebendigen Zellen::<br />
	<select name="selectColor" size="1" class="blub">
		<?php
			$colorArray = file ("./color/colors.txt");
			for($i=0;$i<count($colorArray);$i++)
			{
				$tempArray = explode(" ",$colorArray[$i]);
				?><option value="<?php echo $tempArray[0] ?>" <?php if($selectColor==$tempArray[0]) echo "selected" ?>><?php echo $tempArray[0] ?></option><?php
			}
		?>
    </select><br />
	Hintergrundfarbe:<br />
	<select name="selectBgColor" size="1" class="blub">
		<?php
			$colorArray = file ("./color/colors.txt");
			for($i=0;$i<count($colorArray);$i++)
			{
				$tempArray = explode(" ",$colorArray[$i]);
				?><option value="<?php echo $tempArray[0] ?>" <?php if($selectBgColor==$tempArray[0]) echo "selected" ?>><?php echo $tempArray[0] ?></option><?php

			}
		?>
    </select><br />
	<input type="submit" value="send" class="submitButton"/>
    </form>
<?php

	$filename="beacon";
	$gifname;

	if($selectForm!="beacon")
	{
		$filename=$selectForm;
	}

	if(isset($_GET['name']))
	{
		if( $_GET['name']=="Name for Gif-Animation")
		{
			$gifname="Animation";
		}
		else
		{
			$gifname =  $gifname = $_GET['name'];
		}
	}
	else $gifname = "animation";


	if($selectColor!="White")
	{
		$output->setColor( $_GET['selectColor']);
	}
	if($selectBgColor!="Black")
	{
		$output->setBgColor( $_GET['selectBgColor']);
	}

	$output->setName($gifname);
	$gamefield = new Gamefield();
	$simulator = new Simulator($gamefield);
	$gamefield->setGamefield($y,$x);

	$input->setFilename($filename);
	$input->readIntoGamefield($gamefield);



	for($i=0;$i<$sim;$i++)
	{

		if($output->comparison()==true)
		{
			$output->setCounter($i+1);
			$output->outputGamefield($gamefield);
			$simulator->simulation();
		}
		else break;

	}
	$output->finishOutput();



/*	if(file_exists($gifname.".gif"))
	{
		header('Content-type: application/gif');
		header('Content-Disposition: attachment; filename="animated.gif"');
		readfile('gif/'.$gifname.'gif');
	}
*/
?>
		<div id="imagecontainer">
			<img src="gif/<?php echo $gifname ?>.gif" />
		</div>
	</body>
</html>