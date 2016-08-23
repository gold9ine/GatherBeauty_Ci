<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">로그인</h4>
			</div>
			<form id="loginForm" onsubmit="return loginCheck(this)" name="loginForm" class="form-horizontal" action="<?=site_url('/auth/authentication?returnURL='.rawurlencode(current_url()))?>" method="post">
				<div class="modal-body">
					<fieldset>
						<!-- E-mail -->
						<div id="loginFormEmail" class="form-group">
							<label class="control-label col-sm-3" for="inputEmail">아이디</label>
							<div class="col-sm-9">
								<input type="email"  id="loginEmail" name="userEmail" placeholder="이메일 아이디를 입력해 주세요." class="form-control" maxlength="30">
							</div>
							<div class="col-sm-offset-3 col-sm-9">
								<p id="login-email-label" class="help-block"></p>
								<button id="confirmMailBtn" type="button" class="btn btn-default pull-right displayNone" data-dismiss="modal" onclick="">인증메일 다시 보내기</button>
							</div>
						</div>
						<!-- Password -->
						<div id="loginFormPw" class="form-group">
							<label class="control-label col-sm-3" for="inputPassword">비밀번호</label>
							<div class="col-sm-9">
								<input type="password" id="loginPassword" name="userPassword"  placeholder="6자리 이상의 비밀번호를 입력해 주세요." class="form-control" minlength="6" maxlength="30">
							</div>
							<div class="col-sm-offset-3 col-sm-9">
								<p id="login-password-label" class="help-block"></p>
							</div>
						</div>    
					</fieldset>
				</div>
				<div class="modal-footer">
						<button id="newPwBtn" type="button" class="btn btn-primary pull-left displayNone" onclick="">비번 찾기</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
						<button type="submit" class="btn btn-primary">로그인</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

