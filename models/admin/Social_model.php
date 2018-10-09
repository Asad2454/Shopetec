<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Social_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

   

    public function get_socialList(){
        $this->db->select('*');
        $this->db->from('social_media');	
      
        $query = $this->db->get();
        
        return $query->result_array();
    }
    
    public function edit_social($id){
        $this->db->select('*');
        $this->db->from('social_media');
        $this->db->where('id',$id);	
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    public function social_update($data,$id){
       $this->db->where('id',$id);
       $this->db->update('social_media',$data);
    }
    
     public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('social_media', $data);
    }
    

    
}
/* End of file social_model.php */
/* Location: ./application/model/social_model.php */