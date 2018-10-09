<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Testimonials extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/testimonials_model');
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
    
    public function edit_testimonials($id = NULL) {
    	$data['page']='categories';
        $data['menu'] = $this->left_model->get_menu();
	$data['single_testimonial'] = $this->testimonials_model->single_testimonial($id);
	$this->load->view('admin/edittestimonials', $data);

    }
    
    public function index() {
	$data = array();
	$data['page']='Testimonials';
	$data['menu'] = $this->left_model->get_menu();	
	$data['testimonials'] = $this->testimonials_model->get_all_testimonials();
	$this->load->view('admin/listtestimonials', $data);
    }
    
    public function update_status(){
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $this->testimonials_model->status_update($id,$data);
       $this->session->set_userdata(array(
		'a_success_status' => '1')
	); 
    }
    
    public function index1() {
      	$data['page']='Add Testimonials';
        $data['menu'] = $this->left_model->get_menu();
	$this->load->view('admin/addtestimonials', $data);
    }
    
    public function add() {
 	$images_field = 'images';
 	  	
  	$thedate=date('Y/n/j h:i:s');
	$replace = array(":"," ","/");
	$newname=str_ireplace($replace, "-", $thedate);
	
	$config['upload_path'] = './uploads/testimonials/';
	$config['allowed_types'] = 'gif|jpg|png';
	$config['file_name'] = $newname;
	$config['max_size'] = '1000';
	$config['max_width']  = '';
	$config['max_height']  = '';
	$config['overwrite'] = TRUE;
	$config['remove_spaces'] = TRUE;
	$this->load->library('upload', $config);
		
	if ($this->upload->do_upload($images_field)){
		$upload_data = $this->upload->data();
		$data = array(
			'name'=>$this->input->post('name'),
			'testimonial'=>$this->input->post('testimonial'),
			'images' => $upload_data['file_name']
		);
		$this->db->insert('testimonials', $data);
	}
	else{
		$data = array(
			'name' => $this->input->post('name'),
			'testimonial '=> $this->input->post('testimonial'),
			'images' => ''
		);
		$this->db->insert('testimonials',$data);
	}
	$logFileName = 'application/logs/newfile.log';
	$logContent = "admin added a subcategory at  ".PHP_EOL;
	$space = " ".PHP_EOL;
	$date = new DateTime();
        $date = $date->format("y:m:d h:i:s");
	if ($handle = fopen($logFileName, 'a')){
	  	fwrite($handle, $logContent);
		fwrite($handle, $date);
		fwrite($handle, $space);
	}
	fclose($handle);
	$this->session->set_userdata(array(
       		'a_success' => '1')
	); 
	redirect("admin/testimonials/"); 


   
    }
    
    public function update($id = NULL) {	
	$images_field = 'images';
	
	$thedate=date('Y/n/j h:i:s');
	$replace = array(":"," ","/");
	$newname=str_ireplace($replace, "-", $thedate);

	$config['upload_path'] = './uploads/testimonials/';
	$config['allowed_types'] = 'gif|jpg|png';
	$config['file_name'] = $newname;
	$config['max_size'] = '1000';
	$config['max_width']  = '';
	$config['max_height']  = '';
	$config['overwrite'] = TRUE;
	$config['remove_spaces'] = TRUE;

	$this->load->library('upload', $config);
	if ( $this->upload->do_upload($images_field)){
		$upload_data = $this->upload->data();
		$data = array(
			'name'=>$this->input->post('name'),
			'testimonial'=>$this->input->post('testimonial'),
			'images' => $upload_data['file_name']
		);
		$this->testimonials_model->update_testimonails($data,$id);
	}
	else{
		$data1 = array(
			'name'=>$this->input->post('name'),
			'testimonial'=>$this->input->post('testimonial'),
		);
		$this->testimonials_model->update_testimonails($data1,$id);
	}
	$this->session->set_userdata(array(
		'a_success' => '1')
	); 
	redirect("admin/testimonials/");	
    }
}