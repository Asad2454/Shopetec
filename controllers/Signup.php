<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Signup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('cart');
        $this->load->model('home_model');
		$this->load->model('signup_model');
	
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
		$data['metas'] = objectToArray($this->home_model->get_metas());
		//$data['cart'] = $this->home_model->get_cart();
		$data['cart'] = $this->cart->contents();
		$data['count'] = $this->cart->total_items();
		$data['categories_home'] = $this->home_model->get_categories_home();
		$data['categories_home1'] = $this->home_model->get_categories_home1();
		$data['categories_home2'] = $this->home_model->get_categories_home2();
		$data['categories_home3'] = $this->home_model->get_categories_home3();
		$data['banners'] = $this->home_model->get_banners('1','0');
		$data['banners1'] = $this->home_model->get_banners('5','1');
		$data['socialnetwork'] = objectToArray($this->home_model->get_socialnetwork());
		$data['contactus'] = $this->home_model->get_contactus();
        $this->load->view('signup', $data);
	}
	
    public function create_account() {
    
        $data = array(
                'name' => $this->input->post('billing_first_name'),
                'last_name' => $this->input->post('billing_last_name'),
                'email' => $this->input->post('email'),
                'password' => md5($this->input->post('password')),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('billing_address_1'),
                'address_2' => $this->input->post('billing_address_2'),
                'city' => $this->input->post('billing_city'),
                'state' => $this->input->post('billing_state'),
                'country' => $this->input->post('billing_country'),
                'postcode' => $this->input->post('billing_postcode')
            );
        $this->signup_model->create_account($data);
        $this->session->set_userdata(array('c_success' => '1'));
        redirect('login');
	 }
	 
	 public function check_email() {
        $email = $this->input->post('email');
        $val = $this->signup_model->check_email($email);
        if (count($val) > 0) {
            echo "1";
        }
        
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */