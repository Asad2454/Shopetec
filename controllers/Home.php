<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('cart');
        	
    
	
		$this->load->model('home_model');
	
	
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
        $this->load->view('home', $data);
	}

    
        
   



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */