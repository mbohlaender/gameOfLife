<?php

require_once "baseoutput.php";
require_once "/gifCreator/dGifAnimator.inc.php";
/**
 * Build animated gif files of the simulation process
 *
 * @version $Id$
 * @copyright 2011
 */
class output_gif extends BaseOutput
{

	private $generated;
	private $gif;
	private $name ="Test";
	private $ms;
	private $counter=0;
	private $md5Array;
	private $bool=true;
	private $lastMd5;
	private $color;
	private $bgColor;

	function __construct()
	{
		$this->weProvide="gif";
		$this->color[0]="Black";
		$this->bgColor[1]=0;
		$this->bgColor[2]=0;
		$this->bgColor[3]=0;
		$this->color[0]="White";
		$this->color[1]=255;
		$this->color[2]=255;
		$this->color[3]=255;

	}

	public function setMs($_ms)
	{
		if(is_numeric($_ms))
		{
			$this->ms=(int)$_ms;
			return true;
		}
		else
		{
			return false;
		}
	}
	public function getMs()
	{
		return $this->ms;
	}
	public function comparison()
	{
		return $this->bool;
	}
	/**
	 * Build frames of every simulation step
	 *
	 * @param gameField $_gf
	 * @return
	 */
	function setCounter($_counter)
	{
		$this->counter = $_counter;
	}

	function outputGamefield(gameField $_gf)
	{
		if(!is_dir("img"))
		{
			mkdir("img");
		}
		$im   = imageCreateTrueColor ($_gf->columnsOfGameField()*10,$_gf->rowsOfGameField()*10);
		//Colors initializing

		$bgcolor = imagecolorallocate($im,(int)$this->bgColor[1],(int)$this->bgColor[2],(int)$this->bgColor[3]);
		$color = imagecolorallocate($im,(int)$this->color[1],(int)$this->color[2],(int)$this->color[3]);

		imagefilledrectangle ($im, 0, 0, $_gf->columnsOfGameField()*10,$_gf->rowsOfGameField()*10, $bgcolor);
		for($i=0;$i<$_gf->rowsOfGameField();$i++)
		{

			for($j=0;$j<$_gf->columnsOfGameField();$j++)
			{

				if($_gf->checkDeadOrAlive($i,$j)=="alive")
				{
					imagefilledrectangle($im,$j*10+1,$i*10+1 ,$j*10+9,$i*10+9 ,$color);
				}

			}
		}
		$randFilename = "img/frame".$this->counter.".jpg";
		$this->generated[] = $randFilename;
		imagegif($im, $randFilename);

		$md5= md5_file ( $randFilename );
		if($this->lastMd5!="")
		{
			if($md5==$this->lastMd5)
			{
				$this->bool=false;
			}
		}
		$this->lastMd5=$md5;


	}

	/**
	 * Build a gif file out of the frames
	 *
	 * @return
	 */
	function finishOutput()
	{

		$this->gif = new dGifAnimator;
		$this->gif->setLoop(0);                         # Loop forever
		$this->gif->setDefaultConfig('delay_ms', (int)$this->getMs()); # Delay: 10ms
		if(isset($_GET['transparent']))
			$this->gif->setDefaultConfig('transparent_color', 0);

		/** Adds all the frames to the animation **/
		for($x = 0; $x < sizeof($this->generated); $x++)
		{
			$this->gif->addFile($this->generated[$x]);
		}
		$this->gif->build("gif/".$this->name.".gif");

		/** Exclude all frames used to build the final animation **/
		for($x = 0; $x < sizeof($this->generated); $x++)
		{
			unlink($this->generated[$x]);
		}

	}
	/**
	 * Set the name of the gif file
	 *
	 * @param mixed $_name
	 * @return
	 */
	function setName($_name)
	{
		$this->name=$_name;
	}

	function setColor($_color)
	{
		$colorArray = file ("./color/colors.txt");
		for($i=0;$i<count($colorArray);$i++)
		{
			$tempArray = explode(" ",$colorArray[$i]);
			if($tempArray[0]==$_color)
			{
				$tempString = $colorArray[$i];
				$this->color =explode(" ",$tempString);
				return true;
			}

		}
		return false;
	}
	function getColor()
	{
		return $this->color;
	}
	function setBgColor($_bgColor)
	{
		$colorArray = file ("./color/colors.txt");
		for($i=0;$i<count($colorArray);$i++)
		{
			$tempArray = explode(" ",$colorArray[$i]);
			if($tempArray[0]==$_bgColor)
			{
				$tempString = $colorArray[$i];
				$this->bgColor =explode(" ",$tempString);
				return true;
			}

		}
		return false;
	}
	function getBgColor()
	{
		return $this->bgColor;
	}
}
?>