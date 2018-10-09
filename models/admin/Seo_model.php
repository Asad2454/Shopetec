<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Seo_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
    }

   

    public function get_seoList($params = array()){
        $this->db->select('*');
        $this->db->from('seo');	
        //this code is for pagination
         if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        $query = $this->db->get();
        
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
    
    public function edit_seo($id){
        $this->db->select('*');
        $this->db->from('seo');
        $this->db->where('id',$id);	
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    public function seo_update($data,$id){
       $this->db->where('id',$id);
       $this->db->update('seo',$data);
    }
    
     public function status_update($id,$data){
          $this->db->where('id', $id);
          $this->db->update('seo', $data);
    }
    
    

    
}
/* End of file social_model.php */
/* Location: ./application/model/social_model.php */