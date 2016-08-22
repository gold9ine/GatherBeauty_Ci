<div class="siteWidth center">
	<article>
		<h1><?=$topic->title?></h1>
		<div>
			<div>
				<!-- <?=date('o년 n월 j일, G시 i분 s초', $topic->created)?> -->
				<?=kdate($topic->created)?>
			</div>
			<?=$topic->description?>
		</div>
	</article>
	<div>
		<form action=<?=site_url("/main/delete?returnURL=".rawurlencode(current_url()))?> method="post">
			<input type="hidden" class="btn btn-default" name="topic_id" value="<?=$topic->id?>" />
			<?php 
			if($this->session->userdata('is_login')){ ?>
				<a class="btn btn-default" href="/main/add" role="button">추가</a>
				<input type="submit" class="btn btn-default" value="삭제" />
				<?php 
			} else { $addUrl = site_url('/auth/authentication?returnURL='.rawurlencode(site_url('/main/add')).'&currentURL='.rawurlencode(current_url()));  ?>
				<input type="button" name="addBtn" value="추가" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#loginModal" onclick="loginAfterAdd('<?=$addUrl;?>')" />
				<?php 
				} ?>
			</form>
		</div>
	</div>