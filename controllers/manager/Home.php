<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
		
		
    }

    public function index() {
         if ($this->session->userdata('is_manager_login')) {
        
        
            redirect('manager/dashboard');
        } else {
        $this->load->view('manager/login');
        }
		
		
    }

     public function do_login() {
    			
        if ($this->session->userdata('is_manager_login')) {
            redirect('manager/home/dashboard');
        } else {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $this->form_validation->set_rules('email', 'email', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('manager/login');
            } else {
              
                $enc_pass  = md5($password);
               
			    $sql = "SELECT * FROM managers WHERE email = ? AND password = ?";
               
			   
			    $val = $this->db->query($sql,array($email ,$enc_pass ));

                if ($val->num_rows()) {
                    foreach ($val->result_array() as $recs => $res) {
                        $this->session->set_userdata(array(
                            'mid' => $res['id'],
                            'mname' => $res['name'],
                            'memail' => $res['email'],
                            'mcategory' => $res['category_id'],
                            'm_success' => '2', 
                            'm_success_status' => '2',
                            'm_success_deliver' => '2', 
                            'm_success_quality' => '2',
                            'is_manager_login' => true
                        ));
                    }
                    $mydate=getdate(date("U"));
                    if($mydate[mday]==15||$mydate[mday]==30){
                        unlink('application/logs/newfile.log');
                        $logFileName = 'application/logs/newfile.log';
			 $logContent = "Manager logged in at  ";
			 $space = "                                          ".PHP_EOL;
			 $spacee = "                                          ".PHP_EOL;
			 
			 $date = new DateTime();
                         $date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')) 
			{
			  fwrite($handle, $logContent);
			  fwrite($handle, $date);
			  fwrite($handle, $space);
			  fwrite($handle, $spacee);
			    }
			     fclose($handle);
			
                   }else{
                         $logFileName = 'application/logs/newfile.log';
			 $logContent = "Manager logged in at  ";
			 $space = "                                          ".PHP_EOL;
			 $spacee = "                                          ".PHP_EOL;
			 
			 $date = new DateTime();
                         $date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')) 
			{
			  fwrite($handle, $logContent);
			  fwrite($handle, $date);
			  fwrite($handle, $space);
			  fwrite($handle, $spacee);
			    }
			     fclose($handle);
			 }
                    
                   	     
                    redirect('manager/dashboard');
                    
                } else {
                    $err['error'] = '<strong>Access Denied</strong> Invalid Username/Password';
                    $this->load->view('manager/login', $err);
                }
            }
        }
  
  		//$this->output->enable_profiler(TRUE);
  		
    }

        
    public function logout() {
        $this->session->unset_userdata('mid');
        $this->session->unset_userdata('mname');
        $this->session->unset_userdata('memail');
        $this->session->unset_userdata('mcategory');
        $this->session->unset_userdata('m_success'); 
        $this->session->unset_userdata('m_success_status');   
        $this->session->unset_userdata('m_success_deliver');   
        $this->session->unset_userdata('m_success_quality'); 
        $this->session->unset_userdata('is_manager_login');   

        //$this->session->sess_destroy();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        
        $logFileName = 'application/logs/newfile.log';
			 $logContent = "Manager logged out at  ".PHP_EOL;
			 $space = "                                          ".PHP_EOL;
			 $date = new DateTime();
                         $date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')) 
			{
			  fwrite($handle, $logContent);
			  fwrite($handle, $date);
			  fwrite($handle, $space);
			    }
			     fclose($handle);
        redirect('manager/home', 'refresh');
    }

    

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */