<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('cart');
        $this->load->library("pagination");
        $this->load->model('home_model');
        $this->load->model('products_model');
        
        function objectToArray($d)
        {
            
            if (is_object($d)) {
                $d = get_object_vars($d);
            }
            
            if (is_array($d)) {
                return array_map(__FUNCTION__, $d);
            } else {
                return $d;
            }
            
        }
    }
    
    public function search()
    {
        $id                       = $this->input->get('s');
        $data['cart'] = $this->cart->contents();
		$data['count'] = $this->cart->total_items();
        $data['metas']            = objectToArray($this->home_model->get_metas());
        $data['categories_home3'] = $this->home_model->get_categories_home3();
        $data['products']         = $this->products_model->search_products($id);
        foreach ($data['products'] as $pro);
        $data['groups']        = $this->products_model->get_groups($pro->category);
        $data['cat']           = $id . '|' . $id;
        $data['socialnetwork'] = objectToArray($this->home_model->get_socialnetwork());
        $data['contactus']     = $this->home_model->get_contactus();
        $this->load->view('products', $data);
    }
    
    public function index($id = NULL)
    {
        $data['cart'] = $this->cart->contents();
		$data['count'] = $this->cart->total_items();
        $data['metas']            = objectToArray($this->home_model->get_metas());
        $data['categories_home3'] = $this->home_model->get_categories_home3();
        $data['products']         = $this->products_model->get_single_category_products($id);
        $data['groups']           = $this->products_model->get_groups($id);
        $data['socialnetwork']    = objectToArray($this->home_model->get_socialnetwork());
        $data['contactus']        = $this->home_model->get_contactus();
        $data['cat']              = $id;
        $this->load->view('products', $data);
    }
    
    public function index1($id = NULL)
    {
        $data['cart'] = $this->cart->contents();
		$data['count'] = $this->cart->total_items();
        $data['metas']             = objectToArray($this->home_model->get_metas());
        $data['categories_home3']  = $this->home_model->get_categories_home3();
        $data['products']          = $this->products_model->get_single_product($id);
        $data['product_attribute'] = $this->products_model->get_product_attribute($id);
        foreach ($data['products'] as $key);
        $category              = $key->category;
        $data['category']      = $this->home_model->get_category_name($category);
        $data['related']       = $this->products_model->get_related_product($category);
        $data['socialnetwork'] = objectToArray($this->home_model->get_socialnetwork());
        $data['contactus']     = $this->home_model->get_contactus();
        $this->load->view('product_detail', $data);
    }
    
    
    public function searchkey()
    {
        $data       = array();
        $id         = $this->input->post('id');
        $sort_order = $this->input->get('sort_order');
        $sort_by    = $this->input->get('sort_by');
        if (!empty($this->input->post('per_page'))) {
            $per_page1 = $this->input->post('per_page');
        } else {
            $per_page1 = 9;
        }
        
        if (!empty($this->input->post('orderby')) && !empty($this->input->post('fil')) && !empty($this->input->post('price'))) {
            $orderby  = $this->input->post('orderby');
            $filter   = $this->input->post('fil');
            $price    = $this->input->post('price');
            $per_page = $this->input->get('per_page');
            if ($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price)) {
                $totalRec = count($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price));
            } else {
                $totalRec = 0;
            }
        } else if (empty($this->input->post('orderby')) && !empty($this->input->post('fil')) && empty($this->input->post('price'))) {
            $orderby  = '';
            $price    = '';
            $filter   = $this->input->post('fil');
            $per_page = $this->input->get('per_page');
            if ($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price)) {
                $totalRec = count($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price));
            } else {
                $totalRec = 0;
            }
        }
        
        else if (!empty($this->input->post('orderby')) && empty($this->input->post('fil')) && empty($this->input->post('price'))) {
            $filter   = '';
            $price    = '';
            $orderby  = $this->input->post('orderby');
            $per_page = $this->input->get('per_page');
            if ($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price)) {
                $totalRec = count($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price));
            } else {
                $totalRec = 0;
            }
        } else if (empty($this->input->post('orderby')) && empty($this->input->post('fil')) && !empty($this->input->post('price'))) {
            $filter   = '';
            $price    = $this->input->post('price');
            $orderby  = '';
            $per_page = $this->input->get('per_page');
            if ($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price)) {
                $totalRec = count($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price));
            } else {
                $totalRec = 0;
            }
        } else if (empty($this->input->post('orderby')) && !empty($this->input->post('fil')) && !empty($this->input->post('price'))) {
            $orderby  = '';
            $price    = $this->input->post('price');
            $filter   = $this->input->post('fil');
            $per_page = $this->input->get('per_page');
            if ($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price)) {
                $totalRec = count($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price));
            } else {
                $totalRec = 0;
            }
        }
        
        else if (!empty($this->input->post('orderby')) && empty($this->input->post('fil')) && !empty($this->input->post('price'))) {
            $filter   = '';
            $price    = $this->input->post('price');
            $orderby  = $this->input->post('orderby');
            $per_page = $this->input->get('per_page');
            if ($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price)) {
                $totalRec = count($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price));
            } else {
                $totalRec = 0;
            }
        } else if (!empty($this->input->post('orderby')) && !empty($this->input->post('fil')) && empty($this->input->post('price'))) {
            $filter   = $this->input->post('fil');
            $price    = '';
            $orderby  = $this->input->post('orderby');
            $per_page = $this->input->get('per_page');
            if ($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price)) {
                $totalRec = count($this->products_model->get_single_category_products1($id, '', '', $orderby, $filter, $price));
            } else {
                $totalRec = 0;
            }
        } else {
            $orderby  = '';
            $filter   = '';
            $price    = '';
            $per_page = $this->input->get('per_page');
            if ($this->products_model->get_single_category_products1($id, '', '', '', '', '')) {
                $totalRec = count($this->products_model->get_single_category_products1($id, '', '', '', '', ''));
            } else {
                $totalRec = 0;
            }
        }
        
        $config                      = array();
        $config['full_tag_open']     = "<ul class='pagination1 pagination'>";
        $config['full_tag_close']    = "</ul>";
        $config['num_tag_open']      = '<li class="pagination1">';
        $config['num_tag_close']     = '</li>';
        $config['cur_tag_open']      = "<li class='pagination1'><a class='active'>";
        $config['cur_tag_close']     = "</a></li>";
        $config['next_tag_open']     = "<li class='pagination1'>";
        $config['next_tagl_close']   = "</li>";
        $config['prev_tag_open']     = "<li class='pagination1'>";
        $config['prev_tagl_close']   = "</li>";
        $config['first_tag_open']    = "<li class='pagination1'>";
        $config['first_tagl_close']  = "</li>";
        $config['last_tag_open']     = "<li class='pagination1'>";
        $config['last_tagl_close']   = "</li>";
        $config['next_link']         = 'NEXT';
        $config['prev_link']         = 'PREV';
        $config["base_url"]          = base_url() . "Products/searchkey";
        $config["total_rows"]        = $totalRec;
        $config["per_page"]          = $per_page1;
        $config["uri_segment"]       = 1;
        $config["page_query_string"] = TRUE;
        $config['display_pages']     = TRUE;
        $choice                      = $config["total_rows"] / $config["per_page"];
        $config['num_links']         = 9;
        $config['attributes']        = array(
            'class' => 'pagination'
        );
        $this->pagination->initialize($config);
        
        $data['sort_by']    = $sort_by;
        $data['sort_order'] = $sort_order;
        
        $products = $this->products_model->get_single_category_products1($id, $config["per_page"], $per_page, $orderby, $filter, $price);
        $table    = '';
        
        if (!empty($products)) {
            foreach ($products as $key) {
                $pictures = $this->products_model->get_single_picture($key['pid']);
                
                $table .= '                                                <li class=" first post-5188 product type-product status-publish has-post-thumbnail product_lookbook-sweet-summer product_lookbook-winter-2015 product_brand-adesso product_brand-carvela product_cat-aliquam product_tag-men product_tag-women featured shipping-taxable purchasable product-type-variable product-cat-aliquam product-tag-men product-tag-women instock">
                                                   <div class="product-container">
                                                      <figure>
                                                         <div class="product-wrap">
                                                            <div class="product-images">
                                                               <a href="' . base_url() . 'Products/index1/' . $key['pid'] . '"';
                foreach ($pictures as $key1);
                $table .= '<div class="shop-loop-thumbnail"><img width="300" height="350" src="' . base_url() . 'uploads/products/' . $key1->images . '" class="attachment-shop_catalog size-shop_catalog wp-post-image" alt="No Image" /></div>
                                                                  <div class="yith-wcwl-add-to-wishlist add-to-wishlist-5188">
                                                                     <div class="yith-wcwl-add-button show" style="display:block">
                                                               <a href="indexa1a3.html?add_to_wishlist=5188" rel="nofollow" data-product-id="5188" data-product-type="variable" class="add_to_wishlist">
                                                               Add to Wishlist</a>
                                                               <img src="wp-content/plugins/yith-woocommerce-wishlist/assets/images/wpspin_light.gif" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" />
                                                               </div>
                                                               <div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;">
                                                               <span class="feedback">Product added!</span>
                                                               <a href="wishlist/index19e5.html?wishlist-action=view">
                                                               Browse Wishlist </a>
                                                               </div>
                                                               <div class="yith-wcwl-wishlistexistsbrowse hide" style="display:none">
                                                               <span class="feedback">The product is already in the wishlist!</span>
                                                               <a href="wishlist/index19e5.html?wishlist-action=view">
                                                               Browse Wishlist </a>
                                                               </div>
                                                               <div style="clear:both"></div>
                                                               <div class="yith-wcwl-wishlistaddresponse"></div>
                                                               </div>
                                                               <div class="clear"></div>
                                                               </a>
                                                               
                                                            </div>
                                                         </div>
                                                         <figcaption>
                                                            <div class="shop-loop-product-info">
                                                               <div class="info-title">
                                                                  <h3 class="product_title"><a href="' . base_url() . 'Products/index1/' . $key['id'] . '">' . $key['title'] . '</a></h3>
                                                               </div>
                                                               <div class="info-meta">
                                                                  <div class="info-price">
                                                                     <span class="price">Rs <span class="amount">' . $key['price'] . '</span></span>
                                                                  </div>
                                                                  
                                                                  <div class="loop-add-to-cart">
                                                                  <button type="button" name="add_cart" class="btn btn-success add_cart" data-productname="' . $key['title'] . '" data-price="' . $key['price'] . '" data-productid="' . $key['id'] . '" />Add to Cart</button>
                                                                  
                                                                    
                                                                  </div>
                                                                  <div class="info-excerpt">
                                                                     Proin malesuada enim nulla, nec bibendum justo vestibulum non. Duis et ipsum convallis, bibendum enim a, hendrerit diam. Praesent tellus mi, vehicula et risus eget, laoreet tristique tortor. Fusce id&hellip; 
                                                                  </div>
                                                                  <div class="list-info-meta clearfix">
                                                                     <div class="info-price">
                                                                        <span class="price"><span class="amount">&#36;12.00</span>&ndash;<span class="amount">&#36;23.00</span></span>
                                                                     </div>
                                                                     <div class="list-action clearfix">
                                                                        <div class="loop-add-to-cart">
                                                                           <a href="product.html" rel="nofollow" data-product_id="5188" data-product_sku="" data-quantity="1" class="add_to_cart_button product_type_variable">Select options</a> 
                                                                        </div>
                                                                        <div class="loop-add-to-wishlist">
                                                                           <div class="yith-wcwl-add-to-wishlist add-to-wishlist-5188">
                                                                              <div class="yith-wcwl-add-button show" style="display:block">
                                                                                 <a href="indexa1a3.html?add_to_wishlist=5188" rel="nofollow" data-product-id="5188" data-product-type="variable" class="add_to_wishlist">
                                                                                 Add to Wishlist</a>
                                                                                 <img src="wp-content/plugins/yith-woocommerce-wishlist/assets/images/wpspin_light.gif" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" />
                                                                              </div>
                                                                              <div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;">
                                                                                 <span class="feedback">Product added!</span>
                                                                                 <a href="wishlist/index19e5.html?wishlist-action=view">
                                                                                 Browse Wishlist </a>
                                                                              </div>
                                                                              <div class="yith-wcwl-wishlistexistsbrowse hide" style="display:none">
                                                                                 <span class="feedback">The product is already in the wishlist!</span>
                                                                                 <a href="wishlist/index19e5.html?wishlist-action=view">
                                                                                 Browse Wishlist </a>
                                                                              </div>
                                                                              <div style="clear:both"></div>
                                                                              <div class="yith-wcwl-wishlistaddresponse"></div>
                                                                           </div>
                                                                           <div class="clear"></div>
                                                                        </div>
                                                                     </div>
                                                                  </div>
                                                               </div>
                                                         </figcaption>
                                                      </figure>
                                                      </div>
                                                </li>';
            }
            
            $table .= '</ul><div style="text-align:center; padding-top:20px;" class="col-lg-12 pagination1">' . $this->pagination->create_links() . '</div>';
        } else {
            $table .= '</ul><h3 style="text-align:center;">No Product Found</h3>';
        }
        
        echo $table;
    }
}