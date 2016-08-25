$(function(){  
	$(document).ready(function() {


	});
});

// 로그인 후 바로 add page 호출
function loginAfterAdd(url){
	$('#loginForm').attr('action', url);
}

// 로그인 ajax 체크
function loginCheck(formEI){
	var check = false;
	var email = formEI.loginEmail.value;
	var pw = formEI.loginPassword.value;
	var jObj = new Object();
	jObj.findemail = email;
	jObj.findpw = pw;
	var jsonInfo = JSON.stringify(jObj);

	$(document).ready(function(){
		jQuery.ajax({
			type:"POST",
			url:"/auth/loginCheck",
			contentType : 'application/json; charset=UTF-8',
			data : jsonInfo,
			async : false,
			success:function(data){
				var dataParse = JSON.parse(data);
				$("div[id^='loginForm']").removeClass('has-error');
				$("p[id^='login-']").html('');
				$('#confirmMailBtn').addClass("displayNone");
				$('#resendPwForm').addClass("displayNone");
				if(email.length == 0){
					$('#loginFormEmail').addClass('has-error');
					console.log("id empty");
					check = false;
				}
				if(pw.length == 0){
					$('#loginFormPw').addClass('has-error');
					console.log("pw empty");
					check = false;
				}
				if(email.length != 0 && dataParse.count!="1"){
					$('#loginFormEmail').addClass('has-error');
					$('#login-email-label').html('입력하신 이메일과 일치하는 아이디가 없습니다.');
					console.log("일치하는 이메일이 없음");
					check = false;
				}
				else if(dataParse.count=="1"){
					console.log("일치하는 이메일이 1개 있음");
					var loginEmail = $('#loginEmail').val();
					if(dataParse.emailConfirm!="1"){
						// 이메일 인증 버튼 세팅
						$('#confirmMailBtn').attr('onclick', 'location.href=\'/auth/resendConfirmMail?userEmail='+encodeURIComponent(loginEmail)+'&returnURL='+encodeURIComponent(document.URL)+'\'');
						$('#loginFormEmail').addClass('has-error');
						$('#login-email-label').html('email 인증이 되지 않았습니다. 메일을 확인해 주시고 회원가입 인증 버튼을 눌러주세요.');
						$('#confirmMailBtn').removeClass("displayNone");
						console.log("이메일 확인안됨");
						check = false;
					}
					else if(pw.length != 0 && dataParse.loginCheck!="1"){
						// 임시 비밀번호 버튼 세팅
						$('#resendPwForm').attr('action', '/auth/sendPw?userEmail='+encodeURIComponent(loginEmail)+'&returnURL='+encodeURIComponent(document.URL));
						// $('#newPwBtn').attr('onclick', 'location.href=\'/auth/sendPw?userEmail='+encodeURIComponent(loginEmail)+'&returnURL='+encodeURIComponent(document.URL)+'\'');
						$('#loginFormPw').addClass('has-error');
						$('#login-password-label').html('비밀번호가 일치하지 않습니다.');
						$('#resendPwForm').removeClass("displayNone");
						console.log("비밀번호 불일치");
						return false;
					}
					else if(email.length != 0 && pw.length != 0 && dataParse.emailConfirm=="1" && dataParse.loginCheck=="1"){
						console.log("이메일 확인");
						check = true;
					}
				}
				console.log(data);
			}, error: function(xhr,status,error){
				alert(error);
				console.log("login check ajax false");
				return false;
			}
		}); 
	});
	return check;
}

// 회원가입 ajax 체크
function joinCheck(formEI){
	var check = true;
	var joinEmail = formEI.joinEmail.value;
	var joinNickname = formEI.joinNickname.value;
	var joinPassword = formEI.joinPassword.value;
	var joinPwConfirm = formEI.joinPwConfirm.value;
	var jObj = new Object();
	jObj.findEmail = joinEmail;
	jObj.findNickname = joinNickname;
	var jsonInfo = JSON.stringify(jObj);

	$(document).ready(function(){
		jQuery.ajax({
			type:"POST",
			url:"/auth/joinCheck",
			contentType : 'application/json; charset=UTF-8',
			data : jsonInfo,
			async : false,
			success:function(data){
				var dataParse = JSON.parse(data);
				$("div[id^='joinForm']").removeClass('has-error');
				$("p[id^='join-']").html('');
				if(joinEmail.length == 0){
					$('#joinFormEmail').addClass('has-error');
					check = false;
				} else if(dataParse.existEmail=="1"){
					$('#joinFormEmail').addClass('has-error');
					$('#join-email-label').html('이미 등록된 이메일 입니다.');
					console.log("이메일 중복");
					check = false;
				}
				if(joinNickname.length == 0){
					$('#joinFormNick').addClass('has-error');
					check = false;
				} else if(dataParse.existNickname=="1"){
					$('#joinFormNick').addClass('has-error');
					$('#join-nickname-label').html('이미 사용중인 닉네임 입니다.');
					console.log("닉네임 중복");
					check = false;
				}
				if((joinPassword.length == 0) || (joinPwConfirm.length == 0)){
					$('#joinFormPw').addClass('has-error');
					$('#joinFormPwC').addClass('has-error');
					check = false;
				} else if(joinPassword != joinPwConfirm){
					$('#joinFormPw').addClass('has-error');
					$('#joinFormPwC').addClass('has-error');
					$('#join-pw-label').html('비밀번호가일치하지 않습니다.');
					$('#join-cpw-label').html('비밀번호가 일치하지 않습니다.');
					console.log("비번 같지않음");
					check = false;
				}
			}, error: function(xhr,status,error){
				alert(error);
				console.log("login check ajax false");
				return false;
			}
		}); 
	});
	return check;
}

// 임시 비밀번호 보내기 이메일, 닉네임 ajax 체크
function tempPwNmCheck(userEmail){
	var check = false;
	var email = $('#loginEmail').val();
	var nickname = $('#nicknameCk').val();
	var jObj = new Object();
	jObj.findEmail = email;
	jObj.findNickname= nickname;
	var jsonInfo = JSON.stringify(jObj);

	$(document).ready(function(){
		jQuery.ajax({
			type:"POST",
			url:"/auth/joinCheck",
			contentType : 'application/json; charset=UTF-8',
			data : jsonInfo,
			async : false,
			success:function(data){
				var dataParse = JSON.parse(data);
				$("div[id^='loginForm']").removeClass('has-error');
				$("p[id^='login-']").html('');
				if(email.length == 0){
					$('#loginFormEmail').addClass('has-error');
					console.log('이메일란 empty');
					check = false;
				} else if(dataParse.existEmail!="1"){
					$('#loginFormEmail').addClass('has-error');
					$('#resendPwForm').addClass('displayNone');
					$('#login-email-label').html('입력하신 이메일과 일치하는 아이디가 없습니다.');
					console.log("존재하지 않는 이메일");
					check = false;
				}
				if(nickname.length == 0){
					$('#loginFormNewPw').addClass('has-error');
					console.log('닉네임란 empty');
					check = false;
				}
				if(dataParse.oneUserCheck=="1"){
					console.log('이메일과 닉네임 일치');
					check = true;
				}
				else{
					$('#loginFormEmail').addClass('has-error');
					$('#loginFormNewPw').addClass('has-error');
					$('#login-nick-label').html('이메일과 닉네임이 일치하지 않습니다.');
					console.log('이메일과 닉네임 블일치');
					check = false;
				}
			}, error: function(xhr,status,error){
				alert(error);
				console.log("check ajax false");
				return false;
			}
		}); 
	});
	return check;
}