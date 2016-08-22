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
	function _header(){
		$this->load->config('nowDev');
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
	function _footer(){
		$this->load->view('common/foot');
	}
	function _require_login($return_url){
		if(!$this->session->userdata('is_login')){
			$this->session->set_flashdata('message', '로그인이 필요합니다.');
			redirect($return_url);
		}
	}
	function userSessionSet($user){
		$this->session->set_userdata('is_login', true);
		$this->session->set_userdata('userId', $user->id);
		$this->session->set_userdata('userNm', $user->nickname);
		$this->session->set_userdata('userEmail', $user->email);
		$this->session->set_userdata('userFlower', $user->flower);
		$this->session->set_userdata('userTodayFlower', $user->today_flower);
	}
}
?>