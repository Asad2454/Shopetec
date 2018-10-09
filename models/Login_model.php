<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        
    }
	
	
	public function check($email,$password) {
	    $this->db->select('*');
        $this->db->from('customer');
        $this->db->where('email',$email);
        $this->db->where('password',$password);
        $query = $this->db->get();
		return $query->result_array();
	}
	
	public function check2($email) {
	    $this->db->select('*');
        $this->db->from('customer');
        $this->db->where('email',$email);
        $query = $this->db->get();
		return $query->result_array();
	}
}
/* End of file Products_model.php */
/* Location: ./application/model/Products_model.php */