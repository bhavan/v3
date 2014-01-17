<?php

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$x = realpath(dirname(__FILE__)."/../../") ;
// SVN version
if (!file_exists($x.DS.'includes'.DS.'defines.php')){
	$x = realpath(dirname(__FILE__)."/../../../") ;
}
define( 'JPATH_BASE', $x );
@ini_set("display_errors",0);
require_once JPATH_BASE.DS.'includes'.DS.'defines.php';
require_once JPATH_BASE.DS.'includes'.DS.'framework.php';
include($_SERVER['DOCUMENT_ROOT']."/pagination.php");
//require_once($_SERVER['DOCUMENT_ROOT']."/configuration.php");
include("connection.php");
include("iadbanner.php");
//$jconfig = new JConfig();
//$link = @mysql_pconnect($jconfig->host,  $jconfig->user, $jconfig->password);
//mysql_select_db($jconfig->db);
$rec01 = mysql_query("select * from `jos_pageglobal`");
mysql_set_charset("UTF8");
$pageglobal=mysql_fetch_array($rec01);

function showBrief($str, $length) {
	$str = strip_tags($str);
	$str = explode(" ", $str);
	return implode(" " , array_slice($str, 0, $length));
}

function stripJunk($string) { 
	$cleanedString = preg_replace("/[^A-Za-z0-9\s\.\-\/+\!;\n\t\r\(\)\'\"._\?>,~\*<}{\[\]\=\&\@\#\$\%\^` ]:/","", $string); 
	$cleanedString = preg_replace("/\s+/"," ",$cleanedString); 
	return $cleanedString; 
}

function distance($lat1, $lon1, $lat2, $lon2, $unit) { 
	$theta = $lon1 - $lon2; 
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist); 
	$dist = rad2deg($dist); 
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);
	if($unit == "KM") {
		return ($miles * 1.609344); 
		}else if($unit == "N"){
		return ($miles * 0.8684);
		}else{
		return $miles;
	}
}

if (isset($_REQUEST['lat']) && $_REQUEST['lat'] != '')
	$lat1=$_REQUEST['lat'];
else
	$lat1=0;

if (isset($_REQUEST['lon']) && $_REQUEST['lon'] != '')
	$lon1=$_REQUEST['lon'];
else
	$lon1=0;

if (isset($_REQUEST['filter_loccat']) && $_REQUEST['filter_loccat'] != '0')
	
$filter_loccat=$_REQUEST['filter_loccat'];

if(isset($filter_loccat) && $filter_loccat == 'Featured')
	$customfields3_table = ", `jos_jev_customfields3` ";
else
$customfields3_table = "";
if (isset($_REQUEST['start']))
	$ii=$_REQUEST['start'];
else
$ii=0;
if(isset($_REQUEST['filter_order']) && $_REQUEST['filter_order']!="")
	$filter_order = $_REQUEST['filter_order'];
else    
$filter_order = "";	
if(isset($_REQUEST['filter_order_Dir']) && $_REQUEST['filter_order_Dir']!="")
	$filter_order_Dir = $_REQUEST['filter_order_Dir'];
else    
$filter_order_Dir = "ASC";

#@#
$RES=mysql_query("select id from jos_categories where parent_id=152 AND section='com_jevlocations2' and published=1 order by `ordering`");mysql_set_charset("UTF8");
while($idsrow=mysql_fetch_assoc($RES)){
	$allCatIds[] = $idsrow['id'];
}
$allCatIds[] = 152;
#@#

$path= $_SERVER['PHP_SELF'] . "?option=com_jevlocations&task=locations.listlocations&tmpl=component&needdistance=1&sortdistance=1&lat=".$lat1."&lon=".$lon1."&bIPhone=". isset($_REQUEST['bIPhone'])."&iphoneapp=1&search=". isset($_REQUEST['search'])."&limit=0&jlpriority_fv=0&filter_loccat=".isset($filter_loccat)."&filter_order=".$filter_order."&filter_order_Dir=".$filter_order_Dir;

if ((isset($_REQUEST['search']) && $_REQUEST['search'] != '') || (isset($_REQUEST['Buscar']) && $_REQUEST['Buscar'] != '') || (isset($_REQUEST['Traži']) && $_REQUEST['Traži']!='') || (isset($_REQUEST['Pesquisar']) && $_REQUEST['Pesquisar'] != '') || (isset($_REQUEST['Zoeken']) && $_REQUEST['Zoeken'] != '') || (isset($_REQUEST['Recherche']) && $_REQUEST['Recherche'] != ''))
	$subquery="  and title like '%".$_REQUEST['search']."%' or description like '%".$_REQUEST['search']."%'";

$query1 = "SELECT *,(((acos(sin(($lat1 * pi() / 180)) * sin((geolat * pi() / 180)) + cos(($lat1 * pi() / 180)) * cos((geolat * pi() / 180)) * cos((($lon1 - geolon) * pi() / 180)))) * 180 / pi()) * 60 * 1.1515) as dist FROM jos_jev_locations $customfields3_table WHERE loccat IN (".implode(',',$allCatIds).") AND published=1 ".isset($subquery);

//and loccat=".$filter_loccat
if(isset($filter_loccat) && $filter_loccat == 'Featured')
	$query .= " AND (jos_jev_locations.loc_id = jos_jev_customfields3.target_id AND jos_jev_customfields3.value = 1 ) ";
elseif(isset($filter_loccat) && $filter_loccat!=0 && $_REQUEST['filter_loccat']!='alp')
	$query .= " AND loccat = $filter_loccat ";

if(($filter_order != "") || (isset($_REQUEST['filter_loccat']) && $_REQUEST['filter_loccat']=='alp'))
	$query1 .= " ORDER BY title ASC ";
else
$query1 .= " ORDER BY dist ASC";

$rec1 = mysql_query($query1) or die(mysql_error());
mysql_set_charset("UTF8");
$total_data=mysql_num_rows($rec1);
$total_rows=$total_data;
$page_limit=50;
$entries_per_page=$page_limit;
$current_page=(empty($_REQUEST['page']))? 1:$_REQUEST['page'];
$start_at=($current_page * $entries_per_page)-$entries_per_page;
$link_to=$path;

$query_featured = "SELECT *,(((acos(sin(($lat1 * pi() / 180)) * sin((geolat * pi() / 180)) + cos(($lat1 * pi() / 180)) * cos((geolat * pi() / 180)) * cos((($lon1 - geolon) * pi() / 180)))) * 180 / pi()) * 60 * 1.1515) as dist FROM jos_jev_locations, jos_jev_customfields3 WHERE loccat IN (".implode(',',$allCatIds).") AND published=1 ";
$query_featured .= " AND (jos_jev_locations.loc_id = jos_jev_customfields3.target_id AND jos_jev_customfields3.value = 1 ) ";
$query_featured .= " ORDER BY dist ASC";

/* CODE ADDED BY AKASH FOR SLIDER */

$query_location="SELECT loc.description,loc.alias,loc.loc_id,loc.title,loc.image, cate.title as category, cf.value FROM  jos_jev_locations as loc, jos_categories as cate, jos_jev_customfields3 as cf WHERE loc.loccat = cate.id AND cate.parent_id = 152 AND loc.published = 1 AND loc.loc_id = cf.target_id AND cf.value = 1 ORDER BY `cate`.`title` ASC";

$featured_loc=mysql_query($query_location);
mysql_set_charset("UTF8");

/*CODE END AKASH FOR SLIDER*/

/* code start by rinkal for page title */
$pagemeta_res = mysql_query("select title from `jos_pagemeta`where uri='/places'");
$pagemeta =mysql_fetch_array($pagemeta_res);
/* code end by rinkal for page title */

header( 'Content-Type:text/html;charset=utf-8');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- Conditional comment for mobile ie7 blogs.msdn.com/b/iemobile/ -->
<!--[if IEMobile 7 ]>    <html class="no-js iem7" lang="en"> <![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=280, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="index,follow" name="robots" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<title>
<?php 
	/* code start by rinkal for page title */
	
	if ($_SESSION['tpl_folder_name'] == 'defaultspanish' || $_SESSION['tpl_folder_name'] == 'defaultportuguese'){
		$t = 'Restaurantes';
	}elseif($_SESSION['tpl_folder_name'] == 'defaultdutch'){
		$t = 'Restaurants';
	}elseif($_SESSION['tpl_folder_name'] == 'defaultcroatian'){
		$t = 'Restorani';
	}elseif($_SESSION['tpl_folder_name'] == 'default' || $_SESSION['tpl_folder_name'] == 'defaultfrench'){
		$t = 'Restaurants';
	}

	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(stripos($ua,'android') == True) { 
		$title = $site_name.' ~ '.$t;
		if($pagemeta['title']!='')
		{
			$title.= ' ~ '.$pagemeta['title'];
		}
		echo $title;
	}
	else{
		$title = $site_name.' : '.$t;
		if($pagemeta['title']!='')
		{
			$title .= ' : '.$pagemeta['title'];
		}
		echo $title;
	}
	/* code end by rinkal for page title */
?>
</title>

<link href="pics/homescreen.gif" rel="apple-touch-icon" />
<link href="/components/com_shines_v2.1/css/style.css" rel="stylesheet" media="screen" type="text/css" />
 <link rel="shortcut icon" href="images/l/apple-touch-icon.png">
<link href="pics/startup.png" rel="apple-touch-startup-image" />
<script type="text/javascript">
var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}
</script>
<script type="text/javascript">
function linkClicked(link) { document.location = link; }
ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
	})
ddsmoothmenu.init({
	mainmenuid: "smoothmenu2", //Menu DIV id
	orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
	//customtheme: ["#804000", "#482400"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
	})
	function redirecturl(val)
{
	
	url="<?php echo $_SERVER['PHP_SELF']; ?>?option=com_jevlocations&task=locations.listlocations&tmpl=component&needdistance=1&sortdistance=1&lat=<?php echo isset($_REQUEST['lat'])?>&lon=<?php echo isset($_REQUEST['lon'])?>&bIPhone=<?php echo isset($_REQUEST['bIPhone'])?>&iphoneapp=1&search=<?php echo $_REQUEST['search']?>&limit=0&jlpriority_fv=0&filter_loccat="+val + "&filter_order=<?php echo $filter_order?>&filter_order_Dir=<?php echo $filter_order_Dir?>";
	window.location=url;
}

function divopen(str) {
	if(document.getElementById(str).style.display=="none") {
		document.getElementById(str).style.display="block";
		} else {
		document.getElementById(str).style.display="none";
	}
}
</script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="javascript/libs/jquery-1.7.1.min.js"><\/script>')</script>

<!-- scripts for sliders -->
	<script type="text/javascript" src="javascript/sliders.js"></script>
	<script type="text/javascript">
		$(window).load(function() {
			$('.flexslider').flexslider({
			  animation: "slide",
			  directionNav: false,
			  controlsContainer: ".flexslider-container"
		  });
		});
	</script>
	<script>
		$(document).ready( function() {
			$("#searchIcon").click(function () {
				$("#searchForm").slideToggle("slow");
			});
		});
	</script>
	<script src="javascript/helper.js"></script>
<?php include($_SERVER['DOCUMENT_ROOT']."/ga.php"); ?>
</head>
<body>

<?php
/* Code added for iphone_restaurants.tpl */
require($_SERVER['DOCUMENT_ROOT']."/partner/".$_SESSION['tpl_folder_name']."/tpl_v2.1/iphone_restaurants.tpl");
?>
</body>
</html>