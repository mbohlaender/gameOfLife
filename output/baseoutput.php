<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */
require_once("./src/epf/epfgenericplugin.php");

abstract class BaseOutput extends epfGenericPlugin
{


	function __construct()
	{

	}

	abstract function outputGamefield(gameField $_gf);

	function finishOutput()
	{
		return true;
	}

	function setCounter($_counter)
	{

	}

	function color($_color)
	{

	}
	function getColor()
	{

	}

	function bgColor($_bgColor)
	{

	}
	function getBgColor()
	{

	}

	function setName($_name)
	{

	}

	function setMs($_ms)
	{

	}
	function getMs()
	{

	}
	function setColor($_color)
	{

	}
	function setBgColor($_bgColor)
	{

	}
}
?>