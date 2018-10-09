<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
		
		$this->load->model('admin/left_model');
		$this->load->model('admin/dashboard_model');
		
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
        
        
	    $data['page']='dashboard';
        
		$data['menu'] = $this->left_model->get_menu();
		
		
		$this->load->view('admin/dashboard',$data);
		
		
    }


    	public function read($id = NULL) {
    
    	$this->left_model->read($id);
    	redirect('admin/vendors');
    }   
    
    public function allread() {
    
    	$this->left_model->allread();
    	redirect('admin/dashboard');
    }

     public function index1() {

	  
	    $data['page']='dashboard';
        
		$data['menu'] = $this->left_model->get_menu();
		
		
		$this->load->view('admin/changepassword',$data);
		
		
    }
    
    
     public function checkall() {
    
	  
	    $data['page']='timeline';
        
		$data['menu'] = $this->left_model->get_menu();
		$data['notification']= $this->left_model->allnotifications();
		
		$this->load->view('admin/timeline',$data);
		
		
    }
    public function changepwd(){
               
               $this->form_validation->set_rules('opas','Old Password','required');
               $this->form_validation->set_rules('npas','New Password','required');
               $this->form_validation->set_rules('cpas','Confirm Password','required|matches[npas]');

             if($this->form_validation->run()!= true)
                      {
                            
                         
                         header('location:'.base_url()."admin/Dashboard/index1?msg=success4");


                      }else{
                      
                      $sql = $this->db->select("*")->from("admin")->where("email",$this->session->userdata('email'))->get();

                      foreach ($sql->result() as $my_info) {

                      $db_password = $my_info->password;
                       $id = $_SESSION['id'];
                      
                     
                      
                      if(md5($this->input->post("opas")) == $db_password){

                            $data =md5($this->input->post("npas"));
                            
                            $this->dashboard_model->update_pass($id,$data);
                            $logFileName = 'application/logs/newfile.log';
			 $logContent = "admin Changed password  at  ".PHP_EOL;
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
                            header('location:'.base_url()."admin/Dashboard/index1?msg=success");
                           

                                   }else{
                                          header('location:'.base_url()."admin/Dashboard/index1?msg=success1");

                    return false;

}
                     
                      }
               }
    }
    
    
   
    









    
    

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */