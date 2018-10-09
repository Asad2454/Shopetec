<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Emails extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/emails_model');
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
		
		$this->load->model('admin/left_model');
		
		function objectToArray($d) {
			
			if (is_object($d)) {
				$d = get_object_vars($d);
			}
			
			if (is_array($d)) {
				return array_map(__FUNCTION__, $d);
			}
			else {
				return $d;
			}
			
		}		
		
		
    }

    public function index() {
	  
	    $data['page'] = 'viewemail';
        
		$data['menu'] = $this->left_model->get_menu();
		
		$data['email'] = $this->emails_model->get_all_emails();
		
		$this->load->view('admin/viewemail', $data);
		
		
    }
    
    public function update1($id=NULL){
    
                $data['page'] = 'emails';
        
		$data['menu'] = $this->left_model->get_menu();
       $data['email']=$this->emails_model->get_single_email($id);
       $this->load->view('admin/emails',$data);
    
    }
     public function update($id = NULL) {	
		
			
			
				$data = array(
	  				
					'sender_email'=>$this->input->post('sender_email'),
					'sender_name'=>$this->input->post('sender_name'),
					'Subject'=>$this->input->post('Subject'),
					'body'=>$this->input->post('body')
					
				);
			
				$this->emails_model->update_email($data,$id);
				
			
		$logFileName = 'application/logs/newfile.log';
			 $logContent = "admin updated Email managment at  ".PHP_EOL;
			 $space = "                                          ".PHP_EOL;
			 $date = new DateTime();
                         $date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')) {
			  fwrite($handle, $logContent);
			  fwrite($handle, $date);
			  fwrite($handle, $space);
			}
			fclose($handle);
		header('location:'.base_url()."admin/emails/index?msg=success");
	}
     public function update_status_email($id){
      
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $this->emails_model->status_update($id,$data);
       $this->session->set_userdata(array(
		'a_success_status' => '1'));
    }
    
    
}