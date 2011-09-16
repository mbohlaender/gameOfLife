<?php
require_once("./src/epf/epfgenericplugin.php");

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

abstract class BaseInput extends epfGenericPlugin
{

	abstract function setFilename($_filename);
	abstract function readIntoGamefield(GameField $_gf);

	function setParameter($_ArrayToCheck)
	{

	}
}

?>