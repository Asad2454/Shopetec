<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendors extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('email');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->perPage = 4;
        if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
		
		$this->load->model('admin/left_model');
		$this->load->model('admin/vendor_model');
		$this->load->model('admin/categories_model');
		$this->load->model('admin/dashboard_model');
		
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
		
		
		$data = array();
		$data['page']='managevendor';  
		$data['menu'] = $this->left_model->get_menu();
		$data['vendor'] = $this->vendor_model->get_all_vendors();
		
		$this->load->view('admin/managevendor',$data);
		
		
    }
    
    
    
     public function searchkey() {
	
	
		$data = array();
		$status1=0;
		$j = 0;
		$k = 0;
   	     
		 $wow = '';
		 if(!empty($this->input->post('searchkey'))){
		 
		 	$wow = $this->input->post('searchkey');
			
		 }else{
		 	
			$wow = $this->input->get('searchkey');
		 
		 }
		 
		
		$sort_order = $this->input->get('sort_order');
		$sort_by= $this->input->get('sort_by');
		$per_page = $this->input->get('per_page');
		
		if($this->vendor_model->get_products1($wow,'','','','')){
			$totalRec = count($this->vendor_model->get_products1($wow,'','','',''));
		}
		else{
			$totalRec = 0;
		}
		
		 
     		$config = array();
	
	
		$config['full_tag_open'] = "<ul class='pagination pagination-xs nomargin pagination-custom'>";
		$config['full_tag_close'] ="</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='active disabled'><a>";
		$config['cur_tag_close'] = "</a></li>";
		$config['next_tag_open'] = "<li class='black pagination-gap pagination-gap3'>";
		$config['next_tagl_close'] = "</li>";
		$config['prev_tag_open'] = "<li class='black pagination-gap '>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		$config['next_link'] = 'NEXT';
		$config['prev_link'] = 'PREV';
	
				
		$config["base_url"] = base_url() . "admin/vendors/searchkey?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
		$config["total_rows"] = $totalRec;
		$config["per_page"] = 10;
		$config["uri_segment"] = 2;
		$config["page_query_string"] = TRUE;
		$config['display_pages'] = TRUE;
		$choice = $config["total_rows"] / $config["per_page"];
		$config['num_links'] = 9;
		$config['attributes'] = array('class' => 'pagination');
		$this->pagination->initialize($config);		
		
		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;
		
	$categories1 = $this->vendor_model->get_products1($wow, $config["per_page"], $per_page,$sort_by,$sort_order);
    	
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'admin/vendors/searchkey?searchkey='.$wow.'&sort_by=name&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Name</th>
                          <th href="'.base_url() .'admin/vendors/searchkey?searchkey='.$wow.'&sort_by=phone&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Phone</th>
                          <th href="'.base_url() .'admin/vendors/searchkey?searchkey='.$wow.'&sort_by=category_id&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Categories</th>
                         
                         <th style="width: 30px;">CNIC</th>
                          <th style="width: 30px;">Status</th>
                          <th style="width: 30px;">Action</th>
                        </tr>
                      </thead>
                      <tbody>';
                      if(!empty($categories1)){
					  	
						if($per_page == 0){
							$i = 0;
							$j = $i + 1;
						
                        			}else{
							$i = $per_page;
							$j = $i + 1;
						}
				$k = $config["per_page"];
						$last = $totalRec%5;
						$total_pages = ceil($totalRec/5);
						$current = $config['display_pages'];
						$k += $per_page;
						($k > $totalRec) ? $k =  $totalRec : $k = $k;
						foreach($categories1 as $value){ 
						$i++;
							
                        if ($value['status'] == 1){ $status = 'check-toggler checked';} else if($value['status'] == 0){ $status='check-toggler cross';} else if($value['status'] == 2) { $status='fa fa-pause pause';}
                        if ($value['status'] == 2){ $status1 = 'style="color:#f4b942"'; } 
                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'. $value['name'].'</td>
                          <td>'. $value['phone'].'</td>
                          <td>'. $value['category_id'].'</td>
                          <td>'. $value['cnic'].'</td>
                          
                          <td style="text-align:center;"><i data="'. $value['id'].'" data-status="'. $value['status'].'" '.$status1.' class="status_vendor '.$status.'"></i></td>

                          <td style="text-align:center;"><a href="'. base_url().'admin/vendors/index2/'. $value['id'].'" class="fa fa-pencil color-red"></a></td>
                        </tr> ';
                         } } else { 
                        $table .= '<tr>
                        <td>Data not available.</td>
                        </tr> ';
                         } 
                      $table .= '</tbody>
                    </table>	 </div>


                  <div class="tile-footer bg-transparent-black-2 rounded-bottom-corners">
                    <div class="row">  
                      

                      <div class="col-sm-offset-4 col-sm-4 text-center">
                                              <small class="inline table-options paging-info">showing '.$j.' - '.$k.' of '.$totalRec.' items</small>

                      </div>

                      <div class="col-sm-4 text-right sm-center">
                        '.$this->pagination->create_links().'
                      </div>';
    		
    		echo $table;
    }
    
    
    
    
    
    
    
    
    
    
    public function update_status(){
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $status= $this->input->post('status');
       $this->vendor_model->status_update($id,$data);
       $sql = $this->vendor_model->get_vendoremail($id);
        if($status == "2"){
       //get vendor email from the vendor table via id and send email.
			foreach ($sql as $my_info) {
				
				
				$email = $my_info->email;
				$sql = $this->dashboard_model->get_email('6');
			  
				foreach($sql as $data){
							  
							 
				  $from_email = $data->sender_email; 
				 $subject=$data->Subject;
				 $message=$data->body;
				
				 $this->email->from($from_email); 
				 $this->email->to($email);
				 
				 $this->email->subject($subject); 
				 $this->email->message($message); 
			   
				 //Send mail 
				 $this->email->send();
				
	      
	                      
	                      
	      } 
     
	 
	  }      
       //send email to vendor that you can login now.
       
       
       }else if($status == "1"){
       // send email to vendor that you are now comppletely activate now you can add products.
     		foreach ($sql as $my_info) {
				$email = $my_info->email;
				$sql = $this->dashboard_model->get_email('3');
			  
			
				foreach($sql as $data){
							  
							 
					$from_email = $data->sender_email; 
					$subject=$data->Subject;
					$message=$data->body;
					
					$this->email->from($from_email); 
					$this->email->to($email);
					
					$this->email->subject($subject); 
					$this->email->message($message); 
					
					//Send mail 
					$this->email->send();
				
			  
							  
							  
			  } 
		  }      
       
       
    }
  
  
  
  }
  
  
  
  
  
public function editvendor(){
	     
        $id= $this->input->post('id');
		$category = '';

		$images_field = 'images';
		$thedate=date('Y/n/j h:i:s');
	   	$replace = array(":"," ","/");
	   	$newname=str_ireplace($replace, "-", $thedate);
		$config['upload_path'] = './uploads/vendors/';
	   	$config['allowed_types'] = 'gif|jpg|png|txt';
	   	$config['file_name'] = $newname;
	   	$config['max_size'] = '1000';
	   	$config['max_width']  = '';
	   	$config['max_height']  = '';
	   	$config['overwrite'] = TRUE;
	   	$config['remove_spaces'] = TRUE;
	   	
   	 $this->load->library('upload', $config);
     if ( $this->upload->do_upload($images_field)){
    
    	$upload_data = $this->upload->data();
    	
    	if(!empty($_POST['p_category'])){
       	    for($i = 0; $i < count($_POST['p_category']);$i++){
    		    $category = $_POST['p_category'][$i].",".$category;
            }
            $old_category = $this->input->post('old_category');
            $category = $category.$old_category;
   	    
    	    $data = array(
           		'name'=>$this->input->post('name'),
         		'email'=>$this->input->post('email'),
                'address'=>$this->input->post('address'),
         		'phone'=>$this->input->post('phone'),
         		'cnic'=>$this->input->post('cnic'),
         		'category_id' => $category,
         		'documents' => $upload_data['file_name']
    	    );
    	}
    	else{
    	    $data = array(
           		'name'=>$this->input->post('name'),
         		'email'=>$this->input->post('email'),
                'address'=>$this->input->post('address'),
         		'phone'=>$this->input->post('phone'),
         		'cnic'=>$this->input->post('cnic'),
         		'documents' => $upload_data['file_name']
    	    );
    	}
        $this->vendor_model->update_data($id,$data);
    }else{
        
    	if(!empty($_POST['p_category'])){
       	    for($i = 0; $i < count($_POST['p_category']);$i++){
    		    $category = $_POST['p_category'][$i].",".$category;
            }
            $old_category = $this->input->post('old_category');
            $category = $category.$old_category;
   	    
    	    $data2 = array(
           		'name'=>$this->input->post('name'),
         		'email'=>$this->input->post('email'),
                'address'=>$this->input->post('address'),
         		'phone'=>$this->input->post('phone'),
         		'cnic'=>$this->input->post('cnic'),
         		'category_id' => $category,
    	    );
    	}
    	else{
    	    $data2 = array(
           		'name'=>$this->input->post('name'),
         		'email'=>$this->input->post('email'),
                'address'=>$this->input->post('address'),
         		'phone'=>$this->input->post('phone'),
         		'cnic'=>$this->input->post('cnic'),
    	    );
    	}
    
    	   $this->vendor_model->update_data($id,$data2);
		   
		   
    
   	}

             $logFileName = 'application/logs/newfile.log';
			 $logContent = "admin added a vendor at  ".PHP_EOL;
			 $space = "                                          ".PHP_EOL;
			 $date = new DateTime();
            
			
			
			
			$date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')) 
			{
				fwrite($handle, $logContent);
				fwrite($handle, $date);
				fwrite($handle, $space);
			}
			     fclose($handle);


                   
          	$this->session->set_userdata(array(
           'a_success' => '1')
   );
                     redirect('admin/vendors');
                  
         
  }

         	      	
 
 


	public function index2 ($id = NULL){
	    $data2=array();     
        $data3=array();
		$data['page'] ='edit_vendor';
		$data['menu'] = $this->left_model->get_menu();
		$data['vendor'] = $this->vendor_model->vendor_data_single($id);
		$data['categories'] = $this->vendor_model->get_category_name($id);
		$data['parent'] = $this->categories_model->get_parent();
		$data2['parent2'] = $this->categories_model->get_vendor_categories1($id);
        
        foreach($data2['parent2']as $key){
            if(in_array($key,$data3)){}
                else{
                  array_push($data3,$key);
                }
        }
        $i=0;
        foreach ($data['parent'] as $key){
            if(in_array($key->id,$data3)){
          	  unset($data['parent'][$i]);
          	}
          	$i++;
        }
		$this->load->view('admin/edit_vendor',$data);
	
	
	}
	
	public function update_category_status() {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $statusid = $this->input->post('statusid');
        $this->vendor_model->update_category_status($id,$status,$statusid);
    }




//----------------------------

public function index1() {
      

	    $data['page'] = 'addvendor';
        
		$data['menu'] = $this->left_model->get_menu();
		
		$data['parent'] = $this->categories_model->get_parent();
		$this->load->view('admin/addvendor',$data);
		
		
    }
    
	public function addvendor(){
	     	/*$images_field = 'images';
	     	$thedate=date('Y/n/j h:i:s');
	   	$replace = array(":"," ","/");
	   	$newname=str_ireplace($replace, "-", $thedate);
	     	$config['upload_path'] = './uploads/vendors/';
	   	$config['allowed_types'] = 'gif|jpg|png|txt';
	   	$config['file_name'] = $newname;
	   	$config['max_size'] = '1000';
	   	$config['max_width']  = '';
	   	$config['max_height']  = '';
	   	$config['overwrite'] = TRUE;
	   	$config['remove_spaces'] = TRUE;
	   	
   	$this->load->library('upload', $config);
     	
     	if ( $this->upload->do_upload($images_field)){
    
    	$upload_data = $this->upload->data(); */
    	 
    	$enc_pass=$this->input->post('password');
    	$category = '';
    	$enc_pass1= md5($enc_pass);
    	for($i = 0; $i < count($_POST['p_category']);$i++){
		    $category = $_POST['p_category'][$i].",".$category;
        }
        $category = rtrim($category,",");
        $data = array(
       		'name' => $this->input->post('name'),
     		'email' => $this->input->post('email'),
     		'password' => $enc_pass1,
     		'category_id' => $category,
     		'phone'=>$this->input->post('phone'),
     		'address'=>$this->input->post('address'),
     		'cnic'=>$this->input->post('cnic'),
     		'status'=>'2',
     		//'images' => $upload_data['file_name']
    		);
    	
    
    	$this->vendor_model->setdata($data);
      //}
   	/*else{
    		$data2 = array(
     		'name'=>$this->input->post('name'),
     		'email'=>$this->input->post('email'),
     		'password'=>$this->input->post('password'),
                'address'=>$this->input->post('address'),
     		'phone'=>$this->input->post('phone'),
     		'cnic'=>$this->input->post('cnic'),
     		'status'=>$this->input->post('status'),
     		'image'=>''
    		);
    
    	$this->vendor_model->setdata($data2);
    
   	}*/$logFileName = 'application/logs/newfile.log';
			 $logContent = "admin added a vendor at  ".PHP_EOL;
			 $space = "                                          ".PHP_EOL;
			 $date = new DateTime();
                         $date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')) 
			{
			  fwrite($handle, $logContent);
			  fwrite($handle, $date);
			  fwrite($handle, $space);
			    }
			     fclose($handle);
   
		$this->session->set_userdata(array(
           'a_success' => '1')
   );
		//header('location:'.base_url()."admin/vendors/index?msg=success");
                     redirect('admin/vendors');
    	}
    	
    	
}