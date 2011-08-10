<?php

include_once "baseoutput.php";


/**
 * Build jpgs of every simulation step
 *
 * @version $Id$
 * @copyright 2011
 */
class JpegOutput extends baseoutput
{


	private $name;
	private $gamefieldArray=null;

	//For picture number
	private $counter=1;
	private $image=null;

	//Width and height of the picture; default =400
	private $width=400;
	private $heigth=400;

	/**
	 * Build jpgs of every simulation step
	 *
	 * @param gameField $_gf
	 * @return
	 */
	function outputGameField(gameField $_gf)
	{
		echo "Test\n";
		$this->gamefieldArray=$_gf->gameFieldArray();
		$this->image = @ImageCreate ($_gf->rowsOfGameField()*10,$_gf->columnsOfGameField()*10);
		$backgroundColor = ImageColorAllocate ($this->image, 0, 0, 0);
		$black = ImageColorAllocate ($this->image, 0, 0, 0);
		$white = ImageColorAllocate ($this->image, 255, 255, 255);
		for($i=0;$i<$_gf->rowsOfGameField();$i++)
		{

			for($j=0;$j<$_gf->columnsOfGameField();$j++)
			{

				if($_gf->checkDeadOrAlive($i,$j)=="alive")
				{
					$this->drawFilledRectangle($j*10+1,$i*10+1,$j*10+9,$i*10+9 ,$white);
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

}

?>