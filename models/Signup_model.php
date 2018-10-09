<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Signup_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        
    }
	
	
	public function create_account($data) {
	    $this->db->insert('customer',$data);
	}
	
	public function check_email($email) {
	    $this->db->select('*');
        $this->db->from('customer');
        $this->db->where('email', $email);
        $query = $this->db->get();
		return $query->result();
	}
}