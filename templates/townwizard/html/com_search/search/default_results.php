<?php defined('_JEXEC') or die('Restricted access'); 
// display search result for common search
function displayData($row,$menu_ids){ ?>
	<div class="thumb fr">
		<?php if($row['locimg']!='') {?>
			<a href="index.php?option=com_jevlocations&task=locations.detail&&Itemid=<?php echo $menu_ids[1] ?>&loc_id=<?php echo $row['loc_id'] ?>&title=<?php echo $row['title'] ?>"><img src="/partner/<?php echo $_SESSION['partner_folder_name']?>/images/stories/jevents/jevlocations/thumbnails/thumb_<?php echo $row['locimg']; ?>"></a><?php }?>
	</div>
	<a class="venueName bold fl" href="index.php?option=com_jevlocations&task=locations.detail&&Itemid=<?php echo $menu_ids[1] ?>&loc_id=<?php echo $row['loc_id'] ?>&title=<?php echo $row['title'] ?>"><?php echo $row['title'] ?></a>
	<div class="bc fr bold">
					<?php echo $row['cat']; ?>
	</div>
	<div class="rating">
					<h3><br/>
						<?php if($row['street']!='')
					 	 		echo $row['street'].","; 
						?><br/>
					  <?php if($row['state']!='' || $row['city']!='')
					  			echo $row['city'].",";
					  ?><br/>
					  <?php if($row['postcode']!='')
					  	echo $row['state'].", ".$row['postcode'] ?>
					</h3>
					<?php if($row['url']!=''){?>
						<h2><a class="bold" href="<?php echo "http://".$row['url'] ?>" target="_blank"><?php echo JText::_("TW_VISIT");?></a></h2>
					<?php }?>
					<?php if($row['phone']!=''){?>
						<h2 class="bold"><?php echo $row['phone'] ?></h2>
					<?php }?>
					
	</div>
<?php } 
// display results for events
function displayEvents($ev_res,$menu_ids,$format_res){ 
				$displayTime = '';
				if($ev_res['timestart']=='12:00 AM' && $ev_res['timeend']=='11:59PM'){
					    $displayTime.='All Day Event';
				}else{
					$displayTime.= $ev_res['timestart'];
					if ($ev_res['timeend'] != '11:59PM'){
						 $displayTime.="-".$ev_res['timeend'];
					}
				}
?>
	<div class="event">
		<div class="date fl"><?php echo $ev_res['Date'];?></div>
		<div class="details">
				<h3 style="font-size:12px;margin-bottom:3px;"><a class="ev_link_row" href="index.php?option=com_jevents&task=icalrepeat.detail&Itemid=<?php echo $menu_ids[1] ?>&evid=<?php echo $ev_res['rp_id'];?>&year=<?php echo $ev_res['Eyear'];?>&month=<?php echo $ev_res['Emonth'];?>&day=<?php echo $ev_res['EDate'];?>&catids=<?php echo $ev_res['catid'];?>&title=<?php echo $ev_res['summary'];?>"><?php echo $ev_res['summary'];?></a></h3>
					<?php echo $ev_res['category'];?> &bull; 
				    <?php if($format_res[1] == "12"){
				        		echo $displayTime;
					       }else{
						   		if ($ev_res['timeend'] != '11:59PM'){
									echo date("H:i", strtotime($ev_res['timestart']))." - ".date("H:i", strtotime($ev_res['timeend']));
								}else{
									echo date("H:i", strtotime($ev_res['timestart']));
								}
					        	
					       }
					?>
					&bull; <?php echo $ev_res['title'];?>
				<div class="cl"></div>
		</div>
	</div>
<?php } ?>

<?php if(isset($_REQUEST['m_id']) && $_REQUEST['m_id']!=''){ //search for particular menu item like places and restaurants
		$ser = $_REQUEST['searchword'];
		$cat_id = $_REQUEST['m_id'];
		$menu = &JSite::getMenu();
		$temp = $menu->getItem($cat_id);
		$iParams = new JParameter($temp->params);
		$categories = $iParams->get('catfilter');
		if(count($categories) > 1){
			$ser_cat = implode(',',$iParams->get('catfilter'));
		}else{
			$ser_cat = $iParams->get('catfilter');
		}

		if($ser!=''){
				$db =& JFactory::getDBO();
				$sql = "select *,jjl.title,jjl.image as locimg,jc.title as cat from `jos_jev_locations` jjl, `jos_categories` jc where jjl.loccat IN (".$ser_cat.") and jjl.loccat=jc.id and jjl.published=1 AND (jjl.title LIKE '%".addslashes($ser)."%') order by jjl.title";
				$db->setQuery($sql);
				$rows=$db->query();
		}
		?>
			<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
						<?php echo JText::_("TW_RES");?>
			</div>
			<div class="your"><?php echo "<br/>".JText::_("TW_YOUR");?></div>
			<?php if (mysql_num_rows($rows)!= 0){ ?>
				<ul id="SearchResults">
				<?php while($row = mysql_fetch_array($rows)){ ?>
					<li>
						<div class="thumb fr">
								<?php if($row['locimg']!='') {?>
								<a href="index.php?option=com_jevlocations&task=locations.detail&Itemid=<?php echo $cat_id;?>&loc_id=<?php echo $row['loc_id'] ?>&title=<?php echo $row['title'] ?>"><img src="/partner/<?php echo $_SESSION['partner_folder_name']?>/images/stories/jevents/jevlocations/thumbnails/thumb_<?php echo $row['locimg']; ?>"></a><?php }?>
						</div>
						<a class="venueName bold fl" href="index.php?option=com_jevlocations&task=locations.detail&Itemid=<?php echo $cat_id;?>&loc_id=<?php echo $row['loc_id'] ?>&title=<?php echo $row['title'] ?>"><?php echo $row['title'] ?></a>
						<div class="bc fr bold">
										<?php echo $row['cat']; ?>
						</div>
						<div class="rating">
										<h3><br/>
											<?php if($row['street']!='')
										 	 		echo $row['street'].","; 
											?><br/>
										  <?php if($row['state']!='' || $row['city']!='')
										  			echo $row['city'].",";
										  ?><br/>
										  <?php if($row['postcode']!='')
										  	echo $row['state'].", ".$row['postcode'] ?>
										</h3>
										<?php if($row['url']!=''){?>
											<h2><a class="bold" href="<?php echo "http://".$row['url'] ?>" target="_blank"><?php echo JText::_("TW_VISIT");?></a></h2>
										<?php }?>
										<?php if($row['phone']!=''){?>
											<h2 class="bold"><?php echo $row['phone'] ?></h2>
										<?php }?>
						</div>
					 </li>
			 	<?php } ?>
				 </ul>
				<?php }else{ ?>
					<h3 style="clear: both;padding-top: 20px;"><?php echo JText::_("LOC_RES");?></h3>
				<?php }
			}elseif($_REQUEST['m_id'] == ''){  //common search for events,places and restaurants
				$ser = $_REQUEST['searchword'];
				if($ser!=''){
					/*fetching locations */
					$db =& JFactory::getDBO();
					$sql = "select *,jjl.title,jjl.image as locimg,jc.title as cat from `jos_jev_locations` jjl, `jos_categories` jc where jjl.loccat = jc.id and jjl.published=1 AND (jjl.title LIKE '%".addslashes($ser)."%') order by jjl.title";
					$db->setQuery($sql);
					$rows=$db->query();
					
					/*fetching events */
					$dateformat = "SELECT date_format,time_format FROM jos_pageglobal LIMIT 1";
					$db->setQuery($dateformat);
					$format 	= $db->query();
					$format_res 	= mysql_fetch_row($format);
					$today 		= date('d');
					$tomonth 	= date('m');
					$toyear 	= date('Y');
					
					$event_query="SELECT ev.ev_id,evd.summary,rpt.rp_id, rpt.startrepeat,DATE_FORMAT(rpt.startrepeat,'%Y') as Eyear,DATE_FORMAT(rpt.startrepeat,'%m') as Emonth,DATE_FORMAT(rpt.startrepeat,'%d') as EDate,DATE_FORMAT(rpt.startrepeat,'".$format_res[0]."') as Date,DATE_FORMAT(rpt.startrepeat,'%h:%i %p') as timestart,DATE_FORMAT(rpt.endrepeat,'%h:%i%p') as timeend,rpt.endrepeat,evd.evdet_id, ev.catid,cat.title as category,evd.description, loc.title, evd.location FROM jos_jevents_vevent AS ev,jos_jevents_vevdetail AS evd,jos_jev_locations as loc, jos_categories AS cat,jos_jevents_repetition AS rpt WHERE rpt.eventid = ev.ev_id AND loc.loc_id = evd.location AND rpt.eventdetail_id = evd.evdet_id AND ev.catid = cat.id AND ev.state = 1 AND evd.summary LIKE '%".addslashes($ser)."%' AND rpt.endrepeat >= '".$toyear."-".$tomonth."-".$today." 00:00:00' GROUP BY ev.ev_id ORDER BY rpt.startrepeat" ;
					$db->setQuery($event_query);
					$rows2=$db->query();
				}
				?>
				<?php if (mysql_num_rows($rows)!= 0 || mysql_num_rows($rows2)!= 0){ // checking results are null or not 
						if(mysql_num_rows($rows2)!= 0){	//check events exist or not ?> 
								<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
									<?php echo JText::_("TW_LIST_OF_EVENTS");?>
								</div>
								<ul id="SearchResults">
									<?php while($ev_res = mysql_fetch_array($rows2)){ ?>
									  	<li>
											<?php 
											$param_res = "SELECT `parent`,`id`,`params` FROM `jos_menu` WHERE `link`='index.php?option=com_jevents&view=week&task=week.listevents' and parent='0' AND published = '1'";
											$db->setQuery($param_res);
											$menu_param=$db->query();
											while($menu_ids = mysql_fetch_array($menu_param)){
												$iParams = new JParameter($menu_ids[2]);
												$categories = $iParams->get('catid0');
												$sub_res = "SELECT `id` FROM `jos_categories` WHERE (parent_id='".$categories."' or id='".$categories."') AND published = '1'";
												$db->setQuery($sub_res);
												$sub_menu_id=$db->query();
												while($sub_menu_ids = mysql_fetch_array($sub_menu_id)){
													if($sub_menu_ids[0] == $ev_res['catid']){
														displayEvents($ev_res,$menu_ids,$format_res);
													}
												}
											} ?>
										</li>
									<?php } ?>
								</ul>
						<?php } if(mysql_num_rows($rows)!= 0){ //check locations exist or not ?>
								<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
									<?php echo JText::_("LIST_OF_LOCATIONS");?>
								</div>
									<ul id="SearchResults">
									<?php while($row = mysql_fetch_array($rows)){ ?>
										<li>
											<?php 
											$param_res = "SELECT `parent`,`id`,`params` FROM `jos_menu` WHERE `link`='index.php?option=com_jevlocations&task=locations.locations' and parent='0' AND published = '1'";
											$db->setQuery($param_res);
											$menu_param=$db->query();
											while($menu_ids = mysql_fetch_array($menu_param)){
												$iParams = new JParameter($menu_ids[2]);
												$categories = $iParams->get('catfilter');
												if(count($categories) >= 1){
													if(in_array($row['id'],$categories,TRUE)){
														displayData($row,$menu_ids);
													}
													if(count($categories)==1 && $categories==$row['id']){
														displayData($row,$menu_ids);
													}
											}} ?>
										 </li>
								 	 <?php } ?>
									  </ul>
				<?php } 
				} else { ?>
					<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
						<?php echo JText::_("TW_RES");?>
					</div>
					<div class="your"><?php echo "<br/>".JText::_("TW_YOUR");?></div>
					<h3 style="clear: both;padding-top: 20px;"><?php echo JText::_("LOC_RES");?></h3>
				<?php }
			}?>
		
