<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('vendor/categories_model');
        $this->load->library('form_validation');
	   $this->load->library("pagination");
        $this->perPage = 2;
         if (!$this->session->userdata('is_vendor_login')) {
            redirect('vendor/home');
        }
		
		$this->load->model('vendor/left_model');
		
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
         	$data = array();
         	$data['page'] = 'categories';
         	$data['menu'] = $this->left_model->get_menu();
            $this->load->view('vendor/categories', $data);
        }
        else{
            redirect('vendor/categories/index1');
        }
    }
    
    
    public function searchkey() {
   	$data = array();
   	$j = 0;
	$k = 0;
   	     
		 //$wow = '';
		 if(!empty($this->input->post('searchkey'))){
		 
		 	$wow = $this->input->post('searchkey');
			
		 }else{
		 	
			$wow = $this->input->get('searchkey');
		 
		 }
		 
		
		$sort_order = $this->input->get('sort_order');
		$sort_by= $this->input->get('sort_by');
		$per_page = $this->input->get('per_page');
		
		if($this->categories_model->get_vendor_categories($wow,'','','','')){
			$totalRec = count($this->categories_model->get_vendor_categories($wow,'','','',''));
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
	
				
		$config["base_url"] = base_url() . "vendor/categories/searchkey?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	$categories1 = $this->categories_model->get_vendor_categories($wow, $config["per_page"], $per_page,$sort_by,$sort_order);
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'vendor/categories/searchkey?searchkey='.$wow.'&sort_by=title&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Category</th>
                          <th style="width: 30px;">Status</th>
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
							
                        if ($value['status'] == 1 ){$status = 'check-toggler checked';} else { $status ='check-toggler';}
                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'. $value['title'].'</td>
                          <td style="text-align:center;"><i data="'. $value['id'] .'" data-status="'. $value['status'] .'" class="status_category '.$status.'"></i></td>
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
    
    
    public function index1() {
        $data2=array();     
        $data3=array();
	    $data['page']='categories';
        $data['menu'] = $this->left_model->get_menu();
        $data['parent'] = $this->categories_model->get_parent();
        $data2['parent2'] = $this->categories_model->get_vendor_categories1();
        
        foreach($data2['parent2']as $key){
            if(in_array($key,$data3)){}
                else{
                  array_push($data3,$key);
                }
        }
        $i=0;
        foreach ($data['parent'] as $key){
            if(in_array($key['id'],$data3)){
          	  unset($data['parent'][$i]);
          	}
          	$i++;
        }
		$this->load->view('vendor/addcategory', $data);
	}
    
     public function edit_categories($id = NULL) {
	  
	  	$data['page']='categories';
        
		$data['menu'] = $this->left_model->get_menu();
		
		$data['single_category'] = $this->categories_model->single_category($id);
	
		$this->load->view('admin/editcategory', $data);	
    }


    public function add() {
	  	
	  	$category2 = $this->categories_model->get_vendor_categories2();
  		foreach($category2 as $abc);
  		$category1 = $abc->category_id.','.$this->input->post('p_category').'@';
  		$data = array('category_id' => $category1);
		$this->db->where('id',$_SESSION['vid']);
		$this->db->update('vendors',$data);
		$this->session->set_userdata(array(
	       		'success' => '1'));
		redirect("vendor/categories/");
	}
    
    
     public function update($id = NULL) {	
		
		$images_field = 'images';
		
	  		$thedate=date('Y/n/j h:i:s');
			$replace = array(":"," ","/");
			$newname=str_ireplace($replace, "-", $thedate);
	  			 
	  		$config['upload_path'] = './uploads/categories/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['file_name'] = $newname;
			$config['max_size'] = '1000';
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['overwrite'] = TRUE;
			$config['remove_spaces'] = TRUE;
			
		
			$this->load->library('upload', $config);
	  		
			
			if ( $this->upload->do_upload($images_field)){
				$upload_data = $this->upload->data();
				$data = array(
	  				'title'=>$this->input->post('category_name'),
					'theme'=>$this->input->post('theme'),
					'parent_id'=>"0",
					'description'=>$this->input->post('description'),
					'image' => $upload_data['file_name']
				);
			
				$this->categories_model->update_category($data,$id);
			//$this->categories_model->addcategory($data);
			//$data['message'] = "A record has been added.";
			}
			else{
				$data2 = array(
					'title'=>$this->input->post('category_name'),
					'theme'=>$this->input->post('theme'),
					'description'=>$this->input->post('description'),
					'parent_id'=>"0",
				);
				
				$this->categories_model->update_category($data2,$id);
			}
			
		$logFileName = 'application/logs/newfile.log';
			 $logContent = "admin updated a category at  ".PHP_EOL;
			 $space = "                                          ".PHP_EOL;
			 $date = new DateTime();
                         $date = $date->format("y:m:d h:i:s");
			if ($handle = fopen($logFileName, 'a')) {
			  fwrite($handle, $logContent);
			  fwrite($handle, $date);
			  fwrite($handle, $space);
			}
			fclose($handle);
			$this->session->set_userdata(array(
	       			'a_success' => '1')
			); 
		redirect("admin/categories/");
	}
}