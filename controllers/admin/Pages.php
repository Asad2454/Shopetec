<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/pages_model');
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
	     	$data['pages'] = $this->pages_model->get_pages();
	        $this->load->view('admin/listpages', $data);
	}
	    
	public function update_status(){
	       $data = array('status' => $this->input->post('status') );
	       $id = $this->input->post('id');
	       $this->pages_model->status_update($id,$data);
			$this->session->set_userdata(array(
				'a_success_status' => '1')
			); 
	}
	    
    	public function index1() {
		$data['page']='addpages';
	        $data['menu'] = $this->left_model->get_menu();
		$this->load->view('admin/addpages', $data);
	}
    
     	public function edit_pages($id = NULL) {
		$data['page']='editpages';
        	$data['menu'] = $this->left_model->get_menu();
		$data['single_pages'] = $this->pages_model->single_pages($id);
		$this->load->view('admin/editpages', $data);	
    	}



 public function add() {
	$data = array(
		'title'=>$this->input->post('title'),
		'heading'=>$this->input->post('heading'),
		'seo_url'=>$this->input->post('seo_url'),
		'meta_title'=>$this->input->post('meta_title'),
		'meta_keywords'=>$this->input->post('meta_keywords'),
		'meta_description'=>$this->input->post('meta_description'),
		'description'=>$this->input->post('description'),
		'redirect_url'=>$this->input->post('redirect_url'),
		'status'=>'1'
	);
	$this->db->insert('pages',$data);
	$this->session->set_userdata(array(
           'a_success' => '1')
   	);
	redirect("admin/pages/");	
	}
    
    
     public function update($id = NULL) {	
		
		$data = array(
		'title'=>$this->input->post('title'),
		'heading'=>$this->input->post('heading'),
		'seo_url'=>$this->input->post('seo_url'),
		'meta_title'=>$this->input->post('meta_title'),
		'meta_keywords'=>$this->input->post('meta_keywords'),
		'meta_description'=>$this->input->post('meta_description'),
		'description'=>$this->input->post('description'),
		'redirect_url'=>$this->input->post('redirect_url'),
	);
		
	$this->pages_model->update_pages($id,$data);
	$this->session->set_userdata(array(
           'a_success' => '1')
   	);
	redirect("admin/pages/");	
	}
}