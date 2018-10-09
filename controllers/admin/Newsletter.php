<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newsletter extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/newsletter_model');
        $this->load->library('form_validation');
	$this->load->library("pagination");
        $this->perPage = 2;
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
	     	$data['page'] = 'Newsletter';
	     	$data['menu'] = $this->left_model->get_menu();
	     	$data['newsletter'] = $this->newsletter_model->get_newsletter();
	        $this->load->view('admin/newsletter', $data);
	}
	    
	public function update_status(){
	       $data = array('status' => $this->input->post('status') );
	       $id = $this->input->post('id');
	       $this->newsletter_model->status_update($id,$data);
			$this->session->set_userdata(array(
				'a_success_status' => '1')
			); 
	}
}