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


	function setName($_name)
	{

	}

	function setParameters($_deliverString)
	{
		return true;
	}
}
?>