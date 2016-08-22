//debuging message set
//webnote.setDebug();

//webnote config
webnote.setConfig({
//	auto_start:			false,									//페이지로딩시 페이지에 웹노트 에디터를 자동으로 생성할것인지(true: 자동생성, false: 생성안함)
//	lang:				"en",									//언어셋(lang 디렉토리내에 언어셋.txt 파일이 있어야 함(ex: ko.txt)
	base_dir:			"/include/webnote",								//웹노트 설치디렉토리를 직접 지정
//	css_url:			"/webnote/webnote.css",					//기본 css 파일을 직접 지정
//	icon_dir:			"/webnote/icon",						//기본 아이콘 디렉토리를 직접 지정
//	emoticon_dir:		"/webnote/emoticon",					//기본 이모티콘 디렉토리를 직접 지정
//	attach_proc:		"/webnote/webnote_attach.php",			//에디터에 이미지 즉시 업로드를 처리하는 서버스크립트를 직접 지정
//	delete_proc: 		"/webnote/webnote_attach.php",			//에디터에 즉시 업로드된 이미지 삭제를 처리하는 서버스크립트를 직접 지정(attach_proc 과 같을경우 설정 불필요)
//	use_blind:		true,									//팝업메뉴 출력 시 반투명 배경 스크린 사용여부(true:사용(기본), false: 미사용)
	allow_dndupload:	true,									//드래그&드롭을 통한 이미지 파일 업로드 허용 여부
	allow_dndresize:	true									//드래그&드롭을 통한 에디터 사이즈(높이) 조절 허용 여부
//	fonts:				["굴림체","궁서체"],					//선택할 수 있는 폰트종류를 직접 정의
//	fontsizes:			["9pt","10pt"],							//선택할 수 있는 폰트사이즈를 직접 정의(단위포함)
//	lineheights:		["120%","150%","180%"],					//선택할 수 있는 줄간격을 직접 정의(단위포함)
//	emoticons:			["Nerd"],						//선택할 수 있는 이모티콘들을 직접 정의(png파일은 확장자 생략 가능하며, 그외에는 확장자까지 입력 : PNG, GIF, JPG 만 가능)
//	specialchars:		["§","☆"],								//선택할 수 있는 특수문자를 직접 정의
//	code_highlight:		true,
        //fade_popup: false,                                                        //팝업 열리기/닫히기 시 fade in/out 기능 적용 여부(사용pc 사용이 낮은경우 false로 )
        //attach_list_view: false
});

//webnote user tools set
/*
webnote.setUserTools([
    {
		name: "brick",
		text: "내아이콘1",
		content: "<div class='webnote_popup_container_top'><textarea name='mycontents' id='mycontents' style='width:98%;height:100px'></textarea></div><div class='webnote_popup_container_bottom'><input type='button' class='webnote_btn_center' value='본문에삽입' onClick='insertMyContents()'></div>",
		popup_width: 300,
		callback: brink_func
    },
    {
		name: "bricks",
		text: "내아이콘2",
		content: "<div class='webnote_popup_container_top' id='mycontents2'></div><div class='webnote_popup_container_bottom'><input type='button' class='webnote_btn_center' value='닫기' onClick='myclosepop()'></div>",
		popup_width: 400,
		callback: function() {
			brinks_func();
		}
    }
]);
*/
//webnote create callback set
webnote.onCreateCB = function() {
	myCallBack();
}
function myCallBack() {
	webnote.html("<p>최초 생성된 에디터1의 초기 내용 입니다.</p>");
        focusEditor1();
}


/* -- 최초 로딩 시 소스모드로 시작하도록 콜백에 기능 추가
webnote.onCreateCB = function() {
        //webnote.toggleSourceMode("source");
}
*/




function insertMyContents() {
	webnote.insertHTML($_("mycontents").value);
	webnote.closePopup();
}
function brink_func() {
    $_("mycontents").focus();
}
function brinks_func() {
	var html = webnote.getSelectHtml();
	if(html == "") html = "선택영역 없음";
	$_("mycontents2").innerHTML = html;
}
function myclosepop() {
	webnote.closePopup();
}

function checkForm(form) {
	
	if(form.subject.value == "") {
		//alert("제목을 입력해주세요");
        webnote.showToastMessage("제목을 입력해주세요",1);
		form.subject.focus();
		return false;
	}
	if(form.contents1.value == "") {
		//alert("내용을 입력해주세요");
        webnote.showToastMessage("내용을 입력해주세요",1);
		webnote.focusWebNote("contents1")		//에디터에 포커스를 주기위한 webnote 내장함수
		return false;
	}

	return true;
}

function empty() {
	webnote.empty();
}
function append() {	
	webnote.append($_("append_data").value);
}
function prepend() {	
	webnote.prepend($_("append_data").value);
}
function setContents() {
	webnote.html($_("append_data").value);
}
var editor_cnt = 1;
function createEditor() {

	editor_cnt++;
	var edtBox = document.createElement("div");
	edtBox.style.marginTop = "10px";
	edtBox.innerHTML = "<textarea name=\"contents"+editor_cnt+"\" editor=\"webnote\" style=\"height:150px;width:700px\"></textarea>";
	document.getElementById("dEditor").appendChild(edtBox);
	
	//콜백 재정의
	webnote.onCreateCB = function() {
		myCallBack2();
	}

	webnote.initialize();
}
function myCallBack2() {
	webnote.html("<p>동적으로 생성된 에디터"+editor_cnt+" 의 초기 내용 입니다.</p>");
}
function viewUploadImageList() {
	var list = webnote.getUploadImages();
	var data = "";
	for(var i = 0; i < list.length; i++) {
		data += " ["+list[i].org_name +"]"+list[i].url+"\n";
	}
	alert(data);
	
}
function addFileList() {
    
	var imglink = document.getElementById("idxUserAddImageLink").value;
	var imgName = imglink.split("/").pop();

	//목록에 추가
	webnote.addUploadImages(imgName,imglink);

	//화면에 출력
	//webnote.addFileAttachList("이미지파일URL","이미지 ALT","image");
	webnote.addFileAttachList(imglink,imgName,"image");

}
function viewContents() {
	alert(webnote.html());
}
function viewContents2() {
	alert(webnote.text());
}
function focusEditor1() {
	webnote.focusWebNote("contents1");
}
function focusEditor2() {
	webnote.focusWebNote("contents2");
}

function showLoading() {
    webnote.showLoading();
    setTimeout(function() {
        webnote.hideLoading();    
    },3000);
    
}
function showToastMessage() {
    webnote.showToastMessage("토스트메세지 입니다. 2초간 메세지를 보여주고 사라집니다.",2);
}