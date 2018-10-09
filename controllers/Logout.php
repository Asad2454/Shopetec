<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logout extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('cart');
        $this->load->model('home_model');
		//$this->load->model('login_model');
		if (!$this->session->userdata('is_client_login')) {
            redirect('login');
        }
	
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
		$this->session->unset_userdata('c_id');
        $this->session->unset_userdata('c_name');
        $this->session->unset_userdata('c_email');
        $this->session->unset_userdata('is_client_login');
        //$this->session->sess_destroy();
        redirect('home');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */