<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Social extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('Ajax_pagination');
        $this->perPage = 2;
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
		$this->load->model('admin/left_model');
		$this->load->model('admin/Social_model');
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

    public function index() {
    $data = array();
     $data['page'] = 'socialnetwork';
     $data['menu'] = $this->left_model->get_menu();
     
        
       
        $data['sociallist'] = $this->Social_model->get_socialList();
        //load the view
	$this->load->view('admin/social/socialnetwork',$data);
    }
    
    
    
    public function edit($id) {
    	$data['socialedit'] = $this->Social_model->edit_social($id); 
	$data['page']='socialnetworkedit';
	$data['menu'] = $this->left_model->get_menu();
	$this->load->view('admin/social/socialnetworkedit',$data);
	
    }
    
    public function update($id) {
	$data = array(
	'title'=>$this->input->post('title'),
	'link'=>$this->input->post('link'),
	'class'=>$this->input->post('class'),
	'status'=>$this->input->post('status')
	);			
	$this->Social_model->social_update($data,$id);
	$this->session->set_userdata(array( 'a_success' => '1') );
   	
	redirect('admin/social');
    }
    
    public function update_status_social($id){
      
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $this->Social_model->status_update($id,$data);
       $this->session->set_userdata(array(
		'a_success_status' => '1'));
    }
    
    
    
}

/* End of file social.php */
/* Location: ./application/controllers/social.php */