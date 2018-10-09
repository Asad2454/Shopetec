<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Banners extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/banners_model');
        $this->load->library('form_validation');
	
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
     	$data['page'] = 'banners';
     	$data['menu'] = $this->left_model->get_menu();
     	$data['banners'] = $this->banners_model->get_banners();
        $this->load->view('admin/viewbanners', $data);
    }
    
    
       
    
    
	public function index2 ($id = NULL)
	{
		$data['page']='edit_vendor';
		$data['menu'] = $this->left_model->get_menu();
		
		
		
		$data['banners'] = $this->banners_model->single_banner($id);
		$this->load->view('admin/editbanners',$data);
	
	
	}
	public function index1() {
    
	    $data['page']='categories';
        
		$data['menu'] = $this->left_model->get_menu();
		
		
		$this->load->view('admin/addbanners', $data);
		
		
    }
    public function update_status_banners(){
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $this->banners_model->status_update($id,$data);
	$this->session->set_userdata(array(
		'a_success_status' => '1')
	); 
    }
	
	
	public function add() {
	  
	  		
		
		$images_field = 'images';
	  		
	  		$thedate=date('Y/n/j h:i:s');
			$replace = array(":"," ","/");
			$newname=str_ireplace($replace, "-", $thedate);
	  		
	  		$config['upload_path'] = './uploads/banners/';
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
				$data =  array(
	  				'position'=>$this->input->post('position'),
					'urls'=>$this->input->post('urls'),
					
					
					'images' => $upload_data['file_name']
				);
			              
				$this->db->insert('banners',$data);
				
			}
			else{
				$data2 =  array(
	  				'position'=>$this->input->post('position'),
					'urls'=>$this->input->post('urls'),
					
					
					'images' => $upload_data['file_name']
				);
				
				$this->db->insert('banners',$data2);
			}
			
		
			$logFileName = 'application/logs/newfile.log';
			$logContent = "admin added a category at  ".PHP_EOL;
			$space = "                                          ".PHP_EOL;
			$date = new DateTime();
                        $date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')) {
				fwrite($handle, $logContent);
			  	fwrite($handle, $date);
			  	fwrite($handle, $space);
			}
			fclose($handle);
			$this->session->set_userdata(array(
	       			'a_success' => '1')
			); 
			redirect("admin/banners/");	}
	
	
	
	
	
	 public function editbanner($id = NULL) {	
		
		$id=$this->input->post('id');
		
		
		$images_field = 'images';
		
	  		$thedate=date('Y/n/j h:i:s');
			$replace = array(":"," ","/");
			$newname=str_ireplace($replace, "-", $thedate);
	  			 
	  		$config['upload_path'] = './uploads/banners/';
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
	  				'position'=>$this->input->post('position'),
					'urls'=>$this->input->post('urls'),
					
					
					'images' => $upload_data['file_name']
				);
			
				$this->banners_model->update_banners($data,$id);
			
			}
			else{
				$data2 = array(
	  				'position'=>$this->input->post('position'),
					'urls'=>$this->input->post('urls'),
					
					
					'images' => $upload_data['file_name']
				);
				
				$this->banners_model->update_banners($data2,$id);
			}
			
		$logFileName = 'application/logs/newfile.log';
			 $logContent = "admin updated a BANNER at  ".PHP_EOL;
			 $space = "                                          ".PHP_EOL;
			 $date = new DateTime();
                         $date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')) {
			  fwrite($handle, $logContent);
			  fwrite($handle, $date);
			  fwrite($handle, $space);
			}
			fclose($handle);
			$this->session->set_userdata(array(
	       			'a_success' => '1')
			); 
		redirect("admin/banners/");
	}

    
     
}