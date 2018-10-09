<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Managers extends CI_Controller {

    
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->perPage = 2;
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
		
		$this->load->model('admin/left_model');
		$this->load->model('admin/manager_model');
		
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
     $data['page'] = 'managemanager';
     $data['menu'] = $this->left_model->get_menu();
     
        
        
        $data['manager'] = $this->manager_model->get_all_managers();
        //load the view
	$this->load->view('admin/managemanager',$data);
		
		
    }
    
    
    
    public function searchkey() {
   	$data = array();
   	
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
		
		if($this->manager_model->get_products1($wow,'','','','')){
			$totalRec = count($this->manager_model->get_products1($wow,'','','',''));
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
	
				
		$config["base_url"] = base_url() . "admin/managers/searchkey?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	$categories1= $this->manager_model->get_products1($wow, $config["per_page"], $per_page,$sort_by,$sort_order);
    	
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'admin/managers/searchkey?searchkey='.$wow.'&sort_by=name&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Name</th>
                          <th href="'.base_url() .'admin/managers/searchkey?searchkey='.$wow.'&sort_by=address&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Address</th>
                          <th href="'.base_url() .'admin/managers/searchkey?searchkey='.$wow.'&sort_by=phone&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Phone</th>
                          <th href="'.base_url() .'admin/managers/searchkey?searchkey='.$wow.'&sort_by=email&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Email</th>
                          <th href="'.base_url() .'admin/managers/searchkey?searchkey='.$wow.'&sort_by=categories.title&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Category</th>
                         
                        
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
							
                         if ($value['mstatus'] == 1 ){$status = 'check-toggler checked';} else { $status ='check-toggler';}
                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'. $value['name'].'</td>
                          <td>'. $value['address'].'</td>
                          <td>'. $value['phone'].'</td>
                          <td>'. $value['email'].'</td>
                          <td>'. $value['ctitle'].'</td>
                          
                          
                         
                           <td style="text-align:center;"><i data="'. $value['mid'] .'" data-status="'. $value['mstatus'] .'" class="status_manager '.$status.'"></i></td>

                          <td style="text-align:center;"><a href="'. base_url().'admin/managers/index1/'. $value['mid'].'" class="fa fa-pencil color-red"></a></td>
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
       $this->manager_model->status_update($id,$data);
    }
    
     public function index2() {
      

	    $data['page']='addmanager';
        
		$data['menu'] = $this->left_model->get_menu();
		
		$data['parent'] = $this->manager_model->get_parent();
		
		$this->load->view('admin/addmanager',$data);
		
		
    }
       
	public function editmanager(){
	     
      
                 
        
               
                   $id= $this->input->post('id');
                   $data = array(
	  				'name'=>$this->input->post('name'),
					'email'=>$this->input->post('email'),
                                         'address'=>$this->input->post('address'),
					'phone'=>$this->input->post('phone'),
					'category_id'=>$this->input->post('p_category'),
					
					
				);


                   $this->manager_model->update_data($id,$data);
                   $logFileName = 'application/logs/newfile.log';
			 $logContent = "admin updated a manger at  ".PHP_EOL;
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
                         
                         redirect('admin/managers/index');
                  
         
                   
                     
                      
                  
 
              } 

                  public function index1 ($id = NULL)
    {
                              $data['page']='edit_manager';
		              $data['menu'] = $this->left_model->get_menu();
		              $data['parent'] = $this->manager_model->get_parent();
                              $data['manager']=$this->manager_model->manager_data_single($id);
                              $this->load->view('admin/edit_manager',$data);
      
    }
    
 public function ifexist($value){
                     $this->db->select('email');
                     $this->db->from('managers');
	             $this->db->where('email', $value);
		     $query = $this->db->get();
		     
		     
		     return $query->num_rows();
     }



    public function addmanager()
    {
       $email=$this->input->post('email');
           $a=$this->ifexist($email);
           
           
	if($a==0){
	
	
    	       			$enc_pass=$this->input->post('password');
    				$enc_pass1= md5($enc_pass);
				$data = array(
	  				'name'=>$this->input->post('name'),
					'email'=>$this->input->post('email'),
					'password'=>$enc_pass1,
                    			'address'=>$this->input->post('address'),
					'phone'=>$this->input->post('phone'),
					'category_id'=>$this->input->post('p_category'),
					'status'=>$this->input->post('status')
					
				);
				
				$this->manager_model->setdata($data);
				$logFileName = 'application/logs/newfile.log';
			 $logContent = "admin added a manager at  ".PHP_EOL;
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
                         
                         redirect('admin/managers/index');
	
	
	}else{
           header('location:'.base_url()."admin/managers/index2?msg=success");
    
    
             
   
    }
    
    
    
    
    
    }
    
    
    
    
    

}