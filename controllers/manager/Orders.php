<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('pagination');
         if (!$this->session->userdata('is_manager_login')) {
            redirect('manager/home');
        }
	$this->load->model('manager/left_model');
	$this->load->model('manager/orders_model');
	$this->load->model('manager/categories_model');
		
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
    
    public function invoice($id = NULL) {
    	$data['page']='View Invoice';
    	$data['menu'] = $this->left_model->get_menu();
    	$data['order_details'] = $this->orders_model->vieworder($id);
    	$this->load->view('manager/invoice',$data);
    }
    
    public function vieworder($id = NULL) {
    	$data['page']='View Orders';
    	$data['menu'] = $this->left_model->get_menu();
    	$data['order_details'] = $this->orders_model->vieworder($id);
    	$data['ids'] = $this->categories_model->get_single_sub_categories();
 		$ids = array();
 		foreach($data['ids'] as $key){
 			array_push($ids,$key['id']);
 		}
    	$data['order_details1'] = $this->orders_model->vieworder1($id,$ids);
    	$this->load->view('manager/vieworder',$data);
    }
    
    public function update_orders_status() {
        $data['ids'] = $this->categories_model->get_single_sub_categories();
        $ids = array();
 		foreach($data['ids'] as $key){
 			array_push($ids,$key['id']);
 		}
 		array_push($ids,$_SESSION['mcategory']);
    	$id = $this->input->post('id');
    	$status = $this->input->post('status');
    	$data = array('status' => $status);
    	$this->orders_model->update_orders_status($id,$data,$ids);
    	$this->session->set_userdata(array(
	        'm_success' => '1')
		); 
    }
    
    public function neworders() {
    	$data['page']='New Orders';
    	$data['menu'] = $this->left_model->get_menu();
        $this->load->view('manager/neworders',$data);
    }

    public function searchkeynew() {
    	
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
     	$data['ids'] = $this->categories_model->get_single_sub_categories();
 		$ids = array();
 		foreach($data['ids'] as $key){
 			array_push($ids,$key['id']);
 		}
 		array_push($ids,$_SESSION['mcategory']);
		if($this->orders_model->neworders($wow,'','','','',$ids)){
			$totalRec = count($this->orders_model->neworders($wow,'','','','',$ids));
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
	
				
		$config["base_url"] = base_url() . "manager/orders/searchkeynew?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	    $orders = $this->orders_model->neworders($wow, $config["per_page"], $per_page,$sort_by,$sort_order,$ids);
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'manager/orders/searchkeynew?searchkey='.$wow.'&sort_by=order_id&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Order ID</th>
                          <th href="'.base_url() .'manager/orders/searchkeynew?searchkey='.$wow.'&sort_by=customer.name&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Customer</th>
                          <th href="'.base_url() .'manager/orders/searchkeynew?searchkey='.$wow.'&sort_by=vendors.name&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Vendor</th>
                          <th href="'.base_url() .'manager/orders/searchkeynew?searchkey='.$wow.'&sort_by=date&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Date</th>
                          <th style="width: 30px;">Status</th>
                          <th style="width: 30px;">Action</th>
                        </tr>
                      </thead>
                      <tbody>';
                      if(!empty($orders)){
					  	
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
						foreach($orders as $value){ 
					    
					    $i++;
						if ($value['ostatus'] == "hold" ){$pstatus = 'check-toggler';} else {$pstatus = 'check-toggler checked';}

                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'. $value['order_id'].'</td>
                          <td>'. $value['fname']." ". $value['lname'].'</td>
                          <td>'. $value['vname'].'</td>
                          <td>'. $value['date'].'</td>
                          <td style="text-align:center;"><i data-status="'.$value['ostatus'].'" data-id="'.$value['order_id'].'" title="'. $value['ostatus'].'" class="status_neworder '.$pstatus.'"></i></td>
                          <td style="text-align:center;"><a href="'. base_url().'manager/orders/vieworder/'. $value['order_id'].'" title="View Order" style="color:#f4b942;" class="fa fa-eye"></a></td>

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
    
    public function inprogress() {
    	$data['page']='In-Progress Orders';
    	$data['menu'] = $this->left_model->get_menu();
    	$this->load->view('manager/inprogressorders',$data);
    }

     public function searchkeyprogress() {
    	
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
     		$data['ids'] = $this->categories_model->get_single_sub_categories();
 		$ids = array();
 		foreach($data['ids'] as $key){
 			array_push($ids,$key['id']);
 		}
 		array_push($ids,$_SESSION['mcategory']);
		if($this->orders_model->processorders($wow,'','','','',$ids)){
			$totalRec = count($this->orders_model->processorders($wow,'','','','',$ids));
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
	
				
		$config["base_url"] = base_url() . "manager/orders/searchkeyprogress?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	    $orders = $this->orders_model->processorders($wow, $config["per_page"], $per_page,$sort_by,$sort_order,$ids);
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'manager/orders/searchkeyprogress?searchkey='.$wow.'&sort_by=order_id&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Order ID</th>
                          <th href="'.base_url() .'manager/orders/searchkeyprogress?searchkey='.$wow.'&sort_by=customer.name&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Customer</th>
                          <th href="'.base_url() .'manager/orders/searchkeyprogress?searchkey='.$wow.'&sort_by=vendors.name&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Vendor</th>
                          <th href="'.base_url() .'manager/orders/searchkeyprogress?searchkey='.$wow.'&sort_by=date&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Date</th>
                          <th class="text-center" style="width: 90px;">Status</th>
                          <th style="width: 30px;">Action</th>
                        </tr>
                      </thead>
                      <tbody>';
                      if(!empty($orders)){
					  	
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
						foreach($orders as $value){ 
					    if ($value['ostatus'] == "new" ){$pstatus = 'Waiting';} else if ($value['ostatus'] == "process" ) {$pstatus = 'In-Process';} else if ($value['ostatus'] == "received" ) {$pstatus = 'Received';}
					    $i++;
							
                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'. $value['order_id'].'</td>
                          <td>'. $value['fname']." ". $value['lname'].'</td>
                          <td>'. $value['vname'].'</td>
                          <td>'. $value['date'].'</td>
                          <td style="text-align:center;"><i data-status="'.$value['ostatus'].'" data-id="'.$value['order_id'].'" title="'. $value['ostatus'].'"></i>'.$pstatus.'</td>
                          <td style="text-align:center;"><a href="'. base_url().'manager/orders/vieworder/'. $value['order_id'].'" title="View Order" style="color:#f4b942;" class="fa fa-eye"></a></td>
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
    
    public function complete() {
    	$data['page']='Canceled Orders';
    	$data['menu'] = $this->left_model->get_menu();
    	$this->load->view('manager/completeorders',$data);
    }

    public function searchkeycomplete() {
    	
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
     	$data['ids'] = $this->categories_model->get_single_sub_categories();
 		$ids = array();
 		foreach($data['ids'] as $key){
 			array_push($ids,$key['id']);
 		}		
		if($this->orders_model->processcomplete($wow,'','','','',$ids)){
			$totalRec = count($this->orders_model->processcomplete($wow,'','','','',$ids));
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
	
				
		$config["base_url"] = base_url() . "manager/orders/searchkeycomplete?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	    $orders = $this->orders_model->processcomplete($wow, $config["per_page"], $per_page,$sort_by,$sort_order,$ids);
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'manager/orders/searchkeycomplete?searchkey='.$wow.'&sort_by=order_id&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Order ID</th>
                          <th href="'.base_url() .'manager/orders/searchkeycomplete?searchkey='.$wow.'&sort_by=customer.name&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Customer</th>
                          <th href="'.base_url() .'manager/orders/searchkeycomplete?searchkey='.$wow.'&sort_by=vendors.name&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Vendor</th>
                          <th href="'.base_url() .'manager/orders/searchkeycomplete?searchkey='.$wow.'&sort_by=date&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Date</th>
                          <th style="width: 30px;">Status</th>
                          <th style="width: 30px;">Action</th>
                        </tr>
                      </thead>
                      <tbody>';
                      if(!empty($orders)){
					  	
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
						foreach($orders as $value){ 
					    if ($value['ostatus'] == "delivered" ){$pstatus = 'check-toggler';} else if ($value['ostatus'] == "complete" ) {$pstatus = 'check-toggler checked';}
					    $i++;
					

                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'. $value['order_id'].'</td>
                          <td>'. $value['fname']." ". $value['lname'].'</td>
                          <td>'. $value['vname'].'</td>
                          <td>'. $value['date'].'</td>
                          <td style="text-align:center;"><i data-status="'.$value['ostatus'].'" data-id="'.$value['order_id'].'" title="'. $value['ostatus'].'" class="'.$pstatus.'"></i></td>
                          <td style="text-align:center;"><a href="'. base_url().'manager/orders/vieworder/'. $value['order_id'].'" title="View Order" style="color:#f4b942;" class="fa fa-eye"></a></td>

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
    
    public function all() {
    	$data['page']='Canceled Orders';
    	$data['menu'] = $this->left_model->get_menu();
    	$this->load->view('manager/allorders',$data);
    }

    public function searchkeyall() {
    	
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
     	$data['ids'] = $this->categories_model->get_single_sub_categories();
 		$ids = array();
 		foreach($data['ids'] as $key){
 			array_push($ids,$key['id']);
 		}
 		array_push($ids,$_SESSION['mcategory']);
		if($this->orders_model->allorders($wow,'','','','',$ids)){
			$totalRec = count($this->orders_model->allorders($wow,'','','','',$ids));
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
	
				
		$config["base_url"] = base_url() . "manager/orders/searchkeyall?searchkey=".$wow."&sort_by=".$sort_by."&sort_order=".$sort_order;
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
		
	    $orders = $this->orders_model->allorders($wow, $config["per_page"], $per_page,$sort_by,$sort_order,$ids);
    	$table = '';	
    	$pstatus= '';	
    	$table .= '<table class="table table-bordered table-sortable">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th href="'.base_url() .'manager/orders/searchkeyall?searchkey='.$wow.'&sort_by=order_id&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Order ID</th>
                          <th href="'.base_url() .'manager/orders/searchkeyall?searchkey='.$wow.'&sort_by=customer.name&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Customer</th>
                          <th href="'.base_url() .'manager/orders/searchkeyall?searchkey='.$wow.'&sort_by=vendors.name&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Vendor</th>
                          <th href="'.base_url() .'manager/orders/searchkeyall?searchkey='.$wow.'&sort_by=date&sort_order='.(($sort_order == 'asc') ? 'desc' : 'asc').'" class="sortable moiz sort-alpha sort-'.(($sort_order == 'desc') ? 'desc' : 'asc').'">Date</th>
                          <th class="text-center" style="width: 90px;">Status</th>
                          <th class="text-center" style="width: 70px;">Action</th>
                        </tr>
                      </thead>
                      <tbody>';
                      if(!empty($orders)){
					  	
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
						foreach($orders as $value){ 
					    if ($value['ostatus'] == "new" ){$pstatus = 'Waiting';} else if ($value['ostatus'] == "hold" ) {$pstatus = 'New';} else if ($value['ostatus'] == "process" ) {$pstatus = 'In-Process';}  else if ($value['ostatus'] == "delivered" ) {$pstatus = 'Delivered';} else if ($value['ostatus'] == "received" ) {$pstatus = 'Received';} else if ($value['ostatus'] == "complete" ) {$pstatus = 'Completed';} else if ($value['ostatus'] == "cancel" ) {$pstatus = 'Canceled';}
					    if($value['ostatus'] != "complete"){ $aa = '&nbsp;&nbsp;<a title="Cancel Order" class="status_cancelorder check-toggler" data-status="'.$value['ostatus'].'" data-id="'.$value['order_id'].'"></a>';} else{$aa="";}
					    $i++;
							
                      $table .= '  <tr>
                          <td>'.$i.'</td>
                          <td>'. $value['order_id'].'</td>
                          <td>'. $value['fname']." ". $value['lname'].'</td>
                          <td>'. $value['vname'].'</td>
                          <td>'. $value['date'].'</td>
                          <td style="text-align:center;">'.$pstatus.'</td>
                          <td style="text-align:center; padding-right:10px; padding-left:10px;"><a href="'. base_url().'manager/orders/vieworder/'. $value['order_id'].'" title="View Order" style="color:#f4b942;" class="fa fa-eye"></a> '.$aa.'</td>
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
}