<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Products extends CI_Controller {

     public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
                $this->load->library('pagination');
                $this->load->library('email');
         if (!$this->session->userdata('is_vendor_login')) {
            redirect('vendor/home');
        }
	$this->load->model('vendor/left_model');
	$this->load->model('vendor/products_model');
	$this->load->model('vendor/categories_model');
	$this->load->model('vendor/register_model');
	
		
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
        if($_SESSION['category'] != 0){
            $id = $this->session->userdata('vid');
            $productdata  = $this->products_model->get_products($id);
        	$data['productList'] = $productdata;
        	$data['page']='productList';
        	$data['menu'] = $this->left_model->get_menu();
        	$this->load->view('vendor/products/productList',$data);
        }
        else{
            redirect('vendor/categories/index1');
        }
    }
    
    public function searchkey() {
    	
    	$id = $this->session->userdata('vid');
   	$data = array();
   	     	$j = 0;
   	     	$k = 0;
   	     	$status1=0;
		$wow = '';
		 if(!empty($this->input->post('searchkey'))){
		 
		 	$wow = $this->input->post('searchkey');
			
		 }else{
		 	
			$wow = $this->input->get('searchkey');
		 
		 }
		
		$sort_order = $this->input->get('sort_order');
		$sort_by= $this->input->get('sort_by');
		$per_page = $this->input->get('per_page');
     		
		if($this->products_model->get_products1($wow,'','','','',$id)){
			$totalRec = count($this->products_model->get_products1($wow,'','','','',$id));
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
	
				
		$config["base_url"] = base_url() . "vendor/Products/searchkey?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	$productlist = $this->products_model->get_products1($wow, $config["per_page"], $per_page,$sort_by,$sort_order,$id);
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'vendor/Products/searchkey?searchkey='.$wow.'&sort_by=title&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Products Title</th>
                          <th href="'.base_url() .'vendor/Products/searchkey?searchkey='.$wow.'&sort_by=price&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Price</th>
                          <th style="width: 30px;">Status</th>
                          <th style="width: 30px;">Action</th>
                          <th style="width: 30px;">Quality</th>
                        </tr>
                      </thead>
                      <tbody>';
                      if(!empty($productlist)){
					  	
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
						foreach($productlist as $value){ 
						$i++;
					   //
					   //style="color:#f4b942;
					   //style="color:#848484;	
                        if ($value['pstatus'] == 1 ){$pstatus = 'check-toggler checked';} else if ($value['pstatus'] == 2 ){$pstatus = 'fa fa-pause pause';} else { $pstatus ='check-toggler';}
                        if ($value['pstatus'] == 1 ){$display1 = "Uploaded";} else if($value['pstatus'] == 0 ) {$display1 = "Pending";} else {$display1 = "Approved";}
                        if ($value['pstatus'] == 2){ $status1= 'style="color:#f4b942"'; } 
                        if ($value['sample'] == 1 ){$cube = "sample_delivered fa fa-cube";} else if($value['sample'] == 0) {$cube = "fa fa-cube";}  else {$cube = "fa fa-cube";}
                        if ($value['sample'] == 1 ){$display = "Deliver Sample";} else if($value['sample'] == 0 ) {$display = "No Action";} else {$display = "Sample Delivered";}
                        if ($value['sample'] == 1 ){$color = "color:#f4b942";} else if($value['sample'] == 0 ) {$color = "color:#FE4A43";} else {$color = "color:#A2D200";}



                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'. $value['title'].'</td>
                          <td>'. $value['price'].'</td>
                          <td style="text-align:center;"><i data="'. $value['pid'] .'" title="'.$display1.'" data-status="'. $value['pstatus'] .'" '.$status1.' class="status_products '.$pstatus.'"></i></td>
                          <td style="text-align:center;"><a title="'.$display.'" data="'.$value['pid'].'" style="'.$color.'" class="'.$cube.'"></a></td>   
                          <td style="text-align:center;"><a title="View Quality Form" href="'.base_url().'vendor/products/viewquality/'.$value['pid'].'" class="fa fa-eye" style="color:#FA8258;"></a></td>

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
    
     public function sample_delivered(){
       $data = array('sample' => '2');
       $id = $this->input->post('id');
       
	$this->session->set_userdata(array(
		'success_deliver' => '1')
	);      
      $wow=$_SESSION['category'];
      $iddd=$this->register_model->manager_data_single_by_category($wow);
      $khali='';
      foreach($iddd as $iid){
     echo $khali=$iid->id;
      }
       $data3 = array(
       		'type'=>"7",
     		'message'=>"Sample has been Delivered",
     		'user'=>"2",
     		'user_id'=>$khali,
     		'date'=>date("Y/m/d"),
     		'time' => date("H:i:s"),
             	'status'=>"1",
             	'hit_id'=>$id
     		);
     		    	$this->register_model->notification($data3);
     		    	 $this->products_model->sample_delivered($id,$data);
     		    	 
     		    	  $email=$this->register_model->get_manager_email($khali);
         foreach($email as $e){
         
         $sql = $this->register_model->get_email9();
          
        
        foreach($sql as $data){
          
             $email= $e->email;       
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
    
   public function index1() {
       	$id = $this->session->userdata('vid');
    	$productdata  = $this->Products_model->get_products($id);
		$data['productList'] = $productdata;
		$data['page']='productList';
		$data['menu'] = $this->left_model->get_menu();
		$this->load->view('vendor/products/addproduct',$data);
    }
    
    

   public function addProduct(){
   

    	$this->form_validation->set_rules('product_title', 'Title', 'required');
    	
    	if ($this->form_validation->run() == true) {
            $number_of_files = sizeof($_FILES['userfile']['tmp_name']);
            $files = $_FILES['userfile'];
            $this->load->library('upload');
            $config['upload_path'] = './uploads/products/';
            $config['allowed_types'] = 'gif|jpg|png';
            for ($i = 0; $i < $number_of_files; $i++){
                $_FILES['userfile']['name'] = $files['name'][$i];
                $_FILES['userfile']['type'] = $files['type'][$i];
                $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['userfile']['error'] = $files['error'][$i];
                $_FILES['userfile']['size'] = $files['size'][$i];
                $this->upload->initialize($config);
                if ($this->upload->do_upload('userfile')){
                    $this->_uploaded[$i] = $this->upload->data();
                }else{
                       
                    $data = array(
                    	'user_id' => $_SESSION['vid'],
                        'sku' => $this->input->post('sku'),
                        'category' => $this->input->post('products_category'),
                        'title' => $this->input->post('product_title'),
                        'short_description' => $this->input->post('short_description'),
                        'long_description' => $this->input->post('long_description'),
                        'price' => $this->input->post('products_price'),
                        'seo_url' => $this->input->post('seo_url'),
                        'status' => '0'
                    );
                    $id123 = $this->products_model->add_products($data);
                    $id=$this->products_model->getLastInserted();
                    $check = $this->input->post('gen');
			
                	if($check == 1){
			for($i = 0; $i < count($_POST['ids']);$i++){
				$data = array(
                    			'product_id' => $id123,
                        		'attribute_id' => $_POST['ids'][$i],
                        		'quantity' => $_POST['quantity'][$i],
                        		'status' => "1"
                        		 
                        	);
                       	$this->products_model->add_product_attribute($data);
			}  
			}
			$data2 = array(
       		'type'=>"2",
     		'message'=>"Vendor added product",
     		'user'=>"0",
     		'user_id'=>$this->session->userdata('vid'),
     		'date'=>date("Y/m/d"),
     		'time' => date("H:i:s"),
             	'status'=>"1",
             	'hit_id'=>$id
     		);
     		
     		
     		    	$this->register_model->notification($data2);
     		    	$data3 = array(
       		'type'=>"6",
     		'message'=>"Vendor added product",
     		'user'=>"2",
     		'user_id'=>$this->session->userdata('vid'),
     		'date'=>date("Y/m/d"),
     		'time' => date("H:i:s"),
             	'status'=>"1",
             	'hit_id'=>$id
     		);
     		    	$this->register_model->notification($data3);
     		    	
     		    	
     		    	
     		    	
     		    	 $email=$this->register_model->get_admin_email();
         foreach($email as $e){
         
         $sql = $this->register_model->get_email5();
          
        
        foreach($sql as $data){
          
             $email= $e->email;       
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
     		    	
     		    	 $email=$this->register_model->get_manager_email1($this->session->userdata('id'));
         foreach($email as $e){
         
         $sql = $this->register_model->get_email5();
          
        
        foreach($sql as $data){
          
             $email= $e->email;       
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
	    	$this->session->set_userdata(array(
	       		'success' => '1')
		); 
		redirect('vendor/products');
                	               }
            }
            
	    	$data = array(
	    	    'user_id' => $_SESSION['vid'],
	            'sku' => $this->input->post('sku'),
	            'category' => $this->input->post('products_category'),
	            'title' => $this->input->post('product_title'),
	            'short_description' => $this->input->post('short_description'),
	            'long_description' => $this->input->post('long_description'),
	            'price' => $this->input->post('products_price'),
	            'seo_url' => $this->input->post('seo_url'),
	            'status' => '0'
	        );
	        $id = $this->products_model->add_products($data);
	       $id=$this->products_model->getLastInserted(); 
	        $check = $this->input->post('gen');
		
               	if($check == 1){
	        for($i = 0; $i < count($_POST['ids']); $i++){
				$data = array(
                    			'product_id' => $id,
                        		'attribute_id' => $_POST['ids'][$i],
                        		'quantity' => $_POST['quantity'][$i],
                        		'status' => "1"
                        	);
                $this->products_model->add_product_attribute($data);
			}
		}
    		$dates = $this->_uploaded;
    		foreach ($dates as $key => $value) {
                $dataimg = array('images'=> $value['file_name'],'product_id'=>$id);
             	$this->products_model->add_products_image($dataimg);
            }
            	/*$data1 = array(
                        	'vendor_id' => $this->session->userdata('id'),
                        	'product_id' => $id
                    );
			$this->products_model->add_vendor_product($data1); */
			
			$data2 = array(
       		'type'=>"2",
     		'message'=>"Vendor added product",
     		'user'=>"0",
     		'user_id'=>$this->session->userdata('vid'),
     		'date'=>date("Y/m/d"),
     		'time' => date("H:i:s"),
             	'status'=>"1"
     		);
     		    	$this->register_model->notification($data2);
     		    	    	$data3 = array(
       		'type'=>"6",
     		'message'=>"Vendor added product",
     		'user'=>"2",
     		'user_id'=>$this->session->userdata('vid'),
     		'date'=>date("Y/m/d"),
     		'time' => date("H:i:s"),
             	'status'=>"1",
             	'hit_id'=>$id
     		);
     		    	$this->register_model->notification($data3);
     		    	
     		    	
     		    	
     		    	
     		    	
     		    	
     		    	
     		    	
     		    	
     		    	
     		    	
     		    	
     		    	
     		    	
     		    	

			
        $this->session->set_userdata(array(
	       		'success' => '1')
		); 
		redirect('vendor/products');    	
    	    
    	}else{
    	    $data['categories'] = $this->categories_model->get_all_categories22();
            $data['subcategories'] = $this->categories_model->get_all_sub_categories();
            $data['attributevalueList'] = $this->products_model->get_attribute_value();
    		$data['page']='addproduct';
			$data['menu'] = $this->left_model->get_menu();
			$this->load->view('vendor/products/addproduct',$data);
    	}
    }
    
     public function product_attribute(){
     
     	
        $data = array(
		'sku' => $this->input->post('produtscode')
	);
	            
	            
    }
    
	public function get_titlebyattributeid($id = NULL){
        	$result = $this->Products_model->get_attribute_valuebyid($id);
    		echo json_encode($result);
        }
        
        public function check_attribute($id = NULL){
        	$wow = explode("a",$id);
        	$kji = 0;
        	$result = $this->Products_model->attribute_products($wow[0]);
        	foreach($result as $key){
		$ijk = explode(",",$key->attribute_id);
			for ($i = 0, $len = count($ijk); $i < $len; $i++) {
  				$kji += $ijk[$i];
  			}
			if($kji == $wow[1]){
				$result = "no";
			}
			$kji = 0;
		}
    		echo json_encode($result);
        }

	public function check_seo_url(){
		$value1 = '';
	       $value = $this->input->post('value');
	       $data = $this->products_model->check_seo_url($value);
	       if(!empty($data)){
	       		foreach($data as $key){
	       			$seo = $key->seo_url;
		       		$wow = explode("-", $key->seo_url);
		       		end($wow);
				$key = $wow[key($wow)];
		       		if (is_numeric($key)){
					$xyz = $key + 1;
					$value1 = $this->str_replace_last($key,$xyz,$seo);
				}
				else{
					$value1 = $value.'-'.'0'; 
				}
				break;
			}
			echo $value1; 
	       }
	       else{
	       		echo $value;
	       }
	
	}
	
	public function str_replace_last( $search , $replace , $str ) {
    		if( ( $pos = strrpos( $str , $search ) ) !== false ) {
        		$search_length  = strlen( $search );
        		$str    = substr_replace( $str , $replace , $pos , $search_length );
    		}
    	return $str;
	}
    
    public function update_status_products(){
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $this->Products_model->status_update_products($id,$data);
    }
    
     public function get_groupbycategoryid($id){
    	$result = $this->products_model->get_groupcategoryid($id);
    	echo json_encode($result);
  	 	
}

public function get_valuesbygroupid($id){
    	$result1 = $this->products_model->get_valuesbygroupid($id);
    	echo json_encode($result1);
  	 	
}

    public function Attribute_group(){
    	$attributegroup  = $this->Products_model->get_attribute_group();
		$data['attributegroupList'] = $attributegroup;
		$data['page']='attribute_groupList';
		$data['menu'] = $this->left_model->get_menu();
		$this->load->view('admin/attributegroup/attribute_groupList',$data);
    }

    public function add_attribute_group(){
    	$this->form_validation->set_rules('productgroup_title', 'Products Title', 'required');
    	if ($this->form_validation->run() == true) {
	    	$data = array(
                	'title' => $this->input->post('productgroup_title'),
                	'category_id' => $this->input->post('products_category'),
	        	'status' => 1,
	        );
	        $this->Products_model->add_attribute_group($data);
	        redirect('admin/Products/Attribute_group');
	    }else{
	    	$data['page']='addattribute_group';
			$data['menu'] = $this->left_model->get_menu();
			$data['categories'] = $this->Categories_model->get_all_categories();
            		$data['subcategories'] = $this->Categories_model->get_all_sub_categories();
			$this->load->view('admin/attributegroup/addattribute_group',$data);
		}
    }

    public function Edit_attribute_group($id){
    	$attributedata = $this->Products_model->get_attribute_groupbyid($id);
    	$data['attributeproductEdit'] = $attributedata;
    	$data['page']='attribute_groupEdit';
		$data['menu'] = $this->left_model->get_menu();
		$this->load->view('admin/attributegroup/attribute_groupEdit',$data);
    }
    
    public function delete_attribute($id = NULL){
    	
	$this->db->where('id', $id);
   	$this->db->delete('attribute_product');     	
    	}
    	
    	public function delete_product_image($id = NULL){
   	$res = $this->Products_model->productsimgbyid($id);
	$upload_path = 'uploads/products/';
   	foreach($res as $key){
   		unlink($upload_path.$key->images);
   	}
	$this->db->where('id', $id);
   	$this->db->delete('product_images');
   	}

    public function upate_attribute_group(){
    	$id = $this->input->post('groupid');
    	$data = array(
            'title' => $this->input->post('productgroup_title'),
	    );
	    $this->Products_model->update_attribute_group($id,$data);
                header('location:'.base_url()."admin/products/Edit_attribute_group/$id?msg=success");
    }

    public function Attribute_value(){
    	$attributevalue  = $this->Products_model->get_attribute_value();
		$data['attributevalueList'] = $attributevalue ;
    	$data['page']='attribute_groupList';
		$data['menu'] = $this->left_model->get_menu();
		$this->load->view('admin/attributevalue/attribute_valueList',$data);
    }

    public function add_attribute_value(){
    	$this->form_validation->set_rules('value_title', 'Products Title', 'required');
    	if ($this->form_validation->run() == true) {
    		$data['img'] = $_FILES['img']['name'];
    		$config['upload_path'] = './assets/admin/images/productsvalue/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '1000';
            $config['max_width']  = '';
            $config['max_height']  = '';
            $config['overwrite'] = TRUE;
            $config['remove_spaces'] = TRUE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('img')){
            $data = array(
	            'title' => $this->input->post('value_title'),
	            'group_id' => $this->input->post('products_attri_group'),
	            'status' => 1,
	        );
	        $this->Products_model->add_attribute_value($data);
	        redirect('admin/Products/Attribute_value');
        }else{
           $image_data = $this->upload->data();
        }
	    	$data = array(
	            'title' => $this->input->post('value_title'),
	            'group_id' => $this->input->post('products_attri_group'),
	            'image' => $image_data['file_name'],
	            'status' => 1,
	        );
	        $this->Products_model->add_attribute_value($data);
	        redirect('admin/Products/Attribute_value');
	    }else{
            $attributegroup  = $this->Products_model->get_attribute_group();
            $data['attributegroupList'] = $attributegroup;
	    	$data['page']='addattribute_group';
			$data['menu'] = $this->left_model->get_menu();
			$this->load->view('admin/attributevalue/addattribute_value',$data);
		}
    }
    
    public function update_status_attrigroup(){
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $this->Products_model->status_update_attrigroups($id,$data);
    }
    
    public function Edit_attribute_value($id){
    	$attributevalue  = $this->Products_model->get_attribute_valuebyid($id);
		$data['attributevalueEdit'] = $attributevalue ;
        $attributegroup  = $this->Products_model->get_attribute_group();
        $data['attributegroupEdit'] = $attributegroup;
    	$data['page']='attribute_groupEdit';
		$data['menu'] = $this->left_model->get_menu();
		$this->load->view('admin/attributevalue/attribute_valueEdit',$data);
    }

    public function update_attribute_value(){
    	$id = $this->input->post('valueid'); 
    	$logoArray = array();
		$data['img'] = $_FILES['img']['name'];
		$config['upload_path'] = './assets/admin/images/productsvalue/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';
        $config['max_width']  = '';
        $config['max_height']  = '';
        $config['overwrite'] = TRUE;
        $config['remove_spaces'] = TRUE;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('img')){
            $error = array('error' => $this->upload->display_errors());
        }else{
           	$image_data = $this->upload->data();
        }
        if(!empty($_FILES['img']['name'])) {  
            $data['img'] = $_FILES['img']['name'];
            $logoArray = array('image' => $image_data['file_name']);
        }
    	$data = array(
            'title' => $this->input->post('value_title'),
            'group_id' => $this->input->post('products_attri_group'),
        );
        $finaldata = array_merge($data,$logoArray);
        $this->Products_model->update_attribute_value($id,$finaldata);
                header('location:'.base_url()."admin/products/Edit_attribute_value/$id?msg=success");
    }
    
    public function update_status_attrivalue(){
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $this->Products_model->status_update_attrivalues($id,$data);
    }
    
     	public function viewquality($id = NULL){
		$data['menu'] = $this->left_model->get_menu();
     		$data['questions']=  $this->products_model->getall_comments($id);
     		$this->load->view('vendor/viewquality',$data);

     	}
}

/* End of file Products.php */
/* Location: ./application/controllers/Products.php */