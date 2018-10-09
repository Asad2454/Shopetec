<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Quality extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->library('form_validation');
        
         if (!$this->session->userdata('is_manager_login')) {
            redirect('manager/home');
        }
		
		$this->load->model('manager/left_model');
		$this->load->model('manager/quality_model');
		$this->load->model('manager/categories_model');
		
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

    public function index($pid=NULL) {
     $data = array();
     $data['page'] = 'mviewquality';
     
        $data['menu'] = $this->left_model->get_menu();
        $data['parent'] = $this->categories_model->get_parent();
        
        
        $data['id'] = $pid;
      
        $data['questions']=  $this->quality_model->getall_comments($pid);
        if (empty($data['questions'])){
        	$data['questions1'] =  $this->quality_model->get_all_comments();
        }
        $this->load->view('manager/mviewquality', $data);       
        
    }
    
   
     
    
    
    public function edit(){
    
    
    if(in_array(0,$_POST['aid'])){
    
     $size = count($_POST['q_id']);
     	     for($i=0;$i<$size;$i++)
     	     {
     	     	$binary = 1;
     	     	
     	     	if(empty($_POST['onoffswitch'][$i])){
     	     	
     	     		$binary = 0;
     	     	
     	     	}
     	     	
		$data=array(
		        'question_id'=>$_POST['q_id'][$i],
		        'product_id'=>$this->input->post('id'),
			'comments'=>$_POST['comments'][$i],
         		'binary'=> $binary
         	           );
            	$this->left_model->addform($data);
	     }
			$this->session->set_userdata(array(
	       			'm_success_quality' => '1')
			);                  
                    redirect('manager/Products');    
    }else{
    

         $size = count($_POST['q_id']);
     	     for($i=0;$i<$size;$i++)
     	     {
     	     	$binary = 1;
     	     	
     	     	if(empty($_POST['onoffswitch'][$i])){
     	     	
     	     		$binary = 0;
     	     	
     	     	}
     	     	
		$data=array(
			'comments'=>$_POST['comments'][$i],
         		'binary'=> $binary
         	           );
            	$this->left_model->editform($data,$_POST['aid'][$i]);
	     }
			$this->session->set_userdata(array(
	       			'm_success_quality' => '1')
			);                  
                    redirect('manager/Products'); 
          }
     }
    	
  }
