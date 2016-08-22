<form action="/main/add" method="post" class="siteWidth center">
	<?php echo validation_errors(); ?>
	<input class="col-sm-12" type="text" name="title" placeholder="제목"/>
	<textarea class="col-sm-12" rows="15" name="description" id="description" placeholder="본문"></textarea>
	<input type="submit" />
</form>

<script language="Javascript">
	CKEDITOR.replace('description',{
		'filebrowserUploadUrl':'/topic/upload_receive_from_ck'
	});
</script>