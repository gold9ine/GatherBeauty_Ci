<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends MY_Model{
  function __construct(){        
    parent::__construct();
  }

    // 유저 추가
  function add($option){
    $this->db->set('email', $option['email']);
    $this->db->set('nickname', $option['nickname']);
    $this->db->set('password', $option['password']);
    $this->db->set('created_at', 'NOW()', false);
    $this->db->insert('user');
    $result = $this->db->insert_id();
    return $result;
  }

    // 유저 전체 정보 가져오기
  function gets(){
    return $this->db->query("SELECT * FROM user")->result();
  }

    // 유저 정보 아이디로 가져오기
  function getById($option){
    return $this->db->get_where('user', array('id'=>$option['searchId']))->row();

  }

    // 유저 정보 이메일로 가져오기
  function getByEmail($option){
    return $this->db->get_where('user', array('email'=>$option['searchEmail']))->row();
  }

    // 유저 정보 닉네임으로 가져오기
  function getByNickname($option){
    return $this->db->get_where('user', array('nickname'=>$option['searchNickname']))->row();
  }

    // 유저 인증 메일 보내기
  function sendConfirmMail($user){
    $fromMail = $this->config->item('dev_recieve_email');
    $userId = $user->id;
    $userCreated = $user->created_at;
    $userEmail = $user->email;
    $confirmUrl = site_url('auth/joinconfirm?a='.rawurlencode($userId).'&b='.rawurlencode($userCreated));
    $message = "<div style='text-align: center;'>
    <h1 style=''><strong>Gather Beauty에 가입해주셔서 감사합니다.</strong></h1><br>
    <h4>아래 버튼을 눌러서 인증을 해주시면 회원가입이 완료됩니다.</h4>
    <br><br>
    <a href='$confirmUrl' target='_blank' style='text-decoration:initial'>
      <button type='button' 
      style='background-image: linear-gradient(to bottom, #13bf58, #0c8c05);            
      border-radius: 10px;                
      box-shadow: 0px 1px 3px #666666;    
      font-family: Arial;    
      color: #ffffff;    
      font-size: 20px;    
      width: 200px;
      height: 60px;
      cursor: pointer;
      ' type='button' class='btn btn-success btn-lg btn-block'>
      <strong>회원가입 인증 !!</strong></button></a>
    </div>";

        // 메일전송
    $this->load->library('email');
        // 전송할 데이터가 html 문서임을 옵션으로 설정
    $this->email->initialize(array('mailtype'=>'html'));

    $this->email->clear();
        // 송신자의 이메일과 이름 정보
    $this->email->from($fromMail, '게더뷰티 관리자'); 
        // 이메일 제목
    $this->email->subject('Gather Beauty 회원가입 이메일 인증');
        // 이메일 본문
    $this->email->message($message); 
        // 이메일 수신자.
    $this->email->to($userEmail);
        // 이메일 발송
    $this->email->send();
  }

  // 유저 승인하고 서버에 유저폴더 만들기
  function userConfirm($option){
    $data = array('email_check' => '1');
    $this->db->where('id', $option['userId']);
    $this->db->update('user', $data); 
  }

  // 임시 비밀번호 해쉬로 DB 저장
  function setPassword($option){
    $data = array('password' => $option['password']);
    $this->db->where('email', $option['email']);
    $this->db->update('user', $data); 
  }

  // 임시 비밀번호 메일로 보내기
  function sendNewPw($option){
    $fromMail = $this->config->item('dev_recieve_email');
    $newPassword = $option['password'];
    $message = "<div style='text-align: center;'>
    <h1 style=''><strong>Gather Beauty를 이용해 주셔서 감사합니다.</strong></h1><br>
    <h4>아래의 임시 비밀 번호로 로그인 하신 후</h4>
    <h4>유저 정보 페이지에서 비밀번호를 수정해 주세요.</h4>
    <br>$newPassword<br>
    <a></a>
    </div>";

        // 메일전송
    $this->load->library('email');
        // 전송할 데이터가 html 문서임을 옵션으로 설정
    $this->email->initialize(array('mailtype'=>'html'));

    $this->email->clear();
        // 송신자의 이메일과 이름 정보
    $this->email->from($fromMail, '게더뷰티 관리자'); 
        // 이메일 제목
    $this->email->subject('Gather Beauty 회원가입 이메일 인증');
        // 이메일 본문
    $this->email->message($message); 
        // 이메일 수신자.
    $this->email->to($option['email']);
        // 이메일 발송
    $this->email->send();
  }
}
?>