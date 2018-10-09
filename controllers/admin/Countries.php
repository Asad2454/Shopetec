<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Countries extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin/countries_model');
        $this->load->library('form_validation');
	   $this->load->library("pagination");
        $this->perPage = 2;
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
     	$data['page'] = 'viewcountries';
     	$data['menu'] = $this->left_model->get_menu();
     	$data['countries'] = $this->countries_model->get_countries();
        $this->load->view('admin/viewcountries', $data);
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
		
		if($this->countries_model->get_products1($wow,'','','','')){
			$totalRec = count($this->countries_model->get_products1($wow,'','','',''));
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
	
				
		$config["base_url"] = base_url() . "admin/countries/searchkey?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	$categories1= $this->countries_model->get_products1($wow, $config["per_page"], $per_page,$sort_by,$sort_order);
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'admin/countries/searchkey?searchkey='.$wow.'&sort_by=country_code&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Country Code</th>
                          <th href="'.base_url() .'admin/countries/searchkey?searchkey='.$wow.'&sort_by=country_title&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Country Title</th>
                          <th href="'.base_url() .'admin/countries/searchkey?searchkey='.$wow.'&sort_by=phone_code&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Phone Code</th>
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
							
                        if ($value['status'] == 1 ){$status = 'check-toggler checked';} else { $status ='check-toggler';}
                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'. $value['country_code'].'</td>
                          <td>'. $value['country_title'].'</td>
                          <td>'. $value['phone_code'].'</td>
                          <td style="text-align:center;"><i data="'. $value['id'] .'" data-status="'. $value['status'] .'" class="status_countries '.$status.'"></i></td>

                          <td style="text-align:center;"><a href="'. base_url().'admin/countries/edit_countries/'. $value['id'].'" class="fa fa-pencil color-red"></a></td>
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

       $this->countries_model->status_update($id,$data);
	$this->session->set_userdata(array(
		'a_success_status' => '1')
	); 
    }
    
    public function index1() {
    
	    $data['page']='addcountry';
        
		$data['menu'] = $this->left_model->get_menu();
		
		
		$this->load->view('admin/addcountry', $data);
		
		
    }
    
     public function edit_countries($id = NULL) {
	  
	  	$data['page']='editcountry';
        
		$data['menu'] = $this->left_model->get_menu();
		
		$data['countries'] = $this->countries_model->single_country($id);
	
		$this->load->view('admin/editcountry', $data);	
    }


 public function add(){
	 
				$data = array(
	  				'country_code'=>$this->input->post('country_code'),
					'country_title'=>$this->input->post('country_title'),
					'phone_code'=>$this->input->post('phone_code'),
					'status'=>$this->input->post('status')
					
					);
			              
				$this->db->insert('countries',$data);
			
			
		
			$logFileName = 'application/logs/newfile.log';
			$logContent = "admin added a country at  ".PHP_EOL;
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
			redirect("admin/countries/");
	}
    
    
     public function update($id = NULL) {	
		
		
				$id=$this->input->post('id');
				
				$data = array(
	  				'country_code'=>$this->input->post('country_code'),
					'country_title'=>$this->input->post('country_title'),
					'phone_code'=>$this->input->post('phone_code'),
					'status'=>"1"
					
					);
			
				$this->countries_model->update_countries($data,$id);
			
			
			
		$logFileName = 'application/logs/newfile.log';
			 $logContent = "admin updated a country at  ".PHP_EOL;
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
		redirect("admin/countries/");
	}
}