<?php
error_reporting(0);
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Aboutus extends CI_Controller

{
	public function __construct()

	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('home_model');
		$this->load->library('cart');
		function objectToArray($d)
		{
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
	
	public function index()

	{
		$data['metas'] = objectToArray($this->home_model->get_metas());
		$data['cart'] = $this->cart->contents();
		$data['count'] = $this->cart->total_items();
		$data['categories_home3'] = $this->home_model->get_categories_home3();
		$data['banners'] = $this->home_model->get_banners('1', '0');
		// $data['banners1'] = $this->home_model->get_banners('5','1');
		$data['socialnetwork'] = objectToArray($this->home_model->get_socialnetwork());
		$data['contactus'] = $this->home_model->get_contactus();
		$data['aboutus'] = $this->home_model->get_aboutus();
		$this->load->view('aboutus', $data);
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
