<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stock extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('pagination');
         if (!$this->session->userdata('is_vendor_login')) {
            redirect('vendor/home');
        }
	$this->load->model('vendor/left_model');
	$this->load->model('vendor/stock_model');
		
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
        	$data['page']='stock';
        	$data['menu'] = $this->left_model->get_menu();
        	$data['stocks'] = $this->stock_model->get_all_stocks($this->session->userdata('vid'));
        	$this->load->view('vendor/stock',$data);
        }
        else{
            redirect('vendor/categories/index1');
        }
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
     		
		if($this->stock_model->get_products1($wow,'','','','')){
			$totalRec = count($this->stock_model->get_products1($wow,'','','',''));
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
	
				
		$config["base_url"] = base_url() . "vendor/stock/searchkey?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	$stocks = $this->stock_model->get_products1($wow, $config["per_page"], $per_page,$sort_by,$sort_order);
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'vendor/stock/searchkey?searchkey='.$wow.'&sort_by=products.title&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Products</th>
                          <th href="'.base_url() .'vendor/stock/searchkey?searchkey='.$wow.'&sort_by=price&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Category</th>
                          <th>Attribute</th>
                          <th href="'.base_url() .'vendor/stock/searchkey?searchkey='.$wow.'&sort_by=quantity&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Quantity</th>
                          <th style="width: 30px;">Action</th>
                        </tr>
                      </thead>
                      <tbody>';
                      if(!empty($stocks)){
					  	
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
						foreach($stocks as $value){ 
						
					
                            		$dataabc = $this->stock_model->single_attribute1($value['attribute_id']);
                            		$abc = '';
                            		foreach($dataabc as $key3){
                            			$abc .= $key3->title." | ";
                            		}
					$wowow = rtrim($abc,'| ');
					$i++;
							
                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'. $value['ptitle'].'</td>
                          <td>'. $value['ctitle'].'</td>
                          <td>'. $wowow.'</td>
                          <td>'. $value['quantity'].'</td>
                          <td style="text-align:center;"><a href="'.base_url().'vendor/Stock/edit_stock/'. $value['apid'].'" class="fa fa-pencil color-red"></a></td>

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








    public function edit_stock($id=NULL){
    
          $data['page']='edit_stock';
	  $data['menu'] = $this->left_model->get_menu();
	
	$data['stock'] = $this->stock_model->get_quantity($id);
	$this->load->view('vendor/edit_stock',$data);
    }
    
    public function edit_quantity(){
                $id =$this->input->post('id');
                $stock = $this->stock_model->get_quantity($id);
                foreach($stock as $value){
                	$quantity = $value->quantity;
                }
                $data = $this->input->post('quantity');
                if($quantity >= $data ){
                	$this->stock_model->update_quantity($id,$data);
			$this->session->set_userdata(array(
		       		'success' => '1')
			);
			redirect('vendor/stock');
		}
                else{
                $this->session->set_userdata(array(
		       		'success' => '0')
			);
		redirect('vendor/stock');
                }   		
    }


    

}