$(function(){  
	$(document).ready(function() {
		// $('#myModal').modal('show');
	});
});

function loginAfterAdd(url){
	$('#loginForm').attr('action', url);
}

function logincheck(formEI){
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
				if(email.length == 0 || pw.length == 0){
					console.log("empty");
					return false;
				}
				if(dataParse.count!="1"){
					console.log("일치하는 이메일이 없음");
					alert("입력하신 이메일과\n일치하는 아이디가 없습니다.");
					return false;
				}
				else if(dataParse.count=="1"){
					console.log("일치하는 이메일이 1개 있음");
					if(dataParse.emailConfirm!="1"){
						console.log("이메일 확인안됨");
						alert("email 인증이 되지 않았습니다.\n메일을 확인해 주시고\n회원가입 인증 버튼을 눌러주세요. ^^");
						return false;
					}
					else if(dataParse.loginCheck!="1"){
						console.log("비밀번호 불일치");
						alert("비밀번호가 일치하지 않습니다.");
						return false;
					}
					else if(dataParse.emailConfirm=="1" && dataParse.loginCheck=="1"){
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