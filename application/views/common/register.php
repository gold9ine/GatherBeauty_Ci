<div class="col-sm-12">
	<div class="page-header">
		<h1><small>회원가입</small></h1>
	</div>
	<div class="col-sm-6 finitial center-block">
		<?php 
		echo validation_errors(); 
		?>
		<form class="form-horizontal" action="/auth/register" method="post">
			<fieldset>
				<div class="form-group">
					<!-- E-mail -->
					<label class="control-label col-sm-3" for="joinEmail">이메일</label>
					<div class="col-sm-9">
						<input type="email" id="joinEmail" name="joinEmail" value="<?php echo set_value('joinEmail'); ?>" placeholder="Email" class="form-control">
					</div>
					<div class="col-sm-offset-3 col-sm-9">
						<p id="join-email-label" class="help-block">이메일로 회원가입 확인 메일이 보내집니다.</p>
					</div>
				</div>

				<div class="form-group">
					<!-- Username -->
					<label class="control-label col-sm-3" for="joinNickname">닉네임</label>
					<div class="col-sm-9">
						<input type="text" id="joinNickname" name="joinNickname"  value="<?php echo set_value('joinNickname'); ?>" placeholder="Nickname" class="form-control" minlength="6" maxlength="10">
						<!-- <button type="button" class="btn btn-xs btn-info join-confirm-btn">중복확인</button> -->
					</div>
					<div class="col-sm-offset-3 col-sm-9">
						<p id="join-nickname-label" class="help-block">회원관리 페이지에서 수정 가능합니다.</p>
					</div>
				</div>

				<div class="form-group">
					<!-- Password-->
					<label class="control-label col-sm-3" for="joinPassword">비밀번호</label>
					<div class="col-sm-9">
						<input type="password" id="joinPassword" name="joinPassword" value="<?php echo set_value('joinPassword'); ?>" placeholder="Password" class="form-control" maxlength="20">
					</div>
					<div class="col-sm-offset-3 col-sm-9">
						<p id="join-pw-label" class="help-block">4 자 이상 20 자 미만 비밀번호를 입력해 주세요.</p>
					</div>
				</div>
				<div class="form-group">
					<!-- Password -->
					<label class="control-label col-sm-3" for="joinPwConfirm">비밀번호 확인</label>
					<div class="col-sm-9">
						<input type="password" id="joinPwConfirm" name="joinPwConfirm" value="<?php echo set_value('joinPwConfirm'); ?>" placeholder="Confirm Password" class="form-control" maxlength="20">
					</div>
					<div class="col-sm-offset-3 col-sm-9">
						<p id="join-cpw-label" class="help-block">비밀번호를 한번 더 입력해 주세요.</p>
					</div>
				</div>
				<div class="pull-right">
					<input type="submit" class="btn btn-primary" value="회원가입" />
				</div>
			</fieldset>
		</form>
	</div>
</div>