<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Contactus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->perPage = 2;
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
		$this->load->model('admin/left_model');
		$this->load->model('admin/Contactus_model');
		function objectToArray($d) {
			
			if (is_object($d)) {
				$d = get_object_vars($d);
			}
			
			if (is_array($d)) {
				return array_map(__FUNCTION__, $d);
			}else {
				return $d;
			}
		}			
    }

   
    
    public function index($id=NULL) {
    	$data['contactusedit'] = $this->Contactus_model->edit_contactus(); 
	$data['page']='contactusedit';
	$data['menu'] = $this->left_model->get_menu();
	$this->load->view('admin/contactus/contactusedit',$data);
    }
    
    public function update() {
      
        $id=$this->input->post('id');
	$data = array(
	'business_name'=>$this->input->post('business_name'),
	'phone'=>$this->input->post('phone'),
	'mobile'=>$this->input->post('mobile'),
	'fax'=>$this->input->post('fax'),
	'map'=>$this->input->post('map'),
	'address'=>$this->input->post('address'),
	'website'=>$this->input->post('website'),
	'status'=>$this->input->post('status'),
	);			
	$this->Contactus_model->contactus_update($data,$id);
	redirect('admin/contactus/index');
    }
    
     public function update_status_contactus(){
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $this->Contactus_model->status_update($id,$data);
    }
}

/* End of file social.php */
/* Location: ./application/controllers/social.php */