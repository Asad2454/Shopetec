<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Faq extends CI_Controller {

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
	
		$data['cart'] = $this->cart->contents();
		$data['count'] = $this->cart->total_items();


		$data['metas'] = objectToArray($this->home_model->get_metas());
		
		
		
		$data['banners'] = $this->home_model->get_banners('1','0');
		$data['faq'] = $this->home_model->get_faq();
		
		$data['socialnetwork'] = objectToArray($this->home_model->get_socialnetwork());
		
		$data['contactus'] = $this->home_model->get_contactus();

        
		$this->load->view('faq', $data);
		
    }

    
        
   



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */