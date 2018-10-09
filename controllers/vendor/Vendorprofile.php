<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendorprofile extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_vendor_login')) {
            redirect('vendor/home');
        }
		
		$this->load->model('vendor/left_model');
		$this->load->model('vendor/vendor_model');
		$this->load->model('vendor/dashboard_model');
		$this->load->model('vendor/categories_model');
		$this->load->model('vendor/register_model');
		
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
      	$data['page']='dashboard';
        $id = $_SESSION['vid'];
		$status = $this->dashboard_model->get_status($id);
		foreach($status as $key){
			if($key->status == 1){
				$data['menu'] = $this->left_model->get_menu();
				$data['menu1'] = 0;
			}
			else{
				$data['menu1'] = 1;						
			}
		}		
		
		$data['vendor'] = $this->vendor_model->vendor_data_single($id);
		$data['parent'] = $this->categories_model->get_parent();
		$this->load->view('vendor/edit_vendor',$data);
	}
    
    
    	public function index1() {
	      	$data['page']='dashboard';
	        $data['menu'] = $this->left_model->get_menu();
		$this->load->view('vendor/changepassword',$data);
	}
	
    public function changepwd(){
               
               $this->form_validation->set_rules('opas','Old Password','required');
               $this->form_validation->set_rules('npas','New Password','required');
               $this->form_validation->set_rules('cpas','Confirm Password','required|matches[npas]');

             if($this->form_validation->run()!= true)
                      {
                            header('location:'.base_url()."vendor/vendorprofile/index1?msg=success4");

                      }else{
                      
                      $sql = $this->db->select("*")->from("vendors")->where("email",$this->session->userdata('vemail'))->get();

                      foreach ($sql->result() as $my_info) {

                      $db_password = $my_info->password;
                      $id = $_SESSION['vid'];
                      
                     
                      
                      if(md5($this->input->post("opas")) == $db_password){

                            $data =md5($this->input->post("npas"));
                            
                            $this->vendor_model->update_pass($id,$data);
                            $logFileName = 'application/logs/newfile.log';
			 $logContent = "Vendor changed his/her password at  ".PHP_EOL;
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
                            header('location:'.base_url()."vendor/vendorprofile/index1?msg=success");
                           

                                   }else{
                                          header('location:'.base_url()."vendor/vendorprofile/index1?msg=success1");

                    return false;

}
                     
                      }
               }
    }
    
    
   
      
    public function editvendor(){
          $data2 = array(
       		    'type'=>"0",
     		    'message'=>"Vendor updated profile",
     		    'user'=>"0",
     		    'date'=>date("Y/m/d"),
     		    'time' => date("H:i:s"),
                'status'=>"1",
                'datetime'=>date("Y/m/d H:i:s"),
     		);
	     
        $id = $_SESSION['vid'];
        $images_field = 'images';
	    $thedate=date('Y/n/j h:i:s');
	   	$replace = array(":"," ","/");
	   	$newname=str_ireplace($replace, "-", $thedate);
	    $config['upload_path'] = './uploads/vendors/';
	   	$config['allowed_types'] = 'gif|jpg|png|txt';
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
         		'address'=>$this->input->post('address'),
                'phone'=>$this->input->post('phone'),
         		'cnic'=>$this->input->post('cnic'),
         		'documents' => $upload_data['file_name']
        		);
        	
            $this->vendor_model->update_data($id,$data);
        	
        	$data3 = array(
           		'type'=>"8",
         		'message'=>"Vendor updated profile",
         		'user'=>"2",
         		
         		'date'=>date("Y/m/d"),
         		'time' => date("H:i:s"),
                 	'status'=>"1",
                 	'hit_id'=>$id
         		);
         	$this->register_model->notification($data3);
     		//$this->register_model->setdata($data);
    	    	
            $email=$this->register_model->get_admin_email();
            foreach($email as $e){
                $sql = $this->register_model->get_email1();
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
     		    	
     	    $logFileName = 'application/logs/newfile.log';
			$logContent = "vendor updated his/her profile at  ".PHP_EOL;
			$space = "                                          ".PHP_EOL;
			$date = new DateTime();
            $date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')){
    			  fwrite($handle, $logContent);
    			  fwrite($handle, $date);
    			  fwrite($handle, $space);
    	    }
			fclose($handle);
		    header('location:'.base_url()."vendor/vendorprofile/index?msg=success");
        }
   	    else{
   	        $dataa = md5($this->input->post("password"));
    		$data2 = array(
     		    'name'=>$this->input->post('name'),
     		    'address'=>$this->input->post('address'),
                'phone'=>$this->input->post('phone'),
     	    	'cnic'=>$this->input->post('cnic'),
    		);
    
    	    $this->vendor_model->update_data($id,$data2);
    	    $data3 = array(
       		    'type'=>"8",
     		    'message'=>"Vendor updated profile",
     		    'user'=>"2",
         		'date'=>date("Y/m/d"),
     		    'time' => date("H:i:s"),
             	'status'=>"1",
             	'hit_id'=>$id
     		);
     		    	
    	    $this->register_model->notification($data3);
    	    $email=$this->register_model->get_admin_email();
            foreach($email as $e){
         
                $sql = $this->register_model->get_email1();
         
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
    	
    	
    	
    	
    	$logFileName = 'application/logs/newfile.log';
			 $logContent = "Vendor updated his/her profile at  ".PHP_EOL;
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
		$this->session->set_userdata(array(
		       'category' => $this->input->post('p_category') )
		);                                                    


    	 header('location:'.base_url()."vendor/vendorprofile/index?msg=success");
    
   	}

}
    
    

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */