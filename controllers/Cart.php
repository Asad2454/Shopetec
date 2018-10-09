<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cart extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('cart');
        $this->load->model('cart_model');
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
		$data['categories_home3'] = $this->home_model->get_categories_home3();
		$data['cart'] = $this->cart->contents();
		$data['count'] = $this->cart->total_items();
		$data['total'] = $this->cart->total();
        $data['banners'] = $this->home_model->get_banners('1','0');
		//$data['banners1'] = $this->home_model->get_banners('5','1');
		$data['socialnetwork'] = objectToArray($this->home_model->get_socialnetwork());
		$data['contactus'] = $this->home_model->get_contactus();
        $this->load->view('cart', $data);
	}
	
	public function update() {
	    for($i = 0; $i < count($_POST['row_id']); $i++){
	       $data = array(
                'rowid' => $_POST['row_id'][$i],
                'qty'   => $_POST['qty'][$i]
            );
        $this->cart->update($data);
	    }
	    $this->session->set_userdata(array('c_success' => '1'));
	    redirect('cart');
	}
	
	public function add_to_cart() {
	    $id = $this->input->post('id');
	    $qty = $this->input->post('qty');
	    $price = $this->input->post('price');
	    $name = $this->input->post('name');
	    $image = $this->input->post('image');
	    $attr_id = $this->input->post('attr_id');
	    $attr = implode(",", $attr_id);
	    $data = $this->cart_model->add_to_cart_search($id,$attr,$qty);
	    if(!empty($data)){
	            foreach($data as $key);
	            $this->cart_model->add_to_cart($key->id,$qty,$price,$name,$image,$id,$attr_id);
	            $this->session->set_userdata(array('c_success' => '1'));
	    }
	    else{
	        echo "1";
	    }
	}
	
	public function remove_product() {
	    $id = $this->input->post('id');
	    $this->cart->remove($id);
	}

    
}