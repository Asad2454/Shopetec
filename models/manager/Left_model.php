<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Left_model extends CI_Model {

    public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_manager_login')) {
            redirect('manager/home');
        }
    }


   
    
       public function get_menu() {
          
   			$return = array();

		    
			$this->db->select('*');
			$this->db->from('manager_menu');
			$this->db->where(array('status' => '1'));
			$this->db->order_by("sort", "ASC"); 
			$query = $this->db->get();
			
			foreach ($query->result() as $menu)
			{
				$return[$menu->mid] = $menu;
				$return[$menu->mid]->subs = $this->get_submenu($menu->mid); 
			}

			
			
			return $return;
		  
		  
		
    	}
    
	
		public function get_submenu($mid) {
          
			
					  
		    $this->db->select('*');
			$this->db->from('manager_submenu');
			$this->db->where(array('mid' => $mid, 'status' => '1'));
			$this->db->order_by("sort", "ASC"); 
			$query = $this->db->get();
			return $query->result();
		  

			
		  
		
    	}
    	
    	public function notifications() {
          		$this->db->select('*');
			$this->db->from('notification');
			$this->db->where('user','2');
			$this->db->where('user_id',$_SESSION['mid']);
			$this->db->where('status','1');
			$this->db->order_by("id", "DESC");
			$this->db->limit(7);
			$query = $this->db->get();
			return $query->result();	
    	}
    	public function allnotifications() {
          		$this->db->select('*');
			$this->db->from('notification');
			$this->db->where('user','2');  
			$this->db->where('user_id',$_SESSION['mid']);
			$this->db->order_by("date", "DESC");
			$query = $this->db->get();
			return $query->result();	
    	}
    	
    	public function read($id) {
 
    		$data = array(
    		'status'=> '0'
    		);
    	
          		$this->db->where('id', $id);
			$this->db->update('notification',$data);	
    	}
    	
    	public function addform($data){
    	
    	$this->db->insert('approved_quality',$data);
    	
    	}
    	
    	
    	public function editform($data,$id){
    	$this->db->where('id',$id);
    	$this->db->update('approved_quality',$data);
    	
    	}
    	
    	public function allread() {
 
    		$data = array(
    		'status'=> '0'
    		);
    	
          		
			$this->db->update('notification',$data);	
    	}
    	
    	public function get_email2() {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', "8");
			 $this->db->where('status', "1"); 
			$query = $this->db->get();
			return $query->result();
	}
	public function get_email8() {
			    
			$this->db->select('*');
			$this->db->from('email');
			$this->db->where('id', "8");
			 $this->db->where('status', "1"); 
			$query = $this->db->get();
			return $query->result();
	}
	public function get_vendor_email($id){
	                $this->db->select('*');
			$this->db->from('vendors');
			 $this->db->where('id', "$id");
			$query = $this->db->get();
			return $query->result();
	
	}
    	
    	
    	
    	
    	
    	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */