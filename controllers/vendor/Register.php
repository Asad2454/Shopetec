<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('form_validation');
	$this->load->model('vendor/register_model');
    }

    public function index() {
    $data['parent'] = $this->register_model->get_parent();
        	$this->load->view('vendor/register',$data);
        }
        
        
       
      


       
   public function addvendor(){
   
        $email=$this->input->post('email');
           $a=$this->ifexist($email);
         
           
	if($a==1){
	header('location:'.base_url()."vendor/register?msg=success");
	
	}else{
	     	
	     	
	     	
	     	
	     	
	     	
	$data =md5($this->input->post("password"));
    	$data = array(
       		'name'=>$this->input->post('name'),
     		'email'=>$this->input->post('email'),
     		'password'=>$data,
             	'status'=>"0"
     		);
    	
     		
     		 
     		  
     	 $this->register_model->setdata($data);
     	 $ij=$this->register_model->getLastInserted();
    	$data2 = array(
       		'type'=>"4",
     		'message'=>"New Vendor Registered",
     		'user'=>"0",
     		'date'=>date("Y/m/d"),
     		'time' => date("H:i:s"),
             	'status'=>"1",
             	'hit_id'=>$ij,
             	'datetime'=>date("Y/m/d H:i:s")
     		);
    	    	
        $email=$this->register_model->get_admin_email();
         foreach($email as $e){
         
         $sql = $this->register_model->get_email();
          
        
        foreach($sql as $data){
          
             $email= $e->email;       
         $from_email = $data->sender_email; 
         $subject=$data->Subject;
         $message=$data->body;
         
         $this->email->from($from_email); 
         $this->email->to($email);
         
         $this->email->subject($subject); 
         $this->email->message($message); 
       
         //Send mail 
         $this->email->send(); 
        
      }
    	}
    	
    	$this->register_model->notification($data2);
    	
    	
    	
    	$logFileName = 'application/logs/newfile.log';
			 $logContent = "vendor has been added at  ".PHP_EOL;
			 $space = "                                          ".PHP_EOL;
			 $date = new DateTime();
                         $date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')) 
			{
			  fwrite($handle, $logContent);
			  fwrite($handle, $date);
			  fwrite($handle, $space);
			    }
			     fclose($handle);
	header('location:'.base_url()."vendor/home?msg=success");
   
 
    	}
    	
    	
    	}
    	
    	
    	 
       public function ifexist($value){
                     $this->db->select('email');
                     $this->db->from('vendors');
	             $this->db->where('email', $value);
		     $query = $this->db->get();
		     return $query->num_rows();
     }
}

