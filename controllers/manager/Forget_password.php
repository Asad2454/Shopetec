<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Forget_password extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('form_validation');
	    $this->load->model('vendor/register_model');
    }

    public function index() {
        $this->load->view('manager/forget_password');
    }
    
    public function reset() {
        $id = $this->input->get('code');
        $id = strstr($id, '@');
        $data['id'] = ltrim($id, '@');
        $this->load->view('manager/new_password',$data);
    }
    
    public function change_password() {
        $password = $this->input->post('password');
        $password1 = $this->input->post('password1');
        $id = $this->input->post('id');
        
        if($password == $password1){
            $data = array('password' => md5($password));
            $this->db->where('id',$id);
            $this->db->update('managers',$data);
            $this->session->set_userdata(array('message' => 'Password Changed! You can Login Now!'));
            redirect('manager');
        }
        else{
            $this->session->set_userdata(array('error_message' => 'Passwords dont match!'));
            redirect('manager/forget_password/reset/?code='.md5($password).'@'.$id);
        }
    }
    
    public function check_email() {
        $email1 = $this->input->post('email');
        $sql = "SELECT * FROM managers WHERE email = ? AND status != '0' ";
        $val = $this->db->query($sql,array($email1));
        if ($val->num_rows()) {
            $email2 = $this->register_model->get_admin_email();
            foreach ($val->result_array() as $res);
            $vid = $res['id'];
            $pass = $res['password'];
            $name = $res['name'];
            foreach($email2 as $e);
            $email = $email1;        
            $from_email = $e->email; 
            $subject = "Account Password Reset Initiated - SHOPETEC.COM";
            $message = "Hi " .$name.", Click the link below to reset your password. " . base_url()."manager/forget_password/reset/?code=".$pass."@".$vid." If you did not request this password change, please ignore this email - your password will remain unaffected ";
            
            $this->email->from($from_email); 
            $this->email->to($email);
            $this->email->subject($subject); 
            $this->email->message($message); 
            //Send mail 
            $this->email->send(); 
            
            $this->session->set_userdata(array('message' => 'Email sent succesfully. Check your inbox and follow the instructions to log back in!'));
            redirect('manager/forget_password');
        }
        else{
            $this->session->set_userdata(array('error_message' => '<strong>Email</strong> Doesnt Exist!'));
            redirect('manager/forget_password');
        }
    }
}