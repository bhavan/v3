  <div id="main" role="main">

<div id="zigzag" style="vertical-align:bottom;"> </div>

	<div id="leftnav">

		  <?php 

			if ($current_page!=0)

			{

				 $st1=($current_page*$num_rec)-$num_rec;	

			?> 

			<a href="photos_view.php?start=<?php echo $st1?><?php echo $paginationstr?>&backstart=<?php echo $_REQUEST[backstart]?>&id=<?php echo $CatId ?>"></a> 

								

			<?php }	else {?>

				<a href="galleries.php"></a>

			 <?php }?>

   	</div>  

	<div id="rightnav">

	<?php

					  if (($current_page+1)<$num_pages)

				 		 {

					  $st1=($current_page*$num_rec)+$num_rec;

					  ?>

                    	<a href="photos_view.php?start=<?php echo $st1?><?php echo $paginationstr?>&backstart=<?php echo $_REQUEST[backstart]?>&id=<?php echo $CatId ?>"></a><?php }?>





	</div>


<div id="content">

          <!--<div align="center"><a href="photos.php?start=<?php echo $_REQUEST[backstart]?>" >BACK</a></div> -->

	<ul class="mainList" id="placesList">

		<li class="textbox" style="text-align:center;">

    <?php while($row=mysql_fetch_array($rec)) {

    $file=explode('/',$row['filename']);

			$j++;

			

			if (count($file)>=2)

			{

			?>

     <a href="photos_view.php?start=<?php echo $photoindent+$j?>"><img src="/partner/<?php echo $_SESSION['partner_folder_name']?>/images/phocagallery/<?php echo $file[0]?>/thumbs/phoca_thumb_l_<?php echo $file[1]?>"  border="0" /></a>

      <?php }

			else {

			?>

      <a href="photos_view.php?start=<?php echo $photoindent+$j?>"><img src="/partner/<?php echo $_SESSION['partner_folder_name']?>/images/phocagallery/thumbs/phoca_thumb_l_<?php echo $row['filename']?>"  border="0"/></a>

      <?php

			}

			echo '<div>'.$row[title].'</div>';

			?>

    

      <?php

			}

			?>

		</li>

	</ul>

        

</div> 

<!-- <div id="topbar"><div id="rightnav"><a href="upload.php">Upload</a></div></div> --> 

</div>

<div style='display:none;'><?php echo $pageglobal['googgle_map_api_keys']; ?></div>