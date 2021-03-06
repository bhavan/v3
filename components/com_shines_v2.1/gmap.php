<?php
/**
* @copyright	Copyright (C) 2008 GWE Systems Ltd. All rights reserved.
 * @license		By negoriation with author via http://www.gwesystems.com
*/
ini_set("display_errors",0);

list($usec, $sec) = explode(" ", microtime());
define('_SC_START', ((float)$usec + (float)$sec));

// Set flag that this is a parent file
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$x = realpath(dirname(__FILE__)."/../../") ;
// SVN version
if (!file_exists($x.DS.'includes'.DS.'defines.php')){
	$x = realpath(dirname(__FILE__)."/../../../") ;

}
define( 'JPATH_BASE', $x );

ini_set("display_errors",0);

require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
require_once JPATH_BASE.DS.'includes'.DS.'framework.php';

global $mainframe;
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();

// use the default layout for the iphone app
setcookie("jevents_view","default",null,"/");
JRequest::setVar("iphoneapp",1);

$script = $_SERVER['REQUEST_URI'];
$urlparts = parse_url($_SERVER['REQUEST_URI']);

$parts = pathinfo($urlparts["path"]);
$action = "gmap1";

/**
		 * This is the google maps page
		 * 
	 * Arguments are:
	 * lat = latitude
	 * long = longitude
	 * 
	 */
$params = JComponentHelper::getParams("com_jevlocations");
header('Content-type: text/html; charset=utf-8', true);
$map = file_get_contents(JPATH_SITE."/components/com_shines/ipodhtml/$action.html");
$map = str_replace("GOOGLEMAPSKEY",$params->get("googlemapskey",""),$map);
$map = str_replace("{LAT}",JRequest::getFloat("lat",0),$map);
$map = str_replace("{LON}",JRequest::getFloat("long",0),$map);

echo $map;
