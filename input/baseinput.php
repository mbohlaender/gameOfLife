<?php
/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

abstract class BaseInput
{

	abstract function setFilename($_filename);
	abstract function readIntoGamefield(GameField $_gf);

}

?>