<?php

include("connection.php");
include("class.paggination.php");
include("iadbanner.php");
include("model/galleries_class.php");

$CatId = isset($_GET['id']) ? $_GET['id'] : 0 ;
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0 ;

$data = new photosdata();
$photos = $data->photos($CatId,$start);

$rec_no=mysql_query($photos);
$mydb=new pagination(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$mydb->connection();
$num_records=mysql_num_rows($rec_no);
$num_rec=30;
$mydb->set_qry($photos);
$mydb->set_record_per_sheet($num_rec);
$num_pages=$mydb->num_pages();

 if (isset($_REQUEST['start'])){
	$recno=$_REQUEST['start'];
}else{
	$recno=0;
}

$rec=$mydb->execute_query($recno);
$current_page=$mydb->current_page();
$start_page=$mydb->start_page();
$end_page=$mydb->end_page();
$photoindent=$recno-1;
		
$title_res=mysql_query($photos);
$row=mysql_fetch_row($title_res);

$urlpara = '/photos/category/'.$CatId.'-'.$row[0];
$pagemeta = $data->title($urlpara);

 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="index,follow" name="robots" />
<meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type" />
<link href="pics/homescreen.gif" rel="apple-touch-icon" />
<!--<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" />-->
<meta name="viewport" content="width=280, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<link href="/components/com_shines_v2.1/css/style.css" rel="stylesheet" media="screen" type="text/css" />
<script src="javascript/functions.js" type="text/javascript"></script>
<title>
<?php
	/*Code for Page Title*/
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(stripos($ua,'android') == True) { 
		$title = $site_name.' ~ '.JText::_('TW_GALLERY').' ~ '.$row['0'];
		if($pagemeta['title']!=''){
			$title .= ' ~ '.$pagemeta['title'];
		}
		echo $title;
	}else{
		$title = $site_name.' : '.JText::_('TW_GALLERY').' : '.$row['0'];
		if($pagemeta['title']!=''){
			$title .= ' : '.$pagemeta['title'];
		}
		echo $title;
	}
		

		
?>
</title>
<!--<link href="pics/startup.png" rel="apple-touch-startup-image" /> -->

<meta content="destin, vacactions in destin florida, destin, florida, real estate, sandestin resort, beaches, destin fl, maps of florida, hotels, hotels in florida, destin fishing, destin hotels, best florida beaches, florida beach house rentals, destin vacation rentals for destin, destin real estate, best beaches in florida, condo rentals in destin, vacaction rentals, fort walton beach, destin fishing, fl hotels, destin restaurants, florida beach hotels, hotels in destin, beaches in florida, destin, destin fl" name="keywords" />
<meta content="Destin Florida's FREE iPhone application and website guide to local events, live music, restaurants and attractions" name="description" />

<?php include("../../ga.php"); ?>

</head>
<body>

<?php
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);

if(stripos($ua,'android') == true) { ?>
	<div class="iphoneads" style=" vertical-align:top">
		<?php m_show_banner('android-photos-screen'); ?>
	</div>
	<?php } 
	else {?>
	<div class="iphoneads" style=" vertical-align:top">
		<?php m_show_banner('iphone-photos-screen'); ?>
	</div>
<?php } ?>

<?php
	/* Code added for iphone_photos.tpl */
	require($_SERVER['DOCUMENT_ROOT']."/partner/".$_SESSION['tpl_folder_name']."/tpl_v2.1/iphone_photos.tpl");
?>

</body>
</html>

