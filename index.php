<html>
	<head>
		<title>Game of Life</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>
<?php
	if(isset($_GET['selectForm'])) $selectForm = $_GET['selectForm'];
	else $selectForm = 5;
?>
	<h1>Game of Life</h1>
	<form action="?" method="GET" id="form">
	<select name="selectForm" size="4" class="blub">
      <option value="1" <?php if($selectForm==1) echo "selected" ?>>Blinker</option>
      <option value="2" <?php if($selectForm==2) echo "selected" ?>>Beacon</option>
      <option value="3" <?php if($selectForm==3) echo "selected" ?>>Toad</option>
      <option value="4" <?php if($selectForm==4) echo "selected" ?>>Glider</option>
      <option value="5" <?php if($selectForm==5) echo "selected" ?>>Spaceship</option>
    </select><br />
    <input name="name" type="text" size="30" maxlength="30" value="Name for Gif-Animation"/><br />
    Breite x:		<input name="x" type="text" size="3" maxlength="3" value="40" /><br />
	H&ouml;he y:	<input name="y" type="text" size="3" maxlength="3" value="20" /><br />
    <input type="submit" value="send" class="submitButton"/>
    </form>
<?php

	$filename="blinker";
	$gifname;
	$x=40;
	$y=20;
	switch ($selectForm) {
		case 1:
			$filename = "blinker";
			break;
		case 2:
			$filename = "beacon";
			break;
		case 3:
			$filename = "toad";
			break;
		case 4:
			$filename = "glider";
			break;
		case 5:
			$filename = "spaceship";
			break;
	}

	if(isset($_GET['x']))
	{
		$x = $_GET['x'];
	}

	if(isset($_GET['y']))
	{
		$y = $_GET['y'];
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
	require "simulator.php";
	require "gamefield.php";
	require "output/jpegoutput.php";
	require "output/consoleoutput.php";
	require "input/txtinput.php";
	require "output/gifoutput.php";

	$gamefield = new Gamefield();
	$simulator = new Simulator($gamefield);
	$gamefield->setGamefield($y,$x);
	$input = new TxtInput();
	$input->setFilename($filename);
	$input->readIntoGamefield($gamefield);

	$output = new GifOutput();
	$output->setName($gifname);

	for($i=0;$i<($x*2);$i++)
	{
		$output->outputGamefield($gamefield);
		$simulator->simulation();

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
			<a href="gif/<?php echo $gifname ?>.gif" />
		</div>
	</body>
</html>