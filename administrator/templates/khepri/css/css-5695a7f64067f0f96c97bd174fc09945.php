<?php 
ob_start ("ob_gzhandler");
header("Content-type: text/css; charset: UTF-8");
header("Cache-Control: must-revalidate");
$offset = 60 * 60 ;
$ExpStr = "Expires: " . 
gmdate("D, d M Y H:i:s",
time() + $offset) . " GMT";
header($ExpStr);
                ?>

/*** template.css ***/

/**
* @version $Id: template.css 10387 2008-06-03 10:59:16Z pasamio $
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Joomla! 1.5 Admin template main css file
 *
 * @author		Andy Miller <andy.miller@joomla.org>
 * @package		Joomla
 * @since		1.5
 * @version  	1.0
 */

/* -- Imported styles ----------------------------- */

@import url("general.css");
@import url("icon.css");
@import url("menu.css");
@import url("component.css");

