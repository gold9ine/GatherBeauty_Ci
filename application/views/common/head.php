<?PHP
header('Content-Type: text/html; charset=utf-8');
session_cache_expire(30);
// session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<!-- 공통파일 포함 -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="subject" content="테스트용">
	<meta name="author" content="gold9ine">
	<meta name="keywords" content="gatherbeauty">
	<meta name="description" content="테스트 홈페이지">
	<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,target-densitydpi=medium-dpi">
	<meta name="apple-mobile-web-app-title" content="테스트 페이지">
	<!--모바일용 화면크기 비율고정-->
	<meta id="mobileMeta" name="viewport" content="width=device-width">

	<title>테스트 페이지</title>
	<link rel="shortcut icon" href="/include/img/icon_gb.ico">
	<link rel="apple-touch-icon" href="/include/img/icon_gb.ico">

	<!-- java script -->
	<script language="Javascript" src="/include/lib/ckeditor/ckeditor.js"></script>
	<!-- <script language="javascript" src="//apis.daum.net/maps/maps3.js?apikey=<?=$mapKey?>&libraries=services"></script> -->
	<!-- <script language="Javascript" src="/include/lib/webnote/webnote.js"></script>
	<script language="Javascript" src="/include/lib/webnote/webnoteSub.js"></script> -->

	<!-- style sheet -->
	<link rel="stylesheet" href="/include/lib/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/include/lib/bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="/include/css/layout.css">
	<link rel="stylesheet" href="/include/css/nanumgothic.css">
</head>
<body>
	<?php
	if($this->session->flashdata('message')){ ?>
		<script language="Javascript">alert('<?=$this->session->flashdata('message')?>');</script>	
		<?php
	} ?>
	<nav class="navbar navbar-default siteWidth">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarFunctionGroup">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">GatherBeauty</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="navbarFunctionGroup">
				<form class="navbar-form navbar-left form-inline" role="search">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
					</div>
						<button id="searchBtn" type="submit" class="btn btn-default">찾기</button>
				</form>
				<ul id="" class="nav navbar-nav navbar-right pull-right">
					<?php	
					if($this->session->userdata('is_login')){	
						echo $this->session->userdata('userNm');
						?>
						<input type="button" name="logout" value="로그아웃" class="btn btn-default navbar-btn" onclick="location.href='/auth/logout'";>
						<?php	
					} else{ ?>
						<input type="button" name="login" value="로그인" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#loginModal">
						<input type="button" name="join" value="회원가입" class="btn btn-default navbar-btn" onclick="location.href='/auth/register'";>
						<?php	
					}	?>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
	<!-- /.inside content -->
	<div class="siteWidth center-block clearfix">