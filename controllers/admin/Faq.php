<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/categories_model');
        $this->load->model('admin/faq_model');
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
	     	$data['page'] = 'Faq';
	     	$data['menu'] = $this->left_model->get_menu();
	     	$data['faq'] = $this->faq_model->get_faq();
	        $this->load->view('admin/listfaq', $data);
	}
	    
	public function update_status(){
	       $data = array('status' => $this->input->post('status') );
	       $id = $this->input->post('id');
	       $this->faq_model->status_update($id,$data);
			$this->session->set_userdata(array(
				'a_success_status' => '1')
			); 
	}
	    
    	public function index1() {
		$data['page']='addfaq';
	        $data['menu'] = $this->left_model->get_menu();
		$this->load->view('admin/addfaq', $data);
	}
    
     	public function edit_faq($id = NULL) {
		$data['page']='editfaq';
        	$data['menu'] = $this->left_model->get_menu();
		$data['single_faq'] = $this->faq_model->single_faq($id);
		$this->load->view('admin/editfaq', $data);	
    	}



 public function add() {
	$data = array(
		'question'=>$this->input->post('question'),
		'answer'=>$this->input->post('answer'),
		'status'=>'1'
	);
	$this->db->insert('faqs',$data);
	$this->session->set_userdata(array(
           'a_success' => '1')
   	);
	redirect("admin/faq/");	
	}
    
    
     public function update($id = NULL) {	
		
		$data = array(
		'question'=>$this->input->post('question'),
		'answer'=>$this->input->post('answer')
		);
		
	$this->faq_model->update_faq($id,$data);
	$this->session->set_userdata(array(
           'a_success' => '1')
   	);
	redirect("admin/faq/");	
	}
}