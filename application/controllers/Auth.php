<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends MY_Controller {
  function __construct(){       
    parent::__construct();
    $this->load->model('user_model'); 
  }
  // 로그인 버튼 눌렀을 때 ajax로 가입여부 체크
  function loginCheck(){
    $request_body = file_get_contents('php://input');
    $info = json_decode(stripcslashes($request_body), true);
    $userEmail = $info['findemail'];
    $userPw = $info['findpw'];
    $countEmail = 0;
    $emailConfirm = 0;
    $loginCheck = 0;
    $q_result = $this->user_model->getByEmail(array('searchEmail'=>$userEmail));
    if($q_result){
      $countEmail = 1;
      if($userEmail == $q_result->email && password_verify($userPw, $q_result->password)) {
        $loginCheck = 1;
      }
    // 이메일 인증 확인
      if($q_result->email_check == 1){
        $emailConfirm = 1;
      }
    }

    $returnArr = new stdClass();
    $returnArr->count = $countEmail;
    $returnArr->emailConfirm = $emailConfirm;
    $returnArr->loginCheck = $loginCheck;
    echo json_encode($returnArr);
  }
  // 로그인시 서버 인증
  function authentication(){
    $user = $this->user_model->getByEmail(array('searchEmail'=>$this->input->post('userEmail')));
    if($this->input->post('userEmail') == $user->email && password_verify($this->input->post('userPassword'), $user->password)) {
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
  // 로그 아웃
  function logout(){
    $this->session->sess_destroy();
    redirect('/');
  }
  // 회원가입
  function register(){
    $this->_header();
    $this->load->library('form_validation');
    $this->form_validation->set_rules('joinEmail', '이메일', 'required|valid_email|is_unique[user.email]');
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
      $this->session->set_flashdata('message', '회원가입에 성공했습니다.\\n메일을 확인해 주세요');
      $user = $this->user_model->getById(array('searchId'=>$userId));
      // 세션 세팅
      // $this->userSessionSet($user);
      // 인증 메일 보내기
      $this->user_model->sendConfirmMail($user);
      redirect('/');
    }
    $this->_footer();
  }
  // 회원인증 메일 승인
  function joinconfirm(){
    // $userId = $_GET['a'];
    // $userCreated = $_GET['b'];
    $userId = $this->input->get('a');
    $userCreated = $this->input->get('b');
    $sendData = array('userId'=>$userId, 'userCreated'=>$userCreated);
    $this->user_model->userConfirm($sendData);
    redirect('/');
  }
}
?>