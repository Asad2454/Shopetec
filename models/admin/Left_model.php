<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Left_model extends CI_Model {

    public function __construct() {
        parent::__construct();
		
        $this->load->database();
		
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }


   
    
       public function get_menu() {
          
   			$return = array();

		    
			$this->db->select('*');
			$this->db->from('admin_menu');
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
			$this->db->from('admin_submenu');
			$this->db->where(array('mid' => $mid, 'status' => '1'));
			$this->db->order_by("sort", "ASC"); 
			$query = $this->db->get();
			return $query->result();
		  

			
		  
		
    	}
    	
    	public function notifications() {
          		$this->db->select('*');
			$this->db->from('notification');
			$this->db->where('user','0');
			$this->db->where('status','1');
			$this->db->order_by("id", "DESC");
			$this->db->limit(7);
			//$this->db->order_by("date", "DSC");
			//$this->db->order_by("id", "1");  
			$query = $this->db->get();
			return $query->result();	
    	}
    	public function allnotifications() {
          		$this->db->select('*');
			$this->db->from('notification');
			$this->db->where('user','0');  
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
    	
    	$this->db->insert('quality_form',$data);
    	
    	}
    	
    	
    	
    	
    	
    	
    	public function allread() {
 
    		$data = array(
    		'status'=> '0'
    		);
    	
          		
			$this->db->update('notification',$data);	
    	}
    	
    	
    	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */