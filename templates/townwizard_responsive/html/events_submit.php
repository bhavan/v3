<?php
//define( '_JEXEC', 1 );
//define('JPATH_BASE', dirname(__FILE__) );
//define( 'DS', DIRECTORY_SEPARATOR );

include(JPATH_BASE .DS.'formValidation.php');
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php');
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'utilities'.DS.'utility.php');
require_once(JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table.php');
require(JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'database'.DS.'table'.DS.'category.php');
require_once ( JPATH_BASE .DS.'components'.DS.'com_jevents'.DS.'libraries'.DS.'helper.php');
require_once ( JPATH_BASE .DS.'components'.DS.'com_jevents'.DS.'libraries'.DS.'commonfunctions.php');
require_once ( JPATH_BASE .DS.'administrator'.DS.'components'.DS.'com_jevents'.DS.'libraries'.DS.'categoryClass.php');
require_once(JPATH_BASE .DS."configuration.php");

// move global var here for v2
global $var;
include_once(JPATH_BASE .DS.'/inc/var.php');
include_once(JPATH_BASE .DS.'/inc/base.php');

_init();
// end v2 code
$jconfig = new JConfig();
define('DB_HOST', $jconfig->host);
define('DB_USER',$jconfig->user);
define('DB_PASSWORD',$jconfig->password);
define('DB_NAME',$jconfig->db);
$conn=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD) or die(mysql_error());
$db=mysql_select_db(DB_NAME) or die(mysql_error());


// Query for default ics record.
$ics_query=mysql_query("select * from jos_jevents_icsfile where isdefault='1' and state='1'");
$ics_res=mysql_fetch_array($ics_query);
$ics=$ics_res['ics_id'];
global $msg;
$msg="";

if (isset($_POST['action']))
if($_POST['action']=='Save' || $_POST['action']=='Guardar' || $_POST['action']=='Spremi' || $_POST['action']=='Bewaar' || $_POST['action']=='Salve' ||  $_POST['action']=='Sauvegarder'){
	
	$postRecheck = checkPostParameter($_POST);
	
	if($postRecheck){
	
		$title = addslashes($_POST['title']);
		$allDayEvent=$_POST['allDayEvent'];
		$custom_field4=$_POST['custom_field4'];
		$publish_up=$_POST['publish_up'];
		$publish_down=$_POST['publish_down'];
		
			if($_POST['allDayEvent']=='on') {
				$datem=$publish_up." ".'00:00:00';
				$datee=$publish_down." ".'23:59:59';
				$start_12h=strtotime($publish_up.'00:00:00');
				$end_12h=strtotime($publish_down.'23:59:59');
				$noend=0;
		
			} else if($_POST['noendtime']!='') {
				$start_12h=strtotime($_POST['publish_up'].$_POST['start_12h'].$_POST['start_ampm']);
				$end_12h=strtotime($_POST['publish_down'].$_POST['start_12h'].$_POST['start_ampm']);
				$datem=$publish_up." ".date("H:i:s",$start_12h);
				$datee=$publish_down." ".'23:59:59';
				$noend=1;
			
			} else {
				$start_12h=strtotime($_POST['publish_up'].$_POST['start_12h'].$_POST['start_ampm']);
				$end_12h=strtotime($_POST['publish_down'].$_POST['end_12h'].$_POST['end_ampm']);
				$datem=$publish_up." ".date("H:i:s",$start_12h);
				$datee=$publish_down." ".date("H:i:s",$end_12h);
				$noend=0;
			}
		$day = date('l',strtotime($publish_up));
		$weekday=strtoupper(substr($day,0,2));
		$cat_id=$_POST['catid'];
		
		$ics_id=$_POST['ics_id'];
		$jevcontent=addslashes($_POST['jevcontent']);
		$location=$_POST['location'];
		$custom_anonusername=$_POST['custom_anonusername'];
		$custom_anonemail=$_POST['custom_anonemail'];
		$uid=$_SESSION['__default']['user']->id;
		
		$userid=md5(uniqid(rand(),true));
		$duplicate_value=md5(uniqid(rand(),true));
		$data=array(dtstart =>$start_12h,
			    UID=>$userid,
			    dtend=>$end_12h,
			    description=>$jevcontent,
			    allDayEvent=>$allDayEvent,
			    publish_down=>$publish_down,
			    location=>$location,
			    publish_up=>$publish_up,
			    summary=>$title,
			    createdby=>$uid,
			    Customfield=>$custom_field4,
			    multiday=>1,
			    lockevent=>0,
			    FREQ=>None,
			    Category=>$cat_id,
			    Username=>$custom_anonusername,
			    UserEmail=>$custom_anonemail,
			    noendtime=>0);
		$rawdata=serialize($data);
		
		// Data insertion in event detail table  // 
		$ins_query=mysql_query("insert into jos_jevents_vevdetail(dtstart,duration,dtend,description,geolon,geolat,location,priority,summary,sequence,state,multiday,hits,noendtime,modified) values('".$start_12h."','0','".$end_12h."','".$jevcontent."','0','0','".$location."','0','".$title."','0','".$custom_field4."','1','0','".$noend."',now())");
		
		// Query for last record id of detail table
		$last_id_query=mysql_query("SELECT LAST_INSERT_ID() as last_id");
		$result_lastid=mysql_fetch_array($last_id_query);
		$last_id=$result_lastid['last_id'];
		
		
		// Data insertion in event table
		$ins_query1=mysql_query("insert into jos_jevents_vevent(icsid,catid,uid,created,created_by,rawdata,detail_id,state,access,lockevent,author_notified) values('".$ics_id."','".$cat_id."','".$userid."',now(),'".$uid."','".$rawdata."','".$last_id."','".$custom_field4."','0','0','0')");
		
		
		// Query for last record id of event table
		$last_id_query1=mysql_query("SELECT LAST_INSERT_ID() as last_id1");
		$result_lastid1=mysql_fetch_array($last_id_query1);
		$last_id1=$result_lastid1['last_id1'];
		
		//#DD#
		if($last_id1>0){
			$ins_query0=mysql_query("insert into jos_jev_anoncreator(ev_id ,name,email) values('".$last_id1."','".$custom_anonusername."','".$custom_anonemail."')"); 
		}
		//#DD#
		
		// Data insertion in event rrule table 
		$ins_query2=mysql_query("insert into jos_jevents_rrule(eventid,freq,until,count,rinterval,byday) values('".$last_id1."','none','0','1','1','".$weekday."')");
		
		// Data insertion in event repetition table 
		$ins_query3=mysql_query("insert into jos_jevents_repetition(eventid,eventdetail_id,duplicatecheck,startrepeat,endrepeat) values('".$last_id1."','".$last_id."','".$duplicate_value."','".$datem."','".$datee."')");
		
		
		$db =& JFactory::getDBO();
		$vevent->icsid = $ics_id;
		$abc=JLoader::register('JEventsCategory',JEV_ADMINPATH."/libraries/categoryClass.php");
		$cat = new JEventsCategory($db);
		$cat->load($vevent->catid);
		
			if(!empty($last_id) && (!empty($last_id1))) {
				//require_once($var->tpl_path."events_submit_mail.tpl");
				$msg=JText::_('THANKS_MSG');
				$subject= 'New Event Submission';
				//$adminuser = $cat->getAdminUser();
				//$adminEmail	= $adminuser->email;
				$rec		= mysql_query("select * from `jos_pageglobal`");
				$pageglobal	= mysql_fetch_array($rec);
				$adminEmail = $pageglobal['email'];				

				$sitename =  $jconfig->sitename;
				
				$message = '
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
				<td colspan="2" align="left">Title : '.$title.'</td>
				
				</tr>
				<tr><td style="padding:15px">&nbsp;</td></tr>
				<tr>
				<td colspan="2">'.$jevcontent.'</td>
				</tr>
				<tr>
				<td align="left" colspan="2" style="padding-top:25px">Event Submitted from
				['.$sitename.'] by ['.$custom_anonusername.'('.$custom_anonemail.')]</td>
				</tr>
				</table>';
				
				$ack_message = '
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
				<td colspan="2" align="left">Title : '.$title.'</td>
				
				</tr>
				<tr><td style="padding:15px">&nbsp;</td></tr>
				<tr>
				<td colspan="2">'.$msg.'</td>
				</tr>
				</table>';
				$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type:text/html;charset=iso-8859-1' . "\r\n";
				//$headers .= 'From: NO-REPLY <admin@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
				$headers .= 'From: TownWizard<no-reply@partneremail.townwizard.com>' . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
				// Email Notification to Administrator
				
				# New file added by Yogi for Email functioanlity as per AMAZONE
				# Date: 25 May 2016

				include_once(JPATH_BASE .DS.'twmailer.php');
				# Send Email to Admin
				
				// # code for send email to multiple user/email
				$allEmail = explode(',',$adminEmail);
				foreach($allEmail as $to_email){
					$mail_status_admin = sendTwMail($to_email,$subject,$message,'no-reply@townwizard.com');
				}
				
				# Send Email to event submitter
				$mail_status_user = sendTwMail($custom_anonemail,$subject,$ack_message,'no-reply@townwizard.com');
				
				//send the message, check for errors
				if(isset($mail_status_admin) && ($mail_status_admin == 'success')){
				    $mailMessage = JText::_('THANKS_MSG');
				} else {
				    $mailMessage = "Sorry! Failed to submit your event";
				}

				//mail($adminEmail,$subject,$message,$headers);
				
				// Acknowledgement Email to the Event Creator. 
				//mail($custom_anonemail,$subject,$ack_message,$headers);
				
				$_SESSION['title']="";
				$_SESSION['catid']="";
				$_SESSION['ics_id']="";
				$_SESSION['view12Hour']="";
				$_SESSION['publish_up']="";
				$_SESSION['start_12h']="";
				$_SESSION['publish_down']="";
				$_SESSION['end_12h']="";
				$_SESSION['noendtime']="";
				$_SESSION['allDayEvent']="";
				$_SESSION['jevcontent']="";
				$_SESSION['location']="";
				$_SESSION['custom_anonusername']="";
				$_SESSION['custom_anonemail']="";
				$_SESSION['custom_field4']="";
				$_SESSION['start_ampm']="";
				
				#Redire event submit page after email  : Yogi June 2016
				header("location:http://".$_SERVER["HTTP_HOST"]."/submit-your-event?mailmsg=".$mailMessage);
				exit();
			}
	}else{
				$_SESSION['title']=$_POST['title'];
				$_SESSION['catid']=$_POST['catid'];
				$_SESSION['ics_id']=$_POST['ics_id'];
				$_SESSION['view12Hour']=$_POST['view12Hour'];
				$_SESSION['publish_up']=$_POST['publish_up'];
				$_SESSION['start_12h']=$_POST['start_12h'];
				$_SESSION['publish_down']=$_POST['publish_down'];
				$_SESSION['end_12h']=$_POST['end_12h'];
				$_SESSION['jevcontent']=$_POST['jevcontent'];
				$_SESSION['location']=$_POST['location'];
				$_SESSION['custom_anonusername']=$_POST['custom_anonusername'];
				$_SESSION['custom_anonemail']=$_POST['custom_anonemail'];
				$_SESSION['custom_field4']="";
	}
	
	
	
	
}


/*
#Function name : "checkPostParameter()"
#Parameter     : $_POST array variable 
#Return value  : True or False
#Created by    : Yogi
#Date          : 27-06-2012
#Version       : 1.0
*/

function checkPostParameter($postValue){
	global $msg;
	if(!isvalidchar($postValue['title'])){
		$msg=JText::_('VALID_EV_NAME');
		return false;
	}
	if(empty($postValue['catid']) || $postValue['catid']=="0"){
		$msg=JText::_('VALID_EV_CAT');
		return false;
	}
	if(!isvalidchar($postValue['jevcontent'])){
		$msg=JText::_('VALID_EV_DES');
		return false;
	}
	if(!isvalidchar($postValue['custom_anonusername'])){
		$msg=JText::_('VALID_EV_USER');
		return false;
	}
	/*if(!isValidEmail($postValue['custom_anonemail'])){ 
		$msg=JText::_('VALID_EMAIL');
		return false;
		}*/
	return true;
}?>

<!--<link rel="stylesheet" type="text/css" href="../css/style.css" />-->
<!--<link rel="stylesheet" type="text/css" href="../../../templates/townwizard/css/fonts.css" />
<link href="../../../templates/rt_quantive_j15/favicon.ico" rel="shortcut icon" type="image/x-icon" />-->
<!--<link rel="stylesheet" href="../../../administrator/templates/khepri/css/icon.css" type="text/css" />-->
<link rel="stylesheet" href="../../../administrator/components/com_jevents/assets/css/eventsadmin.css" type="text/css" />
<link rel="stylesheet" href="../../../media/system/css/modal.css" type="text/css" />
<link rel="stylesheet" href="../../../components/com_jevents/assets/css/dashboard.css" type="text/css" />
<!--<script type="text/javascript" src="../../../includes/js/joomla.javascript.js"></script>-->
<script>
    var j$ = jQuery.noConflict();
</script>
<script type="text/javascript" src="../../../media/system/js/mootools.js"></script>
<script type="text/javascript" src="../../../administrator/components/com_jevents/assets/js/editical.js?v=1.5.4"></script>
<script type="text/javascript" src="../../../administrator/components/com_jevpeople/assets/js/people.js"></script>
<script type="text/javascript" src="../../../common/js/modal.js"></script>
<script type="text/javascript" src="../../../media/system/js/tabs.js"></script>
<script type="text/javascript" src="../../../plugins/editors/jce/tiny_mce/tiny_mce.js?version=156"></script>
<script type="text/javascript" src="../../../plugins/editors/jce/libraries/js/editor.js?version=156"></script>
<script type="text/javascript" src="../../../components/com_jevents/assets/js/calendar11.js"></script>
<script type="text/javascript" src="../../../administrator/components/com_jevlocations/assets/js/locations.js"></script>

<script type="text/javascript" src="../../../plugins/system/pc_includes/ajax_1.3.js"></script>
<!--<link href="/indexiphone.php?option=com_jevents&amp;task=modlatest.rss&amp;format=feed&amp;type=rss&amp;Itemid=111&amp;modid=0"  rel="alternate"  type="application/rss+xml" title="JEvents - RSS 2.0 Feed" />
<link href="/indexiphone.php?option=com_jevents&amp;task=modlatest.rss&amp;format=feed&amp;type=atom&amp;Itemid=111&amp;modid=0"  rel="alternate"  type="application/rss+xml" title="JEvents - Atom Feed" />-->

<script type="text/javascript" src="../../../common/js/event_submit.js"></script>

<script type="text/javascript" language="javascript">

	j$(document).ready(function(){
		var cheight = j$('#sbox-window').height();
		var cwidth = j$('#sbox-window').width();
/*			var top = ((j$(window).height() - cheight)/1.3)/10 + "%";
		var left = ((j$(window).width() - cwidth)/1.8)/10 + "%";	*/		
		var top = (j$(window).height() - cheight)/1.5 + "px";
		var left = (j$(window).width() - cwidth)/1.8 + "px";
		j$('#formBottom a.button').click(function(){
			j$('#sbox-window').css('top',top);
			j$('#sbox-window').css('left',left);
			j$('#sbox-window').css('position','absolute');
			j$('#sbox-window').css('margin','0');
			j$('#sbox-content').css('width','535px');
			j$('#sbox-content').css('height','455px');
			j$('#sbox-content').css('-webkit-overflow-scrolling','touch');
			j$('#sbox-content').css('overflow-y','hidden');
		});
		if (window.matchMedia("(min-width:500px) and (max-width:637px)").matches){
			j$('#formBottom a.button').click(function(){
				j$('#sbox-window').css('left','57%');
				j$('#sbox-window').css('top','105%');
				j$('#sbox-content').css('width','450px');
				j$('#sbox-content').css('height','450px');
			});
		}
		if (window.matchMedia("(min-width:361px) and (max-width:499px)").matches){
			j$('#formBottom a.button').click(function(){
				j$('#sbox-window').css('left','57%');
				j$('#sbox-window').css('top','105%');
				j$('#sbox-content').css('width','305px');
				j$('#sbox-content').css('height','400px');
			});
		}	
		if (window.matchMedia("(min-width:321px) and (max-width:360px)").matches){
			j$('#formBottom a.button').click(function(){
				j$('#sbox-window').css('left','83%');
				j$('#sbox-window').css('top','95%');
				j$('#sbox-content').css('width','298px');
				j$('#sbox-content').css('height','400px');
			});
		}
		if (window.matchMedia("(min-width:300px) and (max-width:320px)").matches){
			j$('#formBottom a.button').click(function(){
				j$('#sbox-window').css('left','94%');
				j$('#sbox-window').css('top','135%');
				j$('#sbox-content').css('width','261px');
				j$('#sbox-content').css('height','400px');
			});
		}
	});
	
function gotoindex(str){
//alert(str);
var id=document.getElementById(str).value;
	if(id=='<?php echo JText::_("JEV_CANCEL") ?>') {
		document.location='/index.php?option=com_jevents&view=week&task=week.listevents&Itemid=97'
		return false;
	}
}
function alldayeventtog() {
	var check = document.adminForm.allDayEvent.checked;
	var noendchecked = document.adminForm.noendtime.checked;
	var spm = document.getElementById("startPM");
	var sam = document.getElementById("startAM");
	var epm = document.getElementById("endPM");
	var eam = document.getElementById("endAM");
	if(check) {
		document.adminForm.noendtime.checked = false;
		document.adminForm.start_12h.disabled=true;
		document.adminForm.end_12h.disabled=true;
		spm.disabled=true;
		sam.disabled=true;
		epm.disabled=true;
		eam.disabled=true;

		if(!noendchecked) {
		epm.disabled=true;
		eam.disabled=true;
		document.adminForm.start_12h.disabled=true;
		document.adminForm.end_12h.disabled=true;
		spm.disabled=true;
		sam.disabled=true;
		} 

	} else {
		document.adminForm.start_12h.disabled=false;
		document.adminForm.end_12h.disabled=false;
		spm.disabled=false;
		sam.disabled=false;
		epm.disabled=false;
		eam.disabled=false;
	}
}
function noendtimetog(){
	var noendchecked = document.adminForm.noendtime.checked;
	var epm = document.getElementById("endPM");
	var eam = document.getElementById("endAM");

	if (noendchecked && document.adminForm.allDayEvent.checked) {
		document.adminForm.allDayEvent.checked = false;
		alldayeventtog();
	}
	if(noendchecked){
		if(epm != null){
			epm.disabled=true;
			eam.disabled=true;	
		}
		document.adminForm.end_12h.disabled=true;
	}else{
		if(epm != null){
			epm.disabled=false;
			eam.disabled=false;
		}	
		document.adminForm.end_12h.disabled=false;
	}
}
function form_validation() {
	if (document.adminForm.title.value==""){
		alert('<?php echo JText::_("VALID_EV_NAME") ?>');
		document.adminForm.title.focus();
		return false;
	}
	if (document.adminForm.catid.value=="0"){
		alert ('<?php echo JText::_("VALID_EV_CAT") ?>');
		document.adminForm.catid.focus();
		return false;
	}
	if (document.adminForm.custom_anonusername.value==""){
		alert ('<?php echo JText::_("VALID_EV_USER") ?>');
		document.adminForm.custom_anonusername.focus();
		return false;
	}
	if (document.adminForm.custom_anonemail.value==""){
		alert ('<?php echo JText::_("VALID_EMAIL") ?>');
		document.adminForm.custom_anonemail.focus();
		return false;
	}
	var stuchkemail=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,5})+$/.test(document.adminForm.custom_anonemail.value);
	if (!stuchkemail){
		alert ('<?php echo JText::_("VALID_EMAIL") ?>');
		document.adminForm.custom_anonemail.focus();
		return false;
	}
}
</script>
<style>
#fadeout {background: none repeat scroll 0 0 rgba(0, 0, 0, 0.86);display: none;height: 100%;left: 0;position: fixed;top: 0;width: 100%;z-index: 4000;}
.locationcont {left: 10%;position: absolute;top: 2%;width: 80%;z-index: 4001;}
.locationcont .close {border-radius: 14px;box-shadow: 0 0 5px #fff;color: #fff !important;cursor: pointer;display: block;font-family: verdana;font-size: 24px;height: 14px;line-height: 11px;padding: 5px 6px;position: absolute;right: -28px;text-align: center;top: -26px;}
#sbox-content iframe{width:535px;}
@media only screen and (min-width:371px) and (max-width:581px){.locationcont {left:10%;width:80%;}} 
@media only screen and (max-width:370px){.locationcont {left:5%;width:90%;}} 
@media only screen and (min-width : 1440px){.locationcont .close {right: -28px;top: -26px;}}
</style>

<?php
// Print Message after event form submission starts.
//if($msg!='') {
if(isset($_GET['mailmsg']) && $_GET['mailmsg'] != '') {?>
	<div id="Darkness" style="display: block"></div>
	<div id="systemmsg" class="takeOverlay">
		<a class="close">x</a>
		<span><?php echo $_GET['mailmsg'];?></span>
	</div>	
<?php } ?>

<!--Jevent Form Starts-->
<h2 id="sendEventsHeader"><?php echo JText::_('JEV_SENDEVNTS'); ?></h2>
	
<form action="" method="post" name="adminForm" enctype='multipart/form-data' onSubmit="return form_validation()" id="submitevent">

	<span><?php echo JText::_('JEV_EVNAME'); ?>:</span>
	<input class="inputbox" type="text" name="title" size="41" maxlength="255" value="<?php if(isset($_SESSION['title'])) echo $_SESSION['title']?>" />
	<span><?php echo JText::_('JEV_EVCAT'); ?>:</span>
	<?php $cat_query=mysql_query("select * from jos_categories where section='com_jevents' and published='1'");	?> 
	<select name="catid" id="catid">
		<option value="0" ><?php echo JText::_('JEV_CHOOSE'); ?></option>
		<?php while($row=mysql_fetch_array($cat_query)) { 
		if($_SESSION['catid'] == $row['id']){
			$selectedVal = 'selected';
		}else{
			$selectedVal = '';
		}
		?>
		<option value="<?php echo $row['id']?>" <?php echo $selectedVal?> ><?php echo $row['title']?></option>
		<?php } ?>
	</select>
	<span><input type="hidden" name="ics_id" value="<?php echo $ics?>" /></span>
	<fieldset class="mainFieldset">
		<legend><?php echo JText::_('JEV_EVSED'); ?></legend>
		<legend><?php echo JText::_('JEV_UNTIME'); ?></legend>
		<input type="checkbox" id='allDayEvent' name='allDayEvent' <?php if(isset($_SESSION['allDayEvent']) && $_SESSION['allDayEvent']=='on') {echo 'checked'; }?>  onclick="alldayeventtog()" />
		<input type="hidden" id='view12Hour' name='view12Hour' checked='checked' onClick="toggleView12Hour();" value="1"/>
		<fieldset>
			<legend><?php echo JText::_('JEV_STDATE'); ?></legend>
			<fieldset>
				<?php 
					if(empty($_SESSION['publish_up'])){ $publish_up_value = date("Y-m-d");
					}else{ $publish_up_value = $_SESSION['publish_up'];} 
				?>
				<input type="text" name="publish_up" id="publish_up" value="<?php echo $publish_up_value;?>" maxlength="10" onChange="var elem = $('publish_up');checkDates(elem);" size="10"  /> 
				
				<span><?php echo JText::_('JEV_STTIME'); ?>:</span>
				<?php
					/* Fetching Date Format from Page Global */
					$db1 =& JFactory::getDBO();
					$pageglobal = "select time_format from `jos_pageglobal`";
					$db1->setQuery($pageglobal);
					$df=$db1->query();
					$d=mysql_fetch_row($df);
					$tf=$d[0];

					if($tf == "12"){
						if(empty($_SESSION['start_12h'])){ 
							$start_12h_value = '08:00';
						}else{ 
							$start_12h_value = $_SESSION['start_12h']; 
						} 

						if(isset($_SESSION['start_ampm']) && $_SESSION['start_ampm']=='pm'){ 
							$start_ampm_check = 'checked="checked"';
						} 

						$end_ampm_check = array();
						if(isset($_SESSION['start_ampm']) && $_SESSION['start_ampm']=='pm'){ 
							$end_ampm_check['pm'] = 'checked="checked"';
							$end_ampm_check['am'] = '';
						}else{
							$end_ampm_check['pm'] = '';
							$end_ampm_check['am'] = 'checked="checked"';
						}
					?>

					<input class="inputbox" type="text" name="start_12h" id="start_12h" size="8" maxlength="8"  value="<?php echo $start_12h_value?>" onChange="check12hTime(this);" />
					<input type="radio" name="start_ampm" id="startAM" value="am" <?php echo $end_ampm_check['am']?> checked="checked" onClick="toggleAMPM('startAM');"  />am  
					<input type="radio" name="start_ampm" id="startPM" value="pm" <?php echo $end_ampm_check['pm']?> onClick="toggleAMPM('startPM');"  />pm
								
								<?php }else{
									if(empty($_SESSION['start_12h'])){ 
										$start_12h_value = '08:00';
									}else{ 
										$start_12h_value = $_SESSION['start_12h']; 
									} 
								?>
								
								<input class="inputbox" type="text" name="start_12h" id="start_12h" size="8" maxlength="8"  value="<?php echo $start_12h_value?>" onChange="check12hTime(this);" />	
									
								<?php }?>	
			</fieldset>
			<fieldset>
					<legend><?php echo JText::_('JEV_ENDDATE'); ?></legend>
						<?php 
							if(empty($_SESSION['publish_down'])){ 
								$publish_down_value = date("Y-m-d");
							}else{ 
								$publish_down_value = $_SESSION['publish_down']; 
							} 
						?>
					<input type="text" name="publish_down" id="publish_down" value="<?php echo $publish_down_value;?>" maxlength="10" onChange="var elem = $('publish_up');checkDates(elem);" size="10"  /> 
					       
					<span><?php echo JText::_('JEV_ENDTIME'); ?>:</span>
					<?php 
						if($tf == "12"){
							if(empty($_SESSION['end_12h'])){ 
								$end_12h_value = '05:00';
							}else{ 
								$end_12h_value = $_SESSION['end_12h']; 
							} 

							$end_ampm_check = array();
							if(isset($_SESSION['start_ampm']) && $_SESSION['start_ampm']=='pm'){ 
								$end_ampm_check['pm'] = 'checked="checked"';
								$end_ampm_check['am'] = '';
							}else{
								$end_ampm_check['pm'] = '';
								$end_ampm_check['am'] = 'checked="checked"';
							}
						?>

						<input class="inputbox" type="text" name="end_12h" id="end_12h" size="8" maxlength="8"  value="<?php echo $end_12h_value?>" onChange="check12hTime(this);" />
						<input type="radio" name="end_ampm" id="endAM" value="am" <?php echo $end_ampm_check['am']?> onClick="toggleAMPM('endAM');"  />am  
						<input type="radio" name="end_ampm" id="endPM" value="pm" <?php echo $end_ampm_check['pm']?> checked="checked" onClick="toggleAMPM('endPM');"  />pm
								
						<?php }else{
							if(empty($_SESSION['end_12h'])){ 
								$end_12h_value = '17:00';
							}else{ 
								$end_12h_value = $_SESSION['end_12h']; 
							} 
						?>
								
						<input class="inputbox" type="text" name="end_12h" id="end_12h" size="8" maxlength="8"  value="<?php echo $end_12h_value?>" onChange="check12hTime(this);" />	
									
						<?php }?>
						<input type="checkbox" id='noendtime' name='noendtime'  onclick="noendtimetog();" <?php if(isset($_SESSION['noendtime']) && $_SESSION['noendtime']==1) {echo 'checked'; }?> value="1" />
						<span id="noSpecTime"><?php echo JText::_('JEV_NOENDTIME'); ?></span>
				</fieldset>
		</fieldset>
	</fieldset>
	<span id="eventDescription"><?php echo JText::_('JEV_DES'); ?>:</span>
	<textarea name="jevcontent" cols="70" rows="10" style="width:99%;height:230px;" ><?php if(isset($_SESSION['jevcontent'])) echo $_SESSION['jevcontent'];?></textarea>
	<div id="formBottom">
		<span><?php echo JText::_('JEV_LOC'); ?></span>
		
		<input type="text" name="evlocation_notused" disabled="disabled" id="evlocation" value="--"/>
		<input type="hidden" name="location" id="locn" value=""/>
		<a class="button"  href="javascript:selectLocation('' ,'/index.php?option=com_jevlocations&amp;task=locations.select&amp;tmpl=component','550','450')" title="Select Location"  ><?php echo JText::_('JEV_SELECT'); ?></a>
		<a class="button" href="javascript:removeLocation();" title="Remove Location"  ><?php echo JText::_('JEV_REMOVE'); ?></a>
		<p><?php echo JText::_('JEV_LOCDES'); ?></p>
		<span><?php echo JText::_('JEV_YOURNAME'); ?></span>
		<input size="41" type="text" name="custom_anonusername" id="custom_anonusername" value="<?php if(isset($_SESSION['custom_anonusername'])) echo $_SESSION['custom_anonusername']?>" />
		
		<input size="41" type="email" name="custom_anonemail" id="custom_anonemail" value="<?php if(isset($_SESSION['custom_anonemail'])) echo $_SESSION['custom_anonemail']?>" />
		<span><?php echo JText::_('JEV_YOUREMAIL'); ?></span>

		<!--#DD#-->
		<?php
			global $mainframe;
			//set the argument below to true if you need to show vertically( 3 cells one below the other)
			$mainframe->triggerEvent('onShowOSOLCaptcha', array(false));
		?> 
		<!--#DD#-->

		<input src="images/save-btn.gif" type="submit" name="action" value="<?php echo JText::_('JEV_SEND'); ?>" class="button"/>
		<input type="submit" name="can" id="can" value="<?php echo JText::_('JEV_CANCEL'); ?>" class="button" onClick="gotoindex(this.id)"/>
	</div>

</form>
<!--Jevent Form Ends-->