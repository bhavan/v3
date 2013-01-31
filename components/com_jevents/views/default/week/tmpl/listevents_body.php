<?php 
defined('_JEXEC') or die('Restricted access');

$cfg	 = & JEVConfig::getInstance();

$data = $this->datamodel->getWeekData($this->year, $this->month, $this->day);

$option = JEV_COM_COMPONENT;
$Itemid = JEVHelper::getItemid();

?>
<div class="bc fr" ><span class="bold">Event Type:</span><?php $this->viewNavCatText( $this->catids, JEV_COM_COMPONENT, 'cat.listevents', $this->Itemid );?></div>
<?php echo "<h3 class='fl heading display'>THIS WEEK</h3>";?>
<?php
if (count($data['catids'])==1 && $data['catids'][0]!=0 && strlen($data['catdesc'])>0){
	echo "<div class='jev_catdesc'>".$data['catdesc']."</div>";
}
echo "<ul class='ev_ul'>\n";

for( $d = 0; $d < 7; $d++ ){

	$day_link = '<a class="ev_link_weekday" href="' . $data['days'][$d]['link'] . '" title="' . JText::_('JEV_CLICK_TOSWITCH_DAY') . '">'

	. JEventsHTML::getDateFormat( $data['days'][$d]['week_year'], $data['days'][$d]['week_month'], $data['days'][$d]['week_day'], 5 ).'</a>'."\n";

	if( $data['days'][$d]['today'])	$bg = 'class="ev_td_today"';
	else $bg = 'class="ev_td_left"';

//	echo '<tr><td ' . $bg . '>' . $day_link . '</td>' . "\n";
//	echo '<td class="ev_td_right">' . "\n";

	$num_events		= count($data['days'][$d]['rows']);
	if ($num_events>0) {
		

		for( $r = 0; $r < $num_events; $r++ ){
			$row = $data['days'][$d]['rows'][$r];

			/*$listyle = 'style="border-color:'.$row->bgcolor().';"';*/
			echo "<li class='ev_td_li' $listyle><div class='date fl'>$day_link </div><div class='details'>\n";
			if (!$this->loadedFromTemplate('icalevent.list_row', $row, 5)){
				$this->viewEventRowNew ( $row);
				echo "&nbsp;::&nbsp;";
				$this->viewEventCatRowNew($row);
			}
			echo "</div></li>\n";
		}
		//echo "</ul>\n";
	}
	//echo '</td></tr>' . "\n";
} // end for days

echo "</ul>\n";//echo '</table><br />' . "\n";
//echo '<br /><br />' . "\n";
