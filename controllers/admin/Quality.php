<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Quality extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->library('form_validation');
         $this->load->library("pagination");
        
         if (!$this->session->userdata('is_admin_login')) {
            redirect('admin/home');
        }
		
		$this->load->model('admin/left_model');
		$this->load->model('admin/quality_model');
		$this->load->model('admin/categories_model');
		
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
     $data['page'] = 'quality';
     
        $data['menu'] = $this->left_model->get_menu();
        $data['parent'] = $this->categories_model->get_parent();
        
        $data['quality']=$this->quality_model->get_all_quality();
        
        
          $this->load->view('admin/viewquality', $data);       
        
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
		
		if($this->quality_model->get_products1($wow,'','','','')){
			$totalRec = count($this->quality_model->get_products1($wow,'','','',''));
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
	
				
		$config["base_url"] = base_url() . "admin/quality/searchkey?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	$categories1= $this->quality_model->get_products1($wow, $config["per_page"], $per_page,$sort_by,$sort_order);

	
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          
                          <th href="'.base_url() .'admin/quality/searchkey?searchkey='.$wow.'&sort_by=category&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Category</th>
                         
                        
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
						$a=array();
						
						foreach($categories1 as $value){ 
						$i++;
							
                        if ($value['qstatus'] == 1 ){$status = 'check-toggler checked';} else { $status ='check-toggler';}
                      $table .= '  <tr>
                          <td>'.$i.'</td>
                         
                         
                         
                          <td>'. $value['ctitle'].'</td>
                         	 <td style="text-align:center;"><i data="'. $value['qid'] .'" data-status="'. $value['qstatus'] .'" class="status_quality '.$status.'"></i></td>

                          <td style="text-align:center;"><a href="'. base_url().'admin/quality/edit_form/'. $value['id'].'" class="fa fa-pencil color-red"></a></td>
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
    
    
    
    
    
    
    
    
    public function indexadd() {
     $data = array();
     $data['page'] = 'quality';
       
        $data2=array();     
        $data3=array();
     
        $data['menu'] = $this->left_model->get_menu();
        $data['parent'] = $this->categories_model->get_parent();
        $data2['parent2'] = $this->categories_model->get_cat();
        foreach($data2['parent2']as $key){
                
                if(in_array($key->c_id,$data3)){
               
                
                }
                else{
                  array_push($data3,$key->c_id);
                }
        
        
        }
         $i=0;
         foreach ($data['parent'] as $key){
          	if(in_array($key->id,$data3))
          	{
          	  
          	  unset($data['parent'][$i]);
          	
          	}
          	$i++;
          	
                
         }
        
        
        
        
                
        $this->load->view('admin/quality', $data);
    }
     
     public function add(){
       
       $a=$this->input->post('check');
        
        
        $data=array(
        'c_id'=>$this->input->post('p_category'),
         'checklist'=>$this->input->post('c1'),   
         'description'=>$this->input->post('d1'),
         'status'=>"1"   
            
            );
            
      
        
          $this->left_model->addform($data);
           
           
              $this->session->set_userdata(array(
           'a_success' => '1')
   );
       
       header('location:'.base_url()."admin/quality/index");
          
          
          
        if($a==1){
        	$size=count($_POST['c']);
        
        	for($i=0;$i<$size;$i++){
		        $data=array(
        			'c_id'=>$this->input->post('p_category'),
         			'checklist'=>$_POST['c'][$i],   
         			'description'=>$_POST['d'][$i],
         			'status'=>"1"   
            
            		);
          	$this->left_model->addform($data);
        	}
      
       
              $this->session->set_userdata(array(
           'a_success' => '1')
   );
       
       header('location:'.base_url()."admin/quality/index");
      
      
      
        }
        
    }
      public function update_status(){
      $data = array('status' => $this->input->post('status') );
      $id = $this->input->post('id');
       $this->quality_model->status_update($id,$data);
    }
    
    
    
    public function edit_form($cid=NULL){
     $data = array();
     $data['page'] = 'quality';
     
        $data['menu'] = $this->left_model->get_menu();
        $data['questions'] = $this->quality_model->getall_questions($cid);
        
        
        
         $this->load->view('admin/edit_qualityform', $data);
    
    
    }
      
   public function update_form(){
   $a=$this->input->post('check');
   $aa=$this->input->post('c1');
   $bb=$this->input->post('d1');
   if($a==0){
    if(empty($aa) && empty($bb)){
      //echo "Already data ko update karna ";
      $size=count($_POST['cc1']);
      for($i=0;$i<$size;$i++){
         $id123 = $_POST['id'][$i];   
          $data=array(
      			'c_id'=>$_POST['c_id'],
       			'checklist'=>$_POST['cc1'][$i],   
       			'description'=>$_POST['dd1'][$i]
       		);
        $this->quality_model->update1($data,$id123);
        $this->session->set_userdata(array(
           'a_success' => '1')
   );
        header('location:'.base_url()."admin/quality/index");
      }
    }
    else{
      $size=count($_POST['cc1']);
      for($i=0;$i<$size;$i++){
        $id123 = $_POST['id'][$i];
        $data=array(
    			'c_id'=>$_POST['c_id'],
     			'checklist'=>$_POST['cc1'][$i],   
     			'description'=>$_POST['dd1'][$i]
     		);
        $this->quality_model->update1($data,$id123);
        $this->session->set_userdata(array(
           'a_success' => '1')
   );
        header('location:'.base_url()."admin/quality/index");
      }
      $data=array(
        'c_id'=>$this->input->post('c_id'),
        'checklist'=>$aa,   
        'description'=>$bb,
        'status'=>"1"   
      );
      $this->left_model->addform($data);
    }
  }
  else{
    $size=count($_POST['cc1']);
    for($i=0;$i<$size;$i++){
      $id123 = $_POST['id'][$i];
      $data=array(
  			'c_id'=>$_POST['c_id'],
   			'checklist'=>$_POST['cc1'][$i],   
   			'description'=>$_POST['dd1'][$i]
   		);
      $this->quality_model->update1($data,$id123);
      $this->session->set_userdata(array(
           'a_success' => '1')
   );
              header('location:'.base_url()."admin/quality/index");

    }
    $data=array(
      'c_id'=>$this->input->post('c_id'),
      'checklist'=>$aa,   
      'description'=>$bb,
      'status'=>"1"   
    );
    $this->left_model->addform($data);
    $size=count($_POST['c']);
    for($i=0;$i<$size;$i++){
		  $data=array(
  			'c_id'=>$this->input->post('c_id'),
   			'checklist'=>$_POST['c'][$i],   
   			'description'=>$_POST['d'][$i],
   			'status'=>"1"   
      );
      $this->left_model->addform($data);
    }
  }
}   
            
           
   
  }
