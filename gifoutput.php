<?php

include_once "baseoutput.php";
require_once "/gifCreator/dGifAnimator.inc.php";
/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */
class GifOutput extends baseoutput
{

	private $generated;
	private $gif;
	private $name ="Test";

	function outputGamefield(gameField $_gf)
	{
		if(!is_dir("img"))
		{
			mkdir("img");
		}
		$im   = imageCreateTrueColor (400, 200);

		$black = imagecolorallocate($im,   0,   0,   0);
		$white = imagecolorallocate($im, 255, 255, 255);
		$red   = imagecolorallocate($im, 255,   0,   0);
		$green = imagecolorallocate($im,   0, 128,   0);

		imagefilledrectangle ($im, 0, 0, 400, 200, IMG_COLOR_TILED);
		for($i=0;$i<$_gf->rowsOfGameField();$i++)
		{

			for($j=0;$j<$_gf->columnsOfGameField();$j++)
			{

				if($_gf->checkDeadOrAlive($i,$j)=="alive")
				{
					imagefilledrectangle($im,$i*10,$j*10 ,$i*10+10,$j*10+10 ,$white);
				}

			}
		}
		$randFilename = "img/".uniqid().".gif";
		$this->generated[] = $randFilename;
		imagegif($im, $randFilename);
	}
	function finishOutput()
	{

		$this->gif = new dGifAnimator;
		$genEndTime = microtime(true);


		$startTime = microtime(true);

		$this->gif->setLoop(0);                         # Loop forever
		$this->gif->setDefaultConfig('delay_ms', '40'); # Delay: 10ms
		if(isset($_GET['transparent']))
			$this->gif->setDefaultConfig('transparent_color', 0);

		/** Adds all the frames to the animation **/
		for($x = 0; $x < sizeof($this->generated); $x++){
			$this->gif->addFile($this->generated[$x]);
		}
		$this->gif->build("gif/".$this->name.".gif");
		$endTime = microtime(true);

		/** Exclude all frames used to build the final animation **/
		for($x = 0; $x < sizeof($this->generated); $x++)
		{
			unlink($this->generated[$x]);
		}

	}
	function setName($_name)
	{
		$this->name=$_name;
	}
}
?>