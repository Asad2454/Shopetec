<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Seo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('Ajax_pagination');
        $this->perPage = 2;
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
		$this->load->model('admin/left_model');
		$this->load->model('admin/Seo_model');
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
        $data['page'] = 'seolist';
        $data['menu'] = $this->left_model->get_menu();
     
        //total rows count
        $totalRec = count($this->Seo_model->get_seoList());
        
        //pagination configuration
        $config['target']      = '#postList';
        $config['base_url']    = base_url().'admin/seo/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $data['seolist'] = $this->Seo_model->get_seoList(array('limit'=>$this->perPage));
        //load the view
    	$this->load->view('admin/seo/seolist',$data);
    }
    
    
    
    function ajaxPaginationData(){
        $conditions = array();
        
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        //total rows count
        $totalRec = count($this->Seo_model->get_seoList($conditions));
        
        //pagination configuration
        $config['target']      = '#postList';
        $config['base_url']    = base_url().'admin/seo/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['seolist'] = $this->Seo_model->get_seoList($conditions);
        
        //load the view
        $this->load->view('admin/seo/ajax-pagination-data', $data, false);
    }
    
    
    
    
    
    
    
    
    
    
    public function edit($id) {
    	$data['seoedit'] = $this->Seo_model->edit_seo($id); 
	$data['page']='seoedit';
	$data['menu'] = $this->left_model->get_menu();
	$this->load->view('admin/seo/seoedit',$data);
    }
    
    public function update($id) {
	$data = array(
	'title'=>$this->input->post('title'),
	'heading'=>$this->input->post('heading'),
	'seo_url'=>$this->input->post('seo_url'),
	'redirect'=>$this->input->post('redirect'),
	'meta_title'=>$this->input->post('meta_title'),
	'meta_keywords'=>$this->input->post('meta_keywords'),
	'meta_description'=>$this->input->post('meta_description'),
	'body'=>$this->input->post('body'),
	'status'=>$this->input->post('status'),
	);			
	$this->Seo_model->seo_update($data,$id);
	header('location:'.base_url()."admin/seo/index?msg=success");
    }
    
    public function update_status_seo(){
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $this->Seo_model->status_update($id,$data);
    }
}

/* End of file social.php */
/* Location: ./application/controllers/social.php */