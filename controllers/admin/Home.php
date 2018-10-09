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
        
         if ($this->session->userdata('is_admin_login')) {
            redirect('admin/dashboard');
        } else {
        $this->load->view('admin/login');
        }
		
		
    }

     public function do_login() {
			
        if ($this->session->userdata('is_admin_login')) {
            redirect('admin/home/dashboard');
        } else {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('admin/login');
            } else {
                
                $enc_pass  = md5($password);
               
			    $sql = "SELECT * FROM admin WHERE email= ? AND password = ?";
               
			   
			    $val = $this->db->query($sql,array($email ,$enc_pass ));

                if ($val->num_rows()) {
                    foreach ($val->result_array() as $recs => $res) {
                        $this->session->set_userdata(array(
                            'id' => $res['id'],
                            'username' => $res['username'],
                            'email' => $res['email'],
                            'is_admin_login' => true,
                            'user_type' => $res['user_type'],
                            'a_success' => '2',
                            'a_success_deliver' => '2',
                            'a_success_status' => '2'
                           )
                        );
                    }
                     $mydate=getdate(date("U"));
                   
                   if($mydate[mday]==15||$mydate[mday]==30){
                   unlink('application/logs/newfile.log');
                   $logFileName = 'application/logs/newfile.log';
			 $logContent = "Admin logged in at  ";
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
			 $logContent = "Admin logged in at  ";
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
                    

                    redirect('admin/dashboard');
                } else {
                    $err['error'] = '<strong>Access Denied</strong> Invalid Username/Password';
                    $this->load->view('admin/login', $err);
                }
            }
        }
  
  		//$this->output->enable_profiler(TRUE);
    }

        
    public function logout() {
        $this->session->unset_userdata('id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('user_type');
        $this->session->unset_userdata('is_admin_login');
        $this->session->unset_userdata('a_success');   
        $this->session->unset_userdata('a_success_deliver');   
        $this->session->unset_userdata('a_success_status');   

        //$this->session->sess_destroy();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        
			                    $logFileName = 'application/logs/newfile.log';
			 $logContent = "admin  logout at  ".PHP_EOL;
			
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
        redirect('admin/home', 'refresh');
    }



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */