<div id="bottomUpBtn" class="navbar-fixed-bottom siteWidth marginZero">
	<!-- 에러신고, 게시물신고, 제휴문의, 기타문의 -->
	<?php
	if($this->session->userdata('is_login')){ ?>
		<img id="sendMessageBtn" class="img-rounded pointer pull-left" src="/include/img/button/send_message_btn.png" type="button" data-toggle="modal" data-target="#sendMessage-form" title="관리자에게 전하기">
		<?php
	} ?>
	<a href="#top"><img id="pageUpBtn" class="img-rounded pointer pull-right" src="/include/img/button/top_move_btn.png" title="위로"></a>
</div>
<div id="sendMessage-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="쪽지 보내기" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">관리자에게 쪽지 보내기</h4>
			</div>
			<form class="form-horizontal" name="sendMessage-form" role="form" action="/user/sendMessage" method="POST">
				<div class="modal-body">
					<fieldset>
						<div class="control-group">
							<!-- messageType -->
							<label class="control-label col-md-3" for="messageType">쪽지 분류</label>
							<div class="controls col-md-9">
								<select id="messageType" name="messageType" class="form-control">
									<option>에러 신고</option>
									<option>게시물 신고</option>
									<option>기타 문의</option>
								</select>
							</div>
							<div class="col-md-offset-3 col-md-9">
								<br>
								<br>
							</div>
						</div>
						<div class="control-group">
							<!-- messageSubject -->
							<label class="control-label col-md-3" for="subject">제목</label>
							<div class="controls col-md-9">
								<input type="text" id="messageSubject" name="messageSubject" placeholder="제목을 입력해 주세요." class="input-xlarge form-control">
							</div>
							<div class="col-md-offset-3 col-md-9">
								<br>
								<br>
							</div>
						</div>
						<div class="control-group">
							<!-- messageContent -->
							<label class="control-label col-md-3" for="content">내용</label>
							<div class="controls col-md-9">
								<textarea id="messageContent" name="messageContent" placeholder="내용을 입력해 주세요." class="form-control" rows="3"></textarea>
							</div>
							<div class="col-md-offset-3 col-md-9">
							</div>
						</div>
						<div class="hidden">
							<?PHP
							if($logined){$userEmail=$_SESSION['user_email']; $userNickname=$_SESSION['user_nickname'];}
							else{$userEmail = "user@mail"; $userNickname="GB게스트";}
							echo("<input type=\"text\" id=\"messageUserEmail\" name=\"messageUserEmail\" value=\"$userEmail\">
								<input type=\"text\" id=\"messageUserNickname\" name=\"messageUserNickname\" value=\"$userNickname\">");
								?>
							</div>
						</fieldset>
					</div>
					<div class="modal-footer text-right">
						<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
						<button type="submit" class="btn btn-primary">보내기</button>
					</div>
				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->