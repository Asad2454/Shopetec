<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Managerprofile extends CI_Controller {

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
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_manager_login')) {
            redirect('manager/home');
        }
		
		$this->load->model('manager/left_model');
		$this->load->model('manager/manager_model');
		
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
        $data['menu'] = $this->left_model->get_menu();
		$id= $_SESSION['mid'];
		$data['parent'] = $this->manager_model->get_parent();
        $data['manager'] = $this->manager_model->manager_data_single($id);
		$this->load->view('manager/edit_manager',$data);
	}
    
    public function index1() {
        $data['page']='dashboard';
        $data['menu'] = $this->left_model->get_menu();
		$this->load->view('manager/changepassword',$data);
	}
	
    public function changepwd(){
               
               $this->form_validation->set_rules('opas','Old Password','required');
               $this->form_validation->set_rules('npas','New Password','required');
               $this->form_validation->set_rules('cpas','Confirm Password','required|matches[npas]');

             if($this->form_validation->run()!= true)
                      {
                             header('location:'.base_url()."manager/managerprofile/index1?msg=success4");

                      }else{
                      
                      $sql = $this->db->select("*")->from("managers")->where("email",$this->session->userdata('memail'))->get();

                      foreach ($sql->result() as $my_info) {

                      $db_password = $my_info->password;
                      $id = $_SESSION['mid'];
                      
                     
                      
                      if(md5($this->input->post("opas")) == $db_password){

                            $data =md5($this->input->post("npas"));
                            
                            $this->manager_model->update_pass($id,$data);
                            $logFileName = 'application/logs/newfile.log';
			 $logContent = " Manager updated his/her Password  ".PHP_EOL;
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
			    
                            header('location:'.base_url()."manager/managerprofile/index1?msg=success");
                           

                                   }else{
                                          header('location:'.base_url()."manager/managerprofile/index1?msg=success1");

                    return false;

}
                     
                      }
               }
    }
    
    
    
    
    
    
    
   
      
public function editmanager(){
	     
             
               
                   $id= $this->input->post('id');
                   $data = array(
	  				'name'=>$this->input->post('name'),
					
                                         'address'=>$this->input->post('address'),
					'phone'=>$this->input->post('phone'),
					'category_id'=>$this->input->post('p_category'),
					
					
				);


                   $this->manager_model->update_data($id,$data);
                   $logFileName = 'application/logs/newfile.log';
			 $logContent = "Manager updated his/her profile at  ".PHP_EOL;
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
		       'mcategory' => $this->input->post('p_category') )); 
                  
                         header('location:'.base_url()."manager/Managerprofile/index?msg=success");
}
    
    

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */