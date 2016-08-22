<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends MY_Controller {
  function __construct()
  {       
    parent::__construct();
    $this->load->model('user_model'); 
  }
  function loginCheck(){
    $request_body = file_get_contents('php://input');
    $info = json_decode(stripcslashes($request_body), true);
    $userEmail = $info['findemail'];
    $userPw = $info['findpw'];
    $countEmail = 0;
    // $join_confirm_check = 0;
    $emailConfirm = 1; // 이메일 인증
    $loginCheck = 0;
    $q_result = $this->user_model->getByEmail(array('searchEmail'=>$userEmail));
    if($q_result){
      $countEmail = 1;
      if($userEmail == $q_result->email && password_verify($userPw, $q_result->password)) {
        $loginCheck = 1;
      }
    // 이메일 인증
    //     $join_confirm_check = $q_result["email_check"];
    //     $confirmKey = $q_result["ck"];
    //     $user_id = $q_result["id"];
    //     $created_at = $q_result["created_at"];
    //     if($join_confirm_check!="1"){
    //         include($_SERVER["DOCUMENT_ROOT"]."/user/confirmmail.php");
    //     }
    }

    $returnArr = new stdClass();
    $returnArr->count = $countEmail;
    $returnArr->emailConfirm = $emailConfirm;
    $returnArr->loginCheck = $loginCheck;
    echo json_encode($returnArr);
  }
  function authentication(){
    $user = $this->user_model->getByEmail(array('searchEmail'=>$this->input->post('userEmail')));
    if($this->input->post('userEmail') == $user->email && password_verify($this->input->post('userPassword'), $user->password)) {
      // $this->session->set_userdata('is_login', true);
      $this->userSessionSet($user);
      $returnURL = $this->input->get('returnURL');
      if($returnURL===false){
        $returnURL = '/';
      }
      redirect($returnURL);
    } else {
      $this->session->set_flashdata('message', '로그인에 실패 했습니다.');
      $returnURL = $this->input->get('returnURL');
      $currentURL = $this->input->get('currentURL');
      if(!$currentURL){
        redirect($returnURL);
      }
      redirect($currentURL);
    }
  }
  function logout(){
    $this->session->sess_destroy();
    redirect('/');
  }
  function register(){
    $this->_header();
    $this->load->library('form_validation');
    $this->form_validation->set_rules('joinEmail', '이메일 주소', 'required|valid_email|is_unique[user.email]');
    $this->form_validation->set_rules('joinNickname', '닉네임', 'required|min_length[4]|max_length[20]');
    $this->form_validation->set_rules('joinPassword', '비밀번호', 'required|min_length[6]|max_length[30]|matches[joinPwConfirm]');
    $this->form_validation->set_rules('joinPwConfirm', '비밀번호 확인', 'required');
    if($this->form_validation->run() === false){
      $this->load->view('common/register');    
    } else {
            // PHP 5.5 이하 버전에서만
      if(!function_exists('password_hash')){
        $this->load->helper('joinPassword');
      }
      $hash = password_hash($this->input->post('joinPassword'), PASSWORD_BCRYPT);
      $userId = $this->user_model->add(array(
        'email'=>$this->input->post('joinEmail'),
        'password'=>$hash,
        'nickname'=>$this->input->post('joinNickname')
        ));
      $this->session->set_flashdata('message', '회원가입에 성공했습니다.');
      $user = $this->user_model->getById(array('searchId'=>$userId));
      $this->userSessionSet($user);
      redirect('/');
    }
    $this->_footer();
  }
}
?>