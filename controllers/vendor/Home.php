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
    
         if ($this->session->userdata('is_vendor_login')) {
            	redirect('vendor/dashboard');
        } else {
        	$this->load->view('vendor/login');
        }
		
		
    }
    
     
   



     public function do_login() {
            
        if ($this->session->userdata('is_vendor_login')) {
            redirect('vendor/home/dashboard');
        } else {
            $email= $_POST['email'];
            $password = $_POST['password'];

            $this->form_validation->set_rules('email', 'email', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('vendor/login');
            } else {
              
                $enc_pass = md5($password);
                $sql = "SELECT * FROM vendors WHERE email = ? AND password = ?";
                $val = $this->db->query($sql,array($email ,$enc_pass));
            
                if ($val->num_rows()) {
                    
                    foreach ($val->result_array() as $res);
                        if ($res['status'] != 0) {
                                                        
                        $this->session->set_userdata(array(
                            'vid' => $res['id'],
                            'name' => $res['name'],
                            'vemail' => $res['email'],
                            'category' => $res['category_id'],
                            'vstatus' => $res['status'],
                            'is_vendor_login' => true,
                            'success' => '2',
                            'success_deliver' => '2',
                            )
                        );
                    
                    $mydate = getdate(date("U"));
                   
                   if($mydate[mday]==15||$mydate[mday]==30){
                   unlink('application/logs/newfile.log');
                   $logFileName = 'application/logs/newfile.log';
             $logContent = "Vendor logged in at  ";
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
             $logContent = "vendor logged in at  ";
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
            foreach ($val->result_array() as $key1);    
            if($key1['status'] == 2){
                redirect('vendor/vendorprofile');
            }
            elseif($key1['category_id'] == 0){
                redirect('vendor/categories/index1');
            }
            else{
                redirect('vendor/dashboard');
            }
        }else{
            $err['error'] = '<strong>Account Disabled </strong>Please Contact Admin';
            $this->load->view('vendor/login', $err);
        }
            
                } else {
                   
                        $err['error'] = '<strong>Access Denied</strong> Invalid Username/Password';
                    
                    $this->load->view('vendor/login', $err);
                }
        }
            }
        }
  
          //$this->output->enable_profiler(TRUE);
          


        
    public function logout() {
        $this->session->unset_userdata('vid');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('vemail');
        $this->session->unset_userdata('category');
        $this->session->unset_userdata('vstatus');
        $this->session->unset_userdata('is_vendor_login');
        $this->session->unset_userdata('success');   
        $this->session->unset_userdata('success_deliver');


        //$this->session->sess_destroy();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        $logFileName = 'application/logs/newfile.log';
			 $logContent = "vendor logged out at  ".PHP_EOL;
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
        redirect('vendor/home', 'refresh');
    }

    

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */