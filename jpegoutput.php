<?php

include_once "baseoutput.php";


/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */
class JpegOutput extends baseoutput
{


	private $name;
	private $gamefieldArray=null;

	//For picture number
	private $counter;
	private $image=null;

	//Width and height of the picture; default =400
	private $width=400;
	private $heigth=400;
	function __JpegOutput(){

	}

	function outputGameField(gameField $_gf)
	{
		$this->gamefieldArray=$_gf->gameFieldArray();
		$this->image = @ImageCreate (400, 200);
		$backgroundColor = ImageColorAllocate ($this->image, 255, 255, 255);
		$textColor = ImageColorAllocate ($this->image, 0, 0, 0);
		$schwarz = ImageColorAllocate ($this->image, 0, 0, 0);
		$weiss = ImageColorAllocate ($this->image, 255, 255, 255);

		for($i=0;$i<$_gf->rowsOfGameField();$i++)
		{
			for($j=0;$j<$_gf->columnsOfGameField();$j++)
			{
				if($_gf->checkDeadOrAlive($i,$j)=="alive")
				{
					$this->drawRectangle($i*10,$j*10 ,$i*10+10,$j*10+10 ,$schwarz);
				}
				else
				{
					$this->drawFilledRectangle($i*10,$j*10 ,$i*10+10,$j*10+10  ,$schwarz);
				}

			}
		}

		imagejpeg($this->image, "img/".$this->name.$this->counter.".jpg", 100);

	}
	function drawFilledRectangle($_x1,$_y1,$_x2,$_y2,$_color){
		imagefilledrectangle($this->image, $_x1,$_y1,$_x2,$_y2,$_color);
	}
	function drawRectangle($_x1,$_y1,$_x2,$_y2,$_color){
		imagerectangle($this->image, $_x1,$_y1,$_x2,$_y2,$_color);
	}




	function setName($_name)
	{
		$this->name=$_name;
	}
	function getName()
	{
		return $this->name;
	}
	function setWidth($_width)
	{
		$this->width=$_width;
	}
	function setHeigth($_height)
	{
		$this->height=$_height;
	}
	function Width()
	{
		return $this->width;
	}
	function Heigth()
	{
		return $this->height;
	}
	function setCounter($_counter)
	{
		$this->counter = $_counter;
	}

}

?>