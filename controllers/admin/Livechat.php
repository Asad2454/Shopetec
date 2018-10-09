<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Livechat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/livechat_model');
        $this->load->library('pagination');
        $this->perPage = 2;
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
	$data = array();
	$data['page']='state';
	$data['menu'] = $this->left_model->get_menu();	
	$data['livechat'] = $this->livechat_model->get_livechat();
	$this->load->view('admin/editlivechat', $data);
    }
    
   
   
    
    
    
    public function update($id = NULL) {	
		
		
		
		
				$data = array(
	  				
					'chat_code'=>$this->input->post('chat_code')
					
				);
				
				
				
				$this->livechat_model->update_livechat($data,$id);
				
			
			
		
		
               $this->session->set_userdata(array(
	       			'a_success' => '1')
			); 
			redirect("admin/livechat/");	
    }





    
    

}