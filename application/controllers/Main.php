<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Main extends MY_Controller {
	function __construct(){       
		parent::__construct();   
		$this->load->model('gb_model'); 
	}
	// 메인 호출
	function index(){
		$this->_header();
		$this->_sidebar();
		$this->load->view('main/main');
		$this->_footer();
	}
	// 404 에러 페이지
	function notfound(){
		// $this->_header();
		$this->output->set_status_header('404'); 
        $data['content'] = 'error_404'; // View name 
        $this->load->view('/errors/404page',$data);
        // $this->_footer();
    }
    // 기본 topic get
	function get($id){
		log_message('debug', 'get 호출');
		$this->_header();
		$this->_sidebar();
		$topic = $this->gb_model->get($id);
		if(empty($topic)){
			// 캐쉬 삭제
			$this->cache->delete('topics');
			log_message('error', 'topic의 값이 없습니다');       
			show_error('topic의 값이 없습니다');
		}
		$this->load->helper(array('url', 'HTML', 'korean'));
		log_message('debug', 'get view 로딩');
		$this->load->view('get', array('topic'=>$topic));
		log_message('debug', 'footer view 로딩');
		$this->_footer();
	}
	// topic 삭제
	function delete(){
		$returnURL = $this->input->get('returnURL');
		$topic_id = $this->input->post('topic_id');
		//혹시나 모를 로그인이 안되어있을때 메세지 출력하고 현재 사이트로 redirection
		$this->_require_login($returnURL);
		$this->load->model('gb_model');
		$this->gb_model->delete($topic_id);

		// 캐쉬 삭제
		$this->cache->delete('topics');
		
		redirect('/');
	}
	// topic add
	function add(){
		// 로그인 필요
		$this->_header();
		$this->_sidebar();
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', '제목', 'required');
		$this->form_validation->set_rules('description', '본문', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('add');
		}
		else
		{
			$topic_id = $this->gb_model->add($this->input->post('title'), $this->input->post('description'));

			// Batch Queue에 notify_email_add_topic 추가
			$this->load->model('batch_model');
			$this->batch_model->add(array('job_name'=>'notify_email_add_topic', 'context'=>json_encode(array('topic_id'=>$topic_id))));

			// 캐쉬 삭제
			$this->cache->delete('topics');

			redirect('/main/get/'.$topic_id);
		}
		$this->_footer();
	}
	// ck editor 이미지 업로드
	function upload_receive_from_ck(){
        // 사용자가 업로드 한 파일을 /static/user/ 디렉토리에 저장한다.
		$config['upload_path'] = './uploads';
        // gif,jpg,png 파일만 업로드를 허용한다.
		$config['allowed_types'] = 'gif|jpg|png';
        // 허용되는 파일의 최대 사이즈
		$config['max_size'] = '100';
        // 이미지인 경우 허용되는 최대 폭
		$config['max_width']  = '1024';
        // 이미지인 경우 허용되는 최대 높이
		$config['max_height']  = '768';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload("upload"))
		{
			echo "<script>alert('업로드에 실패 했습니다. ".$this->upload->display_errors('','')."')</script>";
		}   
		else
		{
			$CKEditorFuncNum = $this->input->get('CKEditorFuncNum');
			$data = $this->upload->data();            
			$filename = $data['file_name'];

			$url = '/uploads/'.$filename;
			echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$CKEditorFuncNum."', '".$url."', '전송에 성공 했습니다')</script>";         
		}
	}
	
	function upload_receive(){
		// 사용자가 업로드 한 파일을 /static/user/ 디렉토리에 저장한다.
		$config['upload_path'] = './uploads';
		// gif,jpg,png 파일만 업로드를 허용한다.
		$config['allowed_types'] = 'gif|jpg|png';
		// 허용되는 파일의 최대 사이즈
		$config['max_size'] = '100';
		// 이미지인 경우 허용되는 최대 폭
		$config['max_width']  = '1024';
		// 이미지인 경우 허용되는 최대 높이
		$config['max_height']  = '768';
		$this->load->library('upload', $config);

		if(!$this->upload->do_upload("user_upload_file")){
			echo $this->upload->display_errors();
		}else{
			$data = array('upload_data'=>$this->upload->data());
			echo "성공";
			var_dump($data);
		}
	}
	
	function upload_form(){
		$this->_header();
		$this->_sidebar();
		$this->load->view('upload_form');
		$this->_footer();
	}
}
?>