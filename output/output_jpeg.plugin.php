<?php

include_once "baseoutput.php";
include_once "./help/logsaver.php";

/**
 * Build jpgs of every simulation step
 *
 * @version $Id$
 * @copyright 2011
 */
class output_jpeg extends baseoutput
{

	private $name;
	private $gamefieldArray=null;

	//LogSaver
	private $logsaver;

	//For picture number
	private $counter=1;
	private $image=null;

	//Width and height of the picture; default =400
	private $width=400;
	private $heigth=400;
	private $color;
	private $bgColor;

	function __construct()
	{
		$this->weProvide="jpeg";
		$this->color[0]="Black";
		$this->bgColor[1]=0;
		$this->bgColor[2]=0;
		$this->bgColor[3]=0;
		$this->color[0]="White";
		$this->color[1]=255;
		$this->color[2]=255;
		$this->color[3]=255;
		$this->logsaver= new LogSaver();
		$this->logsaver->log("Jpeg-output-plugin loaded\n");
	}




	/**
	 * Build jpgs of every simulation step
	 *
	 * @param gameField $_gf
	 * @return
	 */
	function outputGameField(gameField $_gf)
	{
		$this->gamefieldArray=$_gf->gameFieldArray();
		$this->image = @ImageCreate ($_gf->rowsOfGameField()*10,$_gf->columnsOfGameField()*10);

		$bgColor = ImageColorAllocate ($this->image,(int)$this->bgColor[1], (int)$this->bgColor[2], (int)$this->bgColor[3]);
		$color = ImageColorAllocate ($this->image, (int)$this->color[1], (int)$this->color[2], (int)$this->color[3]);
		for($i=0;$i<$_gf->rowsOfGameField();$i++)
		{

			for($j=0;$j<$_gf->columnsOfGameField();$j++)
			{

				if($_gf->checkDeadOrAlive($i,$j)=="alive")
				{
					$this->drawFilledRectangle($j*10+1,$i*10+1,$j*10+9,$i*10+9 ,$color);
				}
			}
		}

		imagejpeg($this->image, "img/".$this->name.$this->counter.".jpg", 100);

	}

	/**
	 * Draw a filled rectangle
	 *
	 * @param mixed $_x1
	 * @param mixed $_y1
	 * @param mixed $_x2
	 * @param mixed $_y2
	 * @param mixed $_color
	 * @return
	 */
	function drawFilledRectangle($_x1,$_y1,$_x2,$_y2,$_color)
	{
		imagefilledrectangle($this->image, $_x1,$_y1,$_x2,$_y2,$_color);
	}

	/**
	 * Set the names for jpg files
	 *
	 * @param mixed $_name
	 * @return
	 */
	function setName($_name)
	{
		$this->name=$_name;
	}

	/**
	 * Returns the filename
	 *
	 * @return String name
	 */
	function getName()
	{
		return $this->name;
	}

	/**
	 * Counter for picture names
	 *
	 * @param mixed $_counter
	 * @return
	 */
	function setCounter($_counter)
	{
		$this->counter = $_counter;
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
	function setParameters($_ArrayToCheck)
	{
			foreach($_ArrayToCheck as $opt => $value)
			{
				if($opt=="bgcolor"||$opt=="b")
				{
					if($this->setBgColor($value)==true)
					{
						$this->logsaver->log($value." was set as color for dead cells.");
					}
					else
					{
						$this->logsaver->log($value." is no valid color");
						return false;
					}
				}
				if($opt=="color"||$opt=="c")
				{
					if($this->setColor($value)==true)
					{
						$this->logsaver->log($value." was set as color for living cells.");
					}
					else
					{
						$this->logsaver->log($value." is no valid color");
						return false;
					}
				}
			}
			return true;
	}

}


?>