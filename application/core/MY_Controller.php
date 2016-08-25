<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
	function __construct(){
		parent::__construct();
		date_default_timezone_set('GMT');
		date_default_timezone_set('Asia/Seoul');

		$this->load->database();  // 이미 config.php 에서 $config['sess_driver'] = 'database'; 로 db로드 완료
		
		if(!$this->input->is_cli_request()){
			$this->load->library('session');
		}

		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}

	// 페이지의 헤더 부분
	function _header(){
		$this->load->config('nowDev');
		// 유저 한번 더 로드 해서 세션 저장
		if($this->session->userdata('is_login')){
			$this->load->model('user_model');
			$user = $this->user_model->getByEmail(array('searchEmail'=>$this->session->userdata('userEmail')));
			$this->userSessionSet($user);
		}
		$this->load->view('common/head');
		$this->load->view('common/loginModal');
	}
	function _sidebar(){
		// 페이지 부분 캐쉬
		if ( ! $topics = $this->cache->get('topics')){
			$topics = $this->gb_model->gets();
			$this->cache->save('topics', $topics, 300);
		}
		$this->load->view('topic_list', array('topics'=>$topics));
	}

	// 페이지의 아래 부분
	function _footer(){
		$this->load->view('common/foot');
	}

	// 액션 순간 로그인(세션) 체크 메세지 출력하고 현재 사이트로 redirection
	function _require_login($return_url){
		if(!$this->session->userdata('is_login')){
			$this->session->set_flashdata('message', '로그인이 필요합니다.');
			redirect($return_url);
		}
	}

	// 유저 세션 세팅
	function userSessionSet($user){
		$this->session->set_userdata('is_login', true);
		$this->session->set_userdata('userId', $user->id);
		$this->session->set_userdata('userNm', $user->nickname);
		$this->session->set_userdata('userEmail', $user->email);
		$this->session->set_userdata('userFlower', $user->flower);
		$this->session->set_userdata('userGetFlower', $user->get_flower);
		$this->session->set_userdata('userTodayFlower', $user->today_flower);
	}
}
?>