<?php
/******************************************************************************************
* WebNote
* 공식배포처 : http://www.webwork.kr
* 제작자 이메일 : webwork.kr@gmail.com
* 이프로그램은 개인/기업/영리/비영리 에 관계없이 웹개발에 무료로 자유롭게 사용할 수 있습니다.
* 소스내의 주석문은 어떠한 경우라도 수정/삭제할 수 없습니다.
* 제작자가 배포한상태 그대로인 경우에 한해 재배포를 허용하며 수정된 소스나, 수정된 소스가 포함된 프로그램, 소스중의 일부분을 타인에게 배포하거나 판매할 수 없습니다.
* 이프로그램의 사용으로 인해 발생한 어떠한 문제도 제작자는 책임지지 않습니다.
* 기타 자세한 사항은 공식 배포사이트를 참조해 주시고, 사용중 문의나 건의사항은 공식 배포사이트의 게시판을 이용해주시거나 메일로 보내 주시기 바랍니다.
******************************************************************************************/
$version = "15.4";
session_start();

header('X-Powered-By: WebNote '.$version);
header('Content-Type: text/html; charset=utf-8');

//* User Config */
//계정내의 webnote 설치경로(계정의 DocumentRoot 최상위부터의 위치 표기 : "/"로 시작)
$webnote_uri			= "/include/webnote";			

//즉시 파일 업로드 디렉토리 (현재 파일을 기준으로 상대 또는 절대경로)
$upload_path			= "upload/_quick/";

//업로드 허용 파일 확장자
$allow_file_ext			= array("jpg","gif","png");

//이미지센터에 파일 업로드 디렉토리 (현재 파일을 기준으로 상대 또는 절대경로)
$wic_upload_path		= "upload/_wic/";

//이미지센터 파일 업로드 디렉토리(웹루트에서부터 "/"로 시작)
$wic_upload_uri			= "/webnote/upload/_wic/";


//이미지센터 업로드 파일명 중복시 처리 (true: 이름변경하여 업로드(마지막에 "_1" 붙임, false : 업로드 불가(안내문 출력))
$wic_file_auto_rename 	= true;	

//파일 FULL URL
if($_SERVER["HTTPS"] == "on") {
	$upload_url			= "https://".$_SERVER["HTTP_HOST"].$webnote_uri."/upload/_quick/";
	$wic_upload_url		= "https://".$_SERVER["HTTP_HOST"].$webnote_uri."/upload/_wic/";
} else {
	$upload_url			= "http://".$_SERVER["HTTP_HOST"].$webnote_uri."/upload/_quick/";
	$wic_upload_url		= "http://".$_SERVER["HTTP_HOST"].$webnote_uri."/upload/_wic/";
}

//year,month,day (year: $image_webpath/2012) , default : day
$dir_type			= "day";			

//이미지 업로드 시 가능한 최대 가로사이즈(픽셀) : 초과시 이 사이즈로 리사이징되어 저장됨(0: 제한없음)
$allow_image_max_width		= 600;	    

// 업로드제한용량(byte) (기본 : 1메가, 0: 무제한 , 서버의 설정에따라 별도의 제한이 걸릴 수 있습니다.)
$allow_image_max_volume		= 1024*1024*1;

//쎰네일디렉토리명
$thumb_dir_name			= "_thumb";

//썸네일 사이즈(픽셀) : 가로,세로 사이즈중 큰쪽 :: 이미지센터 리스트에 사용
$thumb_max_size			= 70;

//썸네일 사이즈(픽셀) : 가로,세로 사이즈중 큰쪽 :: 이미지 업로드삽입시 에디터 하단 첨부목록에 사용
$thumb_max_size_quick		= 40;


//이페이지를 호출한 URL이 타 사이트인경우 허용안함
if(!preg_match('@^http[s]*:\/\/'.$_SERVER["SERVER_NAME"].'@ui',$_SERVER['HTTP_REFERER'])) {
	echo('0|File Not Found'); 
	exit;
}


//quick 폴더 생성d
makeDir($upload_path);
if($dir_type == "year" || $dir_type == "month" || $dir_type == "day") {
	$upload_path		.= date("Y");
	$upload_url			.= date("Y");
	makeDir($upload_path);
}
if($dir_type == "month" || $dir_type == "day") {
	$upload_path		.= "/".date("m");
	$upload_url			.= "/".date("m");
	makeDir($upload_path);
}
if($dir_type == "day") {
	$upload_path		.= "/".date("d");
	$upload_url			.= "/".date("d");
	makeDir($upload_path);
}

//wic 폴더 생성
makeDir($wic_upload_path);
makeDir($wic_upload_path.$thumb_dir_name);

function makeDir($path) {
	if(!@is_dir($path)) {
		@mkdir($path,0707);
		@chmod($path,0707);
	}
}

// '../' 나 '/ 두개이상' 이 있는경우 '/'로 모두 치환한다.  
function checkDirPath($path) {
	
	$pattern[] 		= '/(\.\.\/)|(\/+)/';
	$replacement[] 	= '/';
	
	$path = preg_replace($pattern, $replacement ,$path);
	
	return $path;
	
}

function checkFolderName($path) {
    
    $path_array = explode("/",$path);
    $result = true;
    for($i = 0; $i < count($path_array); $i++) {
        
        if(preg_match('/^[_\-\s]/', $path_array[$i])) {
            $result = false;
            break;
        }
        if(preg_match('/[\'\"\*#\$@!&\^%<>\?,\.\{\}~`\=\+\\\|\/:;]/', $path_array[$i])) {
            $result = false;
            break;
        }
        
        
    }
    
    return $result;
    
    
}

//디렉토리 삭제(하위 디렉토리,파일 모두 삭제)
function rmdirAll($path) {
     
    if(!@rmdir($path)) {
        
        if ($handle = opendir($path)) {
                while (false !== ($dir_name = readdir($handle))) {
                        if($dir_name != '.' && $dir_name != '..') {

                            $curr_path = $path."/".$dir_name;

                            if(is_file($curr_path)) {
                                unlink($curr_path);
                            }

                            if(is_dir($curr_path)) {
                                rmdirAll($curr_path);
                            }

                        }
                }            
                closedir($handle);
                @rmdir($path);
        }
    }
    
}


function makeThumbnail($file_path, $save_path, $max_size = 100, $target=""){

	if($target != "width" && $target != "height") $target = "";
	
	$imageObj = getimagesize($file_path);

	//사이즈 제한이 없거나, 원본이미지가 지정된 크기보다 작거나 같으면 원본을 복사만 한다.
	if(
			( $max_size  == 0 )
			|| ( $target == "width" && $max_size >= $imageObj[0] )
			|| ( $target == "height" && $max_size >= $imageObj[1] )
			|| ( $target == "" && $max_size >= $imageObj[0] && $max_size >= $imageObj[1] )
		) 
	{
		copy($file_path,$save_path);
		@chmod($save_path,0707);
		return true;
	}

    if($imageObj[2] == 1) {
		$im = imagecreatefromgif($file_path);
	} elseif($imageObj[2] == 2) {
		$im = imagecreatefromjpeg($file_path);
	} elseif($imageObj[2] == 3) {
		$im = imagecreatefrompng($file_path);
	}
    
	if($target == "width") {
		$width	= $max_size;
		$height	= ($imageObj[1]*$max_size) / $imageObj[0];
	}
	else if($target == "height") {
		$width	= ($imageObj[0]*$max_size) / $imageObj[1];
		$height = $max_size;
	}
	else {
		if($imageObj[0] > $imageObj[1]){
			$width	= $max_size;
			$height	= ($imageObj[1]*$max_size) / $imageObj[0];
		} else 
		if($imageObj[0] < $imageObj[1]){
			$width	= ($imageObj[0]*$max_size) / $imageObj[1];
			$height = $max_size;
		} else 
		if($imageObj[0] == $imageObj[1]){
			$width	= $max_size;
			$height = $max_size;
		}	
	}

	$sheet = imagecreatetruecolor($width, $height);
	imagecopyresampled($sheet, $im, 0, 0, 0, 0, $width, $height, $imageObj[0], $imageObj[1]);
	if($imageObj[2] == 1) {
		imagegif($sheet,$save_path, 90);
	} elseif($imageObj[2] == 2)	{
		imagejpeg($sheet,$save_path, 90);
	} elseif($imageObj[2] == 3) {
		imagepng($sheet,$save_path, 9);
	}
	@chmod($save_path,0707);
	imagedestroy($sheet);
	return true;
	
}

//파일명이 중복되지 않도록 파일명뒤에 "_1" 을 붙여 리턴
function makeUniqFileName($fileDir, $fileName) {
	
	$filePath = $fileDir."/".$fileName;
	if(file_exists($filePath)) {
		
		$fileNameArray 		= explode(".",$fileName);
		if(count($fileNameArray) > 0) {
			$fileExt 		= array_pop($fileNameArray);
			$newFileName 	= implode(".",$fileNameArray)."_1.".$fileExt;			
		} else {
			$newFileName 	= $fileName."_1";
		}
		return 	makeUniqFileName($fileDir, $newFileName);
		
	} else {
		return $fileName;
	}
}
function format_size($size) {
	$sizes = array(" Bytes", " KB", " MB", " GB");
	if ($size == 0) { return('n/a'); } else {
		return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
}



if(!$_SESSION['USERKEY']) {
	$_SESSION['USERKEY'] = substr(md5(time()),0,15);
}


//quick upload
if($_POST["proc_type"] == "quick_up") {

	if(is_uploaded_file($_FILES["up_file"]["tmp_name"])) {

		$tmp_names = explode(".",$_FILES["up_file"]["name"]);
		$fileExt = array_pop($tmp_names);
		$fileExt = strtolower($fileExt);
		if(!in_array($fileExt,$allow_file_ext)) {
			echo "0|upload fail!(not allow file)";
			exit;
		}
		if($allow_image_max_volume && $_FILES['up_file']['size'] > $allow_image_max_volume) {
			echo "0|upload fail: not allow file volume"."\n"."your file size: ".format_size($_FILES['up_file']['size'])."\n"."allow max size: ".format_size($allow_image_max_volume);
			exit;
		}
		$imageObj	= getimagesize($_FILES["up_file"]["tmp_name"]);
		if($imageObj[2] > 0 && $imageObj[2] < 4) {		    
		    
			$img_alt = $org_filename = $_FILES["up_file"]["name"];
			$org_filename_array = explode(".",$org_filename);
			array_pop($org_filename_array);
			$img_title = implode(".",$org_filename_array);
			$filenamebody = $_SESSION['USERKEY']."_".microtime(true).rand(100000,999999);
			$filenamebody = str_replace(".","",$filenamebody);		//. 제거
			$filename = $filenamebody.".".$fileExt;
                        $thumb_filename = "t_".$filename;
                        
			$upfile_path = $upload_path.'/'.$filename;
			$thumb_file_path = $upload_path.'/'.$thumb_filename;
                        
			//작은사이즈 썸네일 생성
			makeThumbnail($_FILES["up_file"]["tmp_name"], $thumb_file_path, 40);
                        

 			//지정된 가로사이즈보다 크면 리사이징한다.
			$allow_image_max_width = (int)$allow_image_max_width;
			if($allow_image_max_width > 0 && $imageObj[0] > $allow_image_max_width) {
                            
				//지정된 가로사이즈보다 크면 리사이징한다.
				makeThumbnail($_FILES["up_file"]["tmp_name"], $upfile_path, $allow_image_max_width,"width");
                                @unlink($_FILES["up_file"]["tmp_name"]);
			    
			} else {

			    if(!move_uploaded_file($_FILES["up_file"]["tmp_name"],$upfile_path)) {
                                    @unlink($_FILES["up_file"]["tmp_name"]);
				    echo "0|upload fail";
				    exit;
			    }
			    
			}
			
			@chmod($upfile_path,0707);

			$fileUrl = $upload_url.'/'.$filename;
			echo "1|".$fileUrl."|".$img_alt."|".$img_title;
			exit;
			
		} else {
                        @unlink($_FILES["up_file"]["tmp_name"]);
			echo "0|upload fail (not image file)";
			exit;	
		}
	} else {
		echo "0|not uploaded file(".$_FILES["up_file"]["tmp_name"].")";
		exit;	
	}

        
} else if($_POST["proc_type"] == "paste_quick_up") {
    
    
    $data = $_POST["up_file"];

    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);

    //임시파일 생성
    $up_file_name = time().'_'.rand(1000000,111111).'.png';
    $up_file_path = '/tmp/'.$up_file_name;
    file_put_contents($up_file_path, $data);
    chmod($up_file_path,0777);
    
    if(file_exists($up_file_path)) {

		if($allow_image_max_volume && filesize($up_file_path) > $allow_image_max_volume) {
			echo "0|upload fail: not allow file volume"."\n"."your file size: ".format_size(filesize($up_file_path))."\n"."allow max size: ".format_size($allow_image_max_volume);
			exit;
		}
		$imageObj	= getimagesize($up_file_path);
		if($imageObj[2] > 0 && $imageObj[2] < 4) {		    
		    
			$img_alt = "image";
			$img_title = "image";
			$filenamebody = $_SESSION['USERKEY']."_".microtime(true).rand(100000,999999);
			$filenamebody = str_replace(".","",$filenamebody);		//. 제거
			$filename = $filenamebody.".png";
                        $thumb_filename = "t_".$filename;
			
			$dest_upfile_path = $upload_path.'/'.$filename;
			$thumb_file_path = $upload_path.'/'.$thumb_filename;
                        
			//작은사이즈 썸네일 생성
			makeThumbnail($up_file_path, $thumb_file_path, 40);                        
                        
                        
			//지정된 가로사이즈보다 크면 리사이징한다.
			$allow_image_max_width = (int)$allow_image_max_width;
			if($allow_image_max_width > 0 && $imageObj[0] > $allow_image_max_width) {
                            
                                //지정된 가로사이즈보다 크면 리사이징한다.
                                makeThumbnail($up_file_path, $dest_upfile_path, $allow_image_max_width,"width");     
                                
			} else {

			    if(!copy($up_file_path,$dest_upfile_path)) {
                                    //업로드된 원본파일 삭제
                                    @unlink($up_file_path);
                                    echo "0|upload fail";
                                    exit;
			    }
			    
			}
                        
                        //업로드된 원본파일 삭제
                        @unlink($up_file_path);
			
                        //파일권한 부여
			@chmod($dest_upfile_path,0707);
                        
                        $fileUrl = $upload_url.'/'.$filename;
			echo "1|".$fileUrl."|".$img_alt."|".$img_title;
			exit;
			
		} else {
                        //업로드된 원본파일 삭제
                        @unlink($up_file_path);
                        
			echo "0|upload fail (not image file)";
			exit;	
		}
	} else {
		echo "0|not uploaded file";
		exit;	
	}    
    
    
    
    
    
    
    
    
    
    
    
    
    
//quick delete
} else if($_GET["proc_type"] == "quick_del") {

	$filename = $_GET['filename'];
	$servefilename = $_SESSION['USERKEY']."_".$filename;
	$servefilepath = $upload_path."/".$servefilename;
	if(@unlink($servefilepath)) {

		$fileUrl = $upload_url.'/'.$servefilename;
		echo "1|".$fileUrl;

	} else {
		echo "0|error";
	}
	exit;

//WIC get Folder list
} else if($_POST["proc_type"] == "wic_get_folder_list") {
	
	$parent_folder = trim($_POST["parent_folder"]);

	// 점 두개이상 연속되어 있으면 => 없애기
	$parent_folder = preg_replace("/\.\.+/i","",$parent_folder);

	// 슬래시 두개이상 연속되어 있으면 => 한개로 
	$parent_folder = preg_replace("/\/+/i","/",$parent_folder);

	//마지막 슬래시 => 없애기
	$parent_folder = preg_replace("/\/+$/","",$parent_folder);

	//처음 슬래시 => 없애기
	$parent_folder = preg_replace("/^\/+/","",$parent_folder);


	$curr_folder = $wic_upload_path;
	if($parent_folder != "") {
		$curr_folder = $wic_upload_path.$parent_folder;
	}

	//$thumb_dir_name
	
	if(!is_dir($curr_folder)) {
		echo "0|".$curr_folder." folder is not exists";
		exit;
	}
	
	//폴더 열어서 하위 폴더 목록 가져오기
	$folder_list_array = array();
	$folder_count = 0;
	if ($handle = opendir($curr_folder)) {
		while (false !== ($dir_name = readdir($handle))) {
			if($dir_name != '.' && $dir_name != '..' && is_dir($curr_folder."/".$dir_name) && !preg_match('/^_/',$dir_name)) {
				$folder_list_array[]	= $dir_name;
				$folder_count++;
			}
		}
		closedir($handle); 
		$handle = null;
		asort($folder_list_array);
                
		$folder_list = "";
                $sub_folder_ok_array = array();
		if($folder_count) {
                    
                        //하위 폴더를 연다.
                        $sub_curr_folder = "";
                        foreach($folder_list_array as $key => $value) {
                            
                            $sub_curr_folder = $curr_folder."/".$value;                                                    
                            if ($subHandle = opendir($sub_curr_folder)) {
                                
                                $sub_foler_ok = "0";
                                while (false !== ($sub_dir_name = readdir($subHandle))) {
                                    
                                    if($sub_dir_name != '.' && $sub_dir_name != '..' && is_dir($sub_curr_folder."/".$sub_dir_name) && !preg_match('/^_/',$sub_dir_name)) {
                                            $sub_foler_ok = "1";
                                            break;
                                    }

                                }
                                $sub_folder_ok_array[] = $sub_foler_ok;

                                closedir($subHandle);
                                $subHandle = null;
                                
                            }
                            
                        }
                    
			$folder_list            = implode(",",$folder_list_array);
                        $sub_folder_ok_list     = implode(",",$sub_folder_ok_array);
		} else {
			$folder_list            = "empty";
                        $sub_folder_ok_list     = "empty";
		}
		echo "1|".$folder_count."|".$folder_list."|".$sub_folder_ok_list;
	} else {
		echo "0|folder open error";
	}	
	exit;

	
//WIC Get FileList
} else if($_POST["proc_type"] == "wic_get_file_list") {	
	
	$user_file_path = $wic_upload_path."/".$_POST["file_path"];
	$user_file_path = checkDirPath($user_file_path);
	

	//폴더가 서버에 존재하지않으면 바로 종료
	if(!is_dir($user_file_path)) {
		echo "0|Folder exists";
		exit;
	}	

	//폴더 열어서 하위 폴더 목록 가져오기
	$file_list_array = array();
	$file_count = 0;
	if ($handle = opendir($user_file_path)) {
		while (false !== ($file_name = readdir($handle))) {
			if( $file_name != '.' && $file_name != '..' && is_file($user_file_path."/".$file_name) ) {
				
				//썸네일 정의
				$thumb_file_name = "t_".$file_name;
				$thumb_file_dir = $user_file_path."/".$thumb_dir_name."/";
				$thumb_file_path = $thumb_file_dir.$thumb_file_name;
				
				//썸네일이 있으면 썸네일파일 URL생성
				if(file_exists($thumb_file_path)) {					
					$thumbUrl = $wic_upload_uri.checkDirPath($_POST["file_path"])."/".$thumb_dir_name."/".$thumb_file_name;
					$thumbUrl = checkDirPath($thumbUrl);
									
		
				//썸네일이 없으면 건너띈다.
				} else {
					continue;
				}
								
				$fileUrl = $wic_upload_uri.checkDirPath($_POST["file_path"])."/".$file_name;
				$fileUrl = checkDirPath($fileUrl);
				
				$fileVolume 	= format_size(filesize($user_file_path."/".$file_name));
				
				$imgObj			= getimagesize($user_file_path."/".$file_name);
				$fileWidth		= $imgObj[0];
				$fileHeight		= $imgObj[1];
				$fileMime		= $imgObj['mime'];
				
				
				$file_list_array[]	= $thumbUrl."*".$fileUrl."*".$fileVolume."*".$fileWidth."*".$fileHeight."*".$fileMime;
				$file_count++;
			}
		}
		closedir($handle);
		$handle = null;
		asort($file_list_array);
	
		$file_list = "";
		if(count($file_list_array)) {
			$file_list = implode(",",$file_list_array);
		} else {
			$file_list = "empty";
		}
		echo "1|".$file_count."|".$file_list;
	} else {
		echo "0|folder open error";
	}	
	
	
	exit;
	
	
//WIC Make Folder
} else if($_POST["proc_type"] == "wic_mkdir") {

        if(!checkFolderName($_POST["dir_path"])) {
            echo "0|folder name error";
            exit;
        }

	$dir_path = $wic_upload_path."/".$_POST["dir_path"];
	$dir_path = checkDirPath($dir_path);

	if(is_dir($dir_path)) {
		echo "0|Folder exists";
	}
	elseif (mkdir($dir_path) && chmod($dir_path,0777) && mkdir($dir_path."/".$thumb_dir_name) && chmod($dir_path."/".$thumb_dir_name,0777)) {

		echo "1|".$_POST["dir_path"];

	} else {
		echo "0|folder make error";
	}

	exit;	
	
//WIC Delete Folder
} else if($_POST["proc_type"] == "wic_rmdir") {

	$dir_path = $wic_upload_path."/".$_POST["dir_path"];
	$dir_path = checkDirPath($dir_path);

	if(!is_dir($dir_path)) {
		echo "0|Folder Not Exists";
                exit;
	}

        @rmdirAll($dir_path);
        
        echo "1|success";
	exit;        
        
//WIC File Upload
} else if($_POST["proc_type"] == "wic_file_up") {
	
	$up_folder_path = $wic_upload_path."/".$_POST["up_folder_path"];
	$up_folder_path = checkDirPath($up_folder_path);
	
	$upfile_count = count($_FILES['upfile']['name']);
	
	$uploaded_count = 0;
	$uploaded_filenames = array();
	for($i = 0; $i < $upfile_count; $i++) {
	
		if(is_uploaded_file($_FILES["upfile"]["tmp_name"][$i])) {
		
			
			
			$filename = $_FILES["upfile"]["name"][$i];
			$tmp_names = explode(".",$filename);
			$fileExt = array_pop($tmp_names);
			$fileExt = strtolower($fileExt);
			
			$filenamebody 	= implode(".",$tmp_names);
			$tmp_names = null;
			unset($tmp_names);

		
			//파일확장자 검사			
			if(!in_array($fileExt,$allow_file_ext)) {
				echo "0|upload fail!(not allow file)";
				exit;
			}
			
			//파일용량검사
			if($allow_image_max_volume && $_FILES['upfile']['size'][$i] > $allow_image_max_volume) {
				echo "0|upload fail!(not allow file volume: Max ".$allow_image_max_volume."byte)";
				exit;
			}
			

			//업로드될 파일경로
			$upfile_path = $up_folder_path.'/'.$filename;
				
			//파일중복시 처리
			if(file_exists($upfile_path)) {
				
				//파일명 자동 리네임 체크
				if($wic_file_auto_rename == true) {
					
					//파일명 재설정
					$filename = makeUniqFileName($up_folder_path,$filename);

					//업로드될 파일경로 재설정
					$upfile_path = $up_folder_path.'/'.$filename;
				
				//중복파일 허용 안함
				} else {
					echo "0|upload fail!('".$filename."' file exists!)";					
					exit;
				}
				
			}
			
			$imageObj	= getimagesize($_FILES["upfile"]["tmp_name"][$i]);
			if($imageObj[2] > 0 && $imageObj[2] < 4) {
		
				//지정된 가로사이즈보다 크면 리사이징한다.
				$allow_image_max_width = (int)$allow_image_max_width;
				if($allow_image_max_width > 0 && $imageObj[0] > $allow_image_max_width) {
					
				//파일 사이즈 제한 사이즈로 줄이기
				makeThumbnail($_FILES["upfile"]["tmp_name"][$i], $upfile_path, $allow_image_max_width);
                                        
                                        //업로드된 원본 파일 삭제
				@unlink($_FILES["upfile"]["tmp_name"][$i]);
						
				//지정된 사이즈보다 작으면 리사이징 하지 않고 그대로 파일만 이동한다.
				} else {
		
					if(!move_uploaded_file($_FILES["upfile"]["tmp_name"][$i],$upfile_path)) {
                                                @unlink($_FILES["upfile"]["tmp_name"][$i]);
						echo "0|upload fail";
						exit;
					}
						
				}
				@chmod($upfile_path,0707);
				
				
				
				//썸네일생성
				$thumb_file_name = "t_".$filename;
				$thumb_file_dir = $up_folder_path."/".$thumb_dir_name."/";
				$thumb_file_path = $thumb_file_dir.$thumb_file_name;
				if(makeThumbnail($upfile_path, $thumb_file_path, $thumb_max_size)) {
					$fileUrl = $wic_upload_uri.checkDirPath($_POST["up_folder_path"])."/".$thumb_dir_name."/t_".$filename;
					$fileUrl = checkDirPath($fileUrl);
				} else {
					$fileUrl = $wic_upload_uri.checkDirPath($_POST["up_folder_path"])."/".$filename;
					$fileUrl = checkDirPath($fileUrl);
				}

				$uploaded_count++;
				$uploaded_fileurls[] = $fileUrl;
				
		
			} else {
				echo "0|upload fail (not image file)";
				exit;
			}
			
		}
		
	}

        echo "1|".$uploaded_count."|".implode(",",$uploaded_fileurls);
	exit;
			
	
	
//WIC File Delete
} else if($_POST["proc_type"] == "wic_file_delete") {

	//실제파일 삭제
	$user_file_path = $wic_upload_path."/".$_POST["file_path"];
	$user_file_path = checkDirPath($user_file_path);
	
	if(file_exists($user_file_path)) {		
		if(!unlink($user_file_path)) {			
			echo "0|delete fail ('".$_POST["file_path"]."' is not exists)";
			exit;
		}		
	}
	
	//썸네일 삭제
	$user_file_path_array = explode("/",$user_file_path);
	$user_file_name = array_pop($user_file_path_array);
	$user_file_dir = implode("/",$user_file_path_array);
	
	$user_thumb_file_name = "t_".$user_file_name;
	$user_thumb_file_path = $user_file_dir."/".$thumb_dir_name."/".$user_thumb_file_name;
	
	//썸네일은 삭제실패무시
	if(file_exists($user_thumb_file_path)) {
		@unlink($user_thumb_file_path);
	}	
	
	
	echo "1|success";	
	exit;
	

    
		
//else
} else {
	echo "0|unknown error";
	exit;	
}
?>