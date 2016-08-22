<div class="col-sm-12">
	<ul class="nav nav-tabs nav-stacked" id="sidebar">
		<?php
		foreach($topics as $entry){
			?>
			<li><a href="/main/get/<?=$entry->id?>"><?=$entry->title?></a></li>
			<? 
		}
		?>
	</ul>	
</div>