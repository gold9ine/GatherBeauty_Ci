<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Batch extends MY_Controller {
    function __construct(){
        parent::__construct();
    }
    function process(){
        $this->load->model('batch_model');
        $queue = $this->batch_model->gets();
        foreach($queue as $job){
            switch($job->job_name){
                case 'notify_email_add_topic':
                    $context = json_decode($job->context);
                    $this->load->model('gb_model');
                    $topic = $this->gb_model->get($context->topic_id);
                    $this->load->model('user_model');
                    $users = $this->user_model->gets();     
                    $this->load->library('email');
                    $this->email->initialize(array('mailtype'=>'html'));
                    foreach($users as $user){
                        $this->email->from('master@ooo2.org', 'master');
                        $this->email->to($user->email);
                        $this->email->subject($topic->title);
                        $this->email->message($topic->description);
                        $this->email->send();
                        echo "{$user->email}로 메일 전송을 성공 했습니다.\n";
                    }
                    $this->batch_model->delete(array('id'=>$job->id));
                    break;
            }
        }
    }
    
    function processEmail(){
        // 메일전송
        $this->load->model('user_model');
        $users = $this->user_model->gets();

        $this->load->library('email');
            // 전송할 데이터가 html 문서임을 옵션으로 설정
        $this->email->initialize(array('mailtype'=>'html'));
        foreach($users as $user){
            $this->email->clear();
            // 송신자의 이메일과 이름 정보
            $this->email->from('gold9ine@naver.com', 'nickname'); 
            // 이메일 제목
            $this->email->subject('글을 발행 됐습니다.');
            // 이메일 본문
             $this->email->message('test'); 
            // $this->email->message('<a href="'.site_url().'topic/get/'.$topic_id.'">'.$this->input->post('title').'</a>');
            // 이메일 수신자.
            $this->email->to($user->email);
            // 이메일 발송
            $this->email->send();
            // echo $this->email->print_debugger();

            echo "{$user->email}로 메일 전송을 성공 했습니다.\n";
        }   

    }
}
?>