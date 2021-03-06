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
         if (!$this->session->userdata('is_manager_login')) {
            redirect('manager/home');
        }
		
		$this->load->model('manager/left_model');
		
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
		
		
		$this->load->view('manager/dashboard',$data);
		
		
    }
    
    	public function read($id = NULL) {
    
    	$this->left_model->read($id);
    	
    }   
    
    public function allread() {
    
    	$this->left_model->allread();
    	redirect('manager/dashboard');
    }
    
     public function checkall() {
    
	  
	    $data['page']='timeline';
        
		$data['menu'] = $this->left_model->get_menu();
		$data['notification']= $this->left_model->allnotifications();
		
		$this->load->view('manager/timeline',$data);
		
		
    }












    
    

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */