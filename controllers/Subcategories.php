<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subcategories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('cart');
        	$this->load->model('home_model');
        	$this->load->model('subcategories_model');
        	$this->load->model('products_model');
	
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

    	public function index($id = NULL) {
    	    $data['cart'] = $this->cart->contents();
		    $data['count'] = $this->cart->total_items();
    		$data['categories_home3'] = $this->home_model->get_categories_home3();
    		$data['subcategory'] = $this->subcategories_model->get_subcategories($id);
    		$data['category'] = $this->subcategories_model->get_parent_category($id);
		    $data['metas'] = objectToArray($this->home_model->get_metas());
		    $data['socialnetwork'] = objectToArray($this->home_model->get_socialnetwork());
		    $data['contactus'] = $this->home_model->get_contactus();
		    $this->load->view('subcategories', $data);
	}
}