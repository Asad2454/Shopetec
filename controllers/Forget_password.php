<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Forget_password extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('cart');
        $this->load->library('email');
        $this->load->model('home_model');
		$this->load->model('login_model');
		if ($this->session->userdata('is_client_login')) {
            redirect('home');
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
	
	public function reset() {
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
        $id = $this->input->get('code');
        $id = strstr($id, '@');
        $data['id'] = ltrim($id, '@');
        $this->load->view('new_password',$data);
    }
    
    public function change_password() {
        $password = $this->input->post('password');
        $password1 = $this->input->post('password1');
        $id = $this->input->post('id');
        
        if($password == $password1){
            $data = array('password' => md5($password));
            $this->db->where('id',$id);
            $this->db->update('customer',$data);
            $this->session->set_userdata(array('message' => 'Password Changed! You can Login Now!'));
            redirect('login');
        }
        else{
            $this->session->set_userdata(array('error_message' => 'Passwords dont match!'));
            redirect('forget_password/reset/?code='.md5($password).'@'.$id);
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
        $this->load->view('forget_password', $data);
	}
	
    public function check_email() {
        $email1 = $this->input->post('email');
        $sql = "SELECT * FROM customer WHERE email = ?";
        $val = $this->db->query($sql,array($email1));
        if ($val->num_rows()) {
            $email2 = $this->home_model->get_admin_email();
            foreach ($val->result_array() as $res);
            $vid = $res['id'];
            $pass = $res['password'];
            $name = $res['name'];
            foreach($email2 as $e);
            $email = $email1;        
            $from_email = $e->email; 
            $subject = "Account Password Reset Initiated - SHOPETEC.COM";
            $message = "Hi " .$name.", Click the link below to reset your password. " . base_url()."forget_password/reset/?code=".$pass."@".$vid." If you did not request this password change, please ignore this email - your password will remain unaffected ";
            
            $this->email->from($from_email); 
            $this->email->to($email);
            $this->email->subject($subject); 
            $this->email->message($message); 
            //Send mail 
            $this->email->send(); 
            
            $this->session->set_userdata(array('message' => 'Email sent succesfully. Check your inbox and follow the instructions to log back in!'));
            redirect('forget_password');
        }
        else{
            $this->session->set_userdata(array('error_message' => 'Email does not Exist!'));
            redirect('forget_password');
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */