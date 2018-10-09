<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cities extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/cities_model');
        $this->load->library('pagination');
        $this->perPage = 2;
        $this->load->library('form_validation');
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
		
		$this->load->model('admin/left_model');
		
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
	$data['page']='viewcities';
	$data['menu'] = $this->left_model->get_menu();	
	//$data['country1'] = $this->states_model->get_all_sub_countries();
	$this->load->view('admin/viewcities', $data);
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
		
		if($this->cities_model->get_products2($wow,'','','','')){
			$totalRec = count($this->cities_model->get_products2($wow,'','','',''));
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
	
				
		$config["base_url"] = base_url() . "admin/cities/searchkey?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	$categories1 = $this->cities_model->get_products2($wow, $config["per_page"], $per_page,$sort_by,$sort_order);
    	
    	
    	
    	
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'admin/cities/searchkey?searchkey='.$wow.'&sort_by=state_title&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">States</th>
                          <th href="'.base_url() .'admin/cities/searchkey?searchkey='.$wow.'&sort_by=city_title&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">City Tilte</th>
                          <th href="'.base_url() .'admin/cities/searchkey?searchkey='.$wow.'&sort_by=city_code&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">City Code</th>
                          <th href="'.base_url() .'admin/cities/searchkey?searchkey='.$wow.'&sort_by=phone_code&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Phone Code</th>
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
							
                        if ($value['cstatus']== 1 ){$status = 'check-toggler checked';} else { $status ='check-toggler';}
                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'.$value['state_title'].'</td>
                          <td>'.$value['ctitle'].'</td>
                          <td>'. $value['city_code'].'</td>
                          <td>'. $value['phone_code'].'</td>
                          <td style="text-align:center;"><i data="'. $value['cid'] .'" data-status="'. $value['cstatus'] .'" class="status_cities '.$status.'"></i></td>

                          <td style="text-align:center;"><a href="'. base_url().'admin/cities/edit_cities/'. $value['cid'].'" class="fa fa-pencil color-red"></a></td>
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
    
    
    
    
    
     
    public function edit_cities($id = NULL) {
	  
	  	$data['page']='editcities';
        
		$data['menu'] = $this->left_model->get_menu();
		
		$data['parent'] = $this->cities_model->get_parent();
		
		$data['single_state'] = $this->cities_model->single_city($id);
	
	
		$this->load->view('admin/editcities', $data);

    }
    
    
    public function update_status(){
       $data = array('status' => $this->input->post('status') );
       $id = $this->input->post('id');
       $this->cities_model->status_update($id,$data);
       $this->session->set_userdata(array(
		'a_success_status' => '1')
	); 
    }
    
    public function index1() {
      
	  
	    $data['page']='addcities';
        
		$data['menu'] = $this->left_model->get_menu();
		
		$data['parent'] = $this->cities_model->get_parent();
		
		$this->load->view('admin/addcities', $data);
		
		
    }
    
    public function add() {
	 			$data = array(
	  				'city_title'=>$this->input->post('city_title'),
					'city_code'=>$this->input->post('city_code'),
					'phone_code'=>$this->input->post('phone_code'),
					'state_id'=>$this->input->post('state_id')
					
				);
				$this->db->insert('cities',$data);
			
			$logFileName = 'application/logs/newfile.log';
			 $logContent = "admin added a City at  ".PHP_EOL;
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
			redirect("admin/cities/"); 
   
   
   
    }
    
    public function update($id = NULL) {	
		
		//echo $id =$this->input->post('id');
		
		
				$data = array(
	  				'city_title'=>$this->input->post('city_title'),
					'city_code'=>$this->input->post('city_code'),
					'phone_code'=>$this->input->post('phone_code'),
					'state_id'=>$this->input->post('state_id')
					
				);
				
				
				
				$this->cities_model->update_city($data,$id);
				
			
			
		
		
               $this->session->set_userdata(array(
	       			'a_success' => '1')
			); 
			redirect("admin/cities/");	
    }





    
    

}