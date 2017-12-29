<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pc
 *
 * @author User
 */
class ControllerCustomPc extends Controller {

    private $error = array();

    //use following query to get all products from components category
    //SELECT * FROM `oc_product` WHERE product_id IN (SELECT product_id FROM `oc_product_to_category` WHERE category_id IN ((SELECT category_id FROM `oc_category` WHERE parent_id = 25)))
    //SELECT * FROM `oc_product` p LEFT JOIN `oc_product_to_category` p2c ON (p.product_id = p2c.product_id) LEFT JOIN `oc_category` c ON (c.category_id = p2c.category_id) WHERE c.parent_id = 25
    public function index() {
        $this->load->language('custom/pc');

        $this->load->model('catalog/category');

        $this->load->model('custom/pc');

        $this->load->model('tool/image');
        
        //$this->log->write($this->config->get('config_url'));$this->request->server['HTTP_X_FORWARDED_FOR']
        //$this->log->write($this->url->link('information/contact'));
        //$mail = 'Parameter: ' .$this->config->get('config_mail_parameter');
        //$mail .= 'hostname: ' .$this->config->get('config_mail_smtp_hostname');
        //$mail .= 'username: ' .$this->config->get('config_mail_smtp_username');
        //$mail .= 'password: ' .html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        //$mail .= 'Port: ' .$this->config->get('config_mail_smtp_port');
        //$mail .= 'Timeout: ' .$this->config->get('config_mail_smtp_timeout');
        
        //$this->log->write($mail);
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        if (isset($this->request->get['path'])) {
            $url = '';

            $path = '';

            $parts = explode('_', (string) $this->request->get['path']);

            $category_id = (int) array_pop($parts);

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = (int) $path_id;
                } else {
                    $path .= '_' . (int) $path_id;
                }

                $category_info = $this->model_catalog_category->getCategory($path_id);

                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
                        'href' => $this->url->link('custom/pc', 'path=' . $path . $url)
                    );
                }
            }
        } else {
            $category_id = 0;
        }

        $category_info = $this->model_catalog_category->getCategory($category_id);

        if ($category_info) {
            $this->document->setTitle($category_info['meta_title']);
            $this->document->setDescription($category_info['meta_description']);
            $this->document->setKeywords($category_info['meta_keyword']);
            $this->document->addScript('catalog/view/javascript/custom/ajaxupload.js');
            $this->document->addScript('catalog/view/javascript/custom/tabs.js');
            $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
            $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
            $this->document->addStyle('catalog/view/theme/default/stylesheet/custom_pc.css');
            //$this->document->addStyle('catalog/view/theme/default/stylesheet/custom_pc.css');

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			
			$data['column_type'] = $this->language->get('column_type');
			$data['column_name'] = $this->language->get('column_name');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_total'] = $this->language->get('column_total');
			$data['text_select'] = $this->language->get('text_select');
			$data['text_total'] = $this->language->get('text_total');
			$data['text_form'] = $this->language->get('text_form');
			$data['text_add_cart'] = $this->language->get('text_add_cart');
			$data['customer_notice'] = $this->language->get('customer_notice');

            // Set the last category breadcrumb
            $data['breadcrumbs'][] = array(
                'text' => $category_info['name'],
                'href' => $this->url->link('custom/pc', 'path=' . $this->request->get['path'])
            );

            if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

            $data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
            //$data['compare'] = $this->url->link('product/compare');

            $url = '';

            $data['categories'] = array();

            $results = $this->model_custom_pc->getCategories2($category_id);
            //$this->log->write($results);

            foreach ($results as $result) {
                $filter_data = array(
                    'filter_category_id' => $result['category_id'],
                    'filter_sub_category' => true
                );

                $data['categories'][] = array(
                    'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_custom_pc->getTotalProducts($filter_data) . ')' : ''),
                    'category_id' => $result['category_id']
                        //'href' => $this->url->link('custom/pc', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
                );
            }

            $data['categories_2'] = array();

            $categories = array();
            $categories = $data['categories'];
            $filter_data = array(
                'filter_category_id' => $category_id,
            );

            //$product_total = $this->model_custom_pc->getTotalProducts($filter_data);
            //$results = $this->model_custom_pc->getProducts($filter_data);

            $results = $this->model_custom_pc->getCustomPcProducts($category_id);

            foreach ($categories as $category) {
                foreach ($results[$category['category_id']] as $result) {
                    //$this->log->write($result);
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], 50, 50);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', 50, 50);
                    }

                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price_xformat = $result['price'];
                        $price = $this->currency->format($this->tax->calculate($result['price'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $price = false;
                        $price_xformat = false;
                    }
                    
                    if ((float)$result['special']) {
                        $special_xformat = $result['special'];
			$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
			$special = false;
                        $special_xformat = false;
                    }
            
                    $data['categories_2'][$category['category_id']]['products'][] = array(
                        'product_id' => $result['product_id'],
                        'quantity' => '',
                        'thumb' => $image,
                        'name' => $result['name'],
                        'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                        'price' => $price,
                        'price_xformat' => $price_xformat,
                        'special' => $special,
                        'special_xformat' => $special_xformat,
                        'href' => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)
                    );
                }
            }

            //$this->log->write($data['categories_2']);

            if (isset($this->session->data['success'])) {
                $data['success'] = $this->session->data['success'];

                unset($this->session->data['success']);
            } else {
                $data['success'] = '';
            }

            $data['add'] = $this->url->link('used/staff/add');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('custom/pc', $data));
        } else {
            $url = '';

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('custom/pc', $url)
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }


    public function getproductoptions() {
        $json = array();
        $test = 'PRODUCT OPTIONS';
        $output = '';
        $output .= '<div class="text-center"><h5><b>' . $test . '</b></h5></div>';

        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $lang = $this->language->get('code');

        if (isset($this->request->post['product_id'])) {
            $product_id = $this->request->post['product_id'];
        } else {
            $product_id = '42';
        }

        if (isset($this->request->post['i'])) {
            $i = $this->request->post['i'];
        } else {
            $i = '';
        }

        $result = $this->model_catalog_product->getProduct($product_id);

        $data['options'] = array();

        foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) {
            $product_option_value_data = array();

            foreach ($option['product_option_value'] as $option_value) {
                if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                    if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float) $option_value['price']) {
                        $price = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
                        $real_price = (float) $option_value['price'];
                    } else {
                        $price = false;
                    }

					//$this->log->write('Price: ' .$this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']) .' Real Price: ' .$real_price);
                    $product_option_value_data[] = array(
                        'product_option_value_id' => $option_value['product_option_value_id'],
                        'option_value_id' => $option_value['option_value_id'],
                        'name' => $option_value['name'],
                        'image' => $this->model_tool_image->resize($option_value['image'], 50, 50),
                        'price' => $price,
                        'real_price' => $real_price,
                        'price_prefix' => $option_value['price_prefix']
                    );
                }
            }

            $data['options'][] = array(
                'product_option_id' => $option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id' => $option['option_id'],
                'name' => $option['name'],
                'type' => $option['type'],
                'value' => $option['value'],
                'required' => $option['required']
            );
        }

        $options = $data['options'];
        //$this->log->write($options);
        $text_option = 'Available Options';
        $text_select = '-- Please Select --';
        $text_loading = 'Loading...';
        $button_upload = ' Upload';
        
        $output = '';
        if ($options){
            $output .= '<div class="options_block" id="options_block_' .$i. '">';
            $output .= '<div id="product_' .$i. '">';
            $output .= '<h3>' .$text_option. '</h3>';
            $output .= '<hr />';
            foreach($options as $option) {
                if ($option['type'] == 'select'){
                    //$this->log->write('select');
                    $output .= '<div class="form-group';
                    $required = '';
                    if ($option['required']) {
                       $required = ' required'; 
                    }
                    $output .= $required. '">';
                    $output .= '<label class="control-label" for="input-option' .$option['product_option_id']. '">' .$option['name']. '</label>
                                    <select onChange="addOptionPrice('.$i.');" name="products[' .$i. '][option][' .$option['product_option_id']. ']" id="input-option' .$option['product_option_id']. '" class="form-control">
                                        <option value="">' .$text_select. '</option>';
                                        foreach ($option['product_option_value'] as $option_value) {
                                            $output .= '<option value="' .$option_value['product_option_value_id']. '">' .$option_value['name'];
											
                                            if ($option_value['price']) {
                                                $output .= ' (' .$option_value['price_prefix'] .' '. $option_value['price']. ')';
                                            } 
                                            $output .= '</option>';
                                        }
                                  $output .= '</select>
                                </div>';
                }
                if ($option['type'] == 'radio') {
                    //$this->log->write('radio');
                    $output .= '<div class="form-group';
                    $required = '';
                    if ($option['required']) { 
                        $required = ' required';
                    }
                    $output .= $required. '">';
                    
                    $output .= '<label class="control-label">' .$option['name']. '</label>
                      <div id="input-option' .$option['product_option_id']. '">'; 
                        foreach ($option['product_option_value'] as $option_value) {
                            $output .= '<div class="radio">
                              <label>
                                <input type="radio" onclick="addOptionPrice('.$i.');" name="products[' .$i. '][option][' .$option['product_option_id']. ']" value="' .$option_value['product_option_value_id']. '" />';
                                if ($option_value['image']) {
                                    $output .= '<img src="' .$option_value['image']. '" alt="' .$option_value['name']; 
                                    if ($option_value['price']) {
                                        $output .= ' (' .$option_value['price_prefix'] .' '. $option_value['price']. ')';                  
                                    }
                                    $output .= '" class="img-thumbnail" />';
                                }
                                $output .= $option_value['name'];
                                if ($option_value['price']) {
                                    $output .= ' (' .$option_value['price_prefix'] .' '. $option_value['price']. ')';
                                    //$output .= '<input type="hidden" id="radio_' .$i. '_' .$count. '" name="option_' .$i. '_' .$count. '" value="' .$option_value['real_price']. '"/>';
                                } 
                                $output .= '</label>
                            </div>';
                        } 
                        $output .= '</div>
                    </div>';
                }
                if ($option['type'] == 'checkbox') {
                    //$this->log->write('checkbox');
                    $output .= '<div class="form-group';
                    $required = '';
                    if ($option['required']) { 
                        $required = ' required';
                    }
                    $output .= $required. '">';
                    
                    $output .= '<label class="control-label">' .$option['name']. '</label>
                      <div id="input-option' .$option['product_option_id']. '">'; 
                        foreach ($option['product_option_value'] as $option_value) {
                            $output .= '<div class="checkbox">
                              <label>
                                <input type="checkbox" onclick="addOptionPrice('.$i.');" name="products[' .$i. '][option][' .$option['product_option_id']. '][]" value="' .$option_value['product_option_value_id']. '" />';
                                if ($option_value['image']) {
                                    $output .= '<img src="' .$option_value['image']. '" alt="' .$option_value['name']; 
                                    if ($option_value['price']) {
                                        $output .= ' (' .$option_value['price_prefix'] .' '. $option_value['price']. ')';                  
                                    }
                                    $output .= '" class="img-thumbnail" />';
                                }
                                $output .= $option_value['name'];
                                if ($option_value['price']) {
                                    $output .= ' (' .$option_value['price_prefix'] .' '. $option_value['price']. ')';
                                } 
                                $output .= '</label>
                            </div>';
                        } 
                        $output .= '</div>
                    </div>';
                }
                if ($option['type'] == 'text') {
                    //$this->log->write('text');
                    $output .= '<div class="form-group';
                    $required = '';
                    if ($option['required']) { 
                        $required = ' required';
                    }
                    $output .= $required. '">';
                    $output .= '<label class="control-label" for="input-option' .$option['product_option_id']. '">' .$option['name']. '</label>
                      <input type="text" onclick="addOptionPrice('.$i.');" name="products[' .$i. '][option][' .$option['product_option_id']. ']" value="' .$option['value']. '" placeholder="' .$option['name']. '" id="input-option' .$option['product_option_id']. '" class="form-control" />
                    </div>';
                }
                if ($option['type'] == 'textarea') {
                    //$this->log->write('textarea');
                    $output .= '<div class="form-group';
                    $required = '';
                    if ($option['required']) { 
                        $required = ' required';
                    }
                    $output .= $required. '">';
                    $output .= '<label class="control-label" for="input-option' .$option['product_option_id']. '">' .$option['name']. '</label>
                        <textarea name="products[' .$i. '][option][' .$option['product_option_id']. ']" rows="5" placeholder="' .$option['name']. '" id="input-option' .$option['product_option_id']. '" class="form-control">' .$option['value']. '</textarea>
                    </div>';
                }
                if ($option['type'] == 'file') {
                    //$this->log->write('file');
                    $output .= '<div class="form-group';
                    $required = '';
                    if ($option['required']) { 
                        $required = ' required';
                    }
                    $output .= $required. '">';
                    
                    $output .= '<label class="control-label">' .$option['name']. '</label>
                        <button type="button" id="button-upload' .$option['product_option_id']. '" data-loading-text="' .$text_loading. '" class="btn btn-default btn-block"><i class="fa fa-upload"></i>' .$button_upload. '</button>
                        <input type="hidden" name="products[' .$i. '][option][' .$option['product_option_id']. ']" value="" id="input-option' .$option['product_option_id']. '" />
                    </div>';
                    $output .= '<script type="text/javascript">$("button[id^=\"button-upload\"]").on("click", function() {
                                        var node = this;

                                        $("#form-upload-' .$i. '").remove();

                                        $("body").prepend("<form enctype=\"multipart/form-data\" id=\"form-upload-' .$i. '\" style=\"display: none;\"><input type=\"file\" name=\"file\" /></form>");

                                        $("#form-upload-' .$i. ' input[name=\"file\"]").trigger("click");

                                        if (typeof timer != "undefined") {
                                        clearInterval(timer);
                                        }

                                        timer = setInterval(function() {
                                                if ($("#form-upload-' .$i. ' input[name=\"file\"]").val() != "") {
                                                        clearInterval(timer);

                                                        $.ajax({
                                                                url: "index.php?route=tool/upload",
                                                                type: "post",
                                                                dataType: "json",
                                                                data: new FormData($("#form-upload-' .$i. '")[0]),
                                                                cache: false,
                                                                contentType: false,
                                                                processData: false,
                                                                beforeSend: function() {
                                                                        $(node).button("loading");
                                                                },
                                                                complete: function() {
                                                                        $(node).button("reset");
                                                                },
                                                                success: function(json) {
                                                                        $(".text-danger").remove();

                                                                        if (json["error"]) {
                                                                                $(node).parent().find("input").after("<div class=\"text-danger\">" + json["error"] + "</div>");
                                                                        }

                                                                        if (json["success"]) {
                                                                                alert(json["success"]);

                                                                                $(node).parent().find("input").val(json["code"]);
                                                                        }
                                                                },
                                                                error: function(xhr, ajaxOptions, thrownError) {
                                                                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                                }
                                                        });
                                                }
                                        }, 500);
                                });
                                </script>';
                    //$this->log->write($test);
                }
                if ($option['type'] == 'date') {
                    //$this->log->write('date');
                    $output .= '<div class="form-group';
                    $required = '';
                    if ($option['required']) { 
                        $required = ' required';
                    }
                    $output .= $required. '">';
                    $output .= '<label class="control-label" for="input-option' .$option['product_option_id']. '">' .$option['name']. '</label>
                        <div class="input-group date">
                          <input type="text" onclick="addOptionPrice('.$i.');" name="products[' .$i. '][option][' .$option['product_option_id']. ']" value="' .$option['value']. '" data-date-format="YYYY-MM-DD" id="input-option' .$option['product_option_id']. '" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div>
                    </div>';
                    $output .= '<script type="text/javascript">$(".date").datetimepicker({language: "'.$lang.'",pickTime: false});</script>';
                }
                if ($option['type'] == 'datetime') {
                    //$this->log->write('datetime');
                    $output .= '<div class="form-group';
                    $required = '';
                    if ($option['required']) { 
                        $required = ' required';
                    }
                    $output .= $required. '">';
                    $output .= '<label class="control-label" for="input-option' .$option['product_option_id']. '">' .$option['name']. '</label>
                        <div class="input-group datetime">
                          <input type="text" onclick="addOptionPrice('.$i.');" name="products[' .$i. '][option][' .$option['product_option_id']. ']" value="' .$option['value']. '" data-date-format="YYYY-MM-DD HH:mm" id="input-option' .$option['product_option_id']. '" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div>
                    </div>';
                    $output .= '<script type="text/javascript">$(".datetime").datetimepicker({language: "'.$lang.'",pickDate: true, pickTime: true});</script>';
                }
                if ($option['type'] == 'time') {
                    //$this->log->write('time');
                    $output .= '<div class="form-group';
                    $required = '';
                    if ($option['required']) { 
                        $required = ' required';
                    }
                    $output .= $required. '">';
                    $output .= '<label class="control-label" for="input-option' .$option['product_option_id']. '">' .$option['name']. '</label>
                        <div class="input-group time">
                          <input type="text" onclick="addOptionPrice('.$i.');" name="products[' .$i. '][option][' .$option['product_option_id']. ']" value="' .$option['value']. '" data-date-format="HH:mm" id="input-option' .$option['product_option_id']. '" class="form-control" />
                          <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div>
                    </div>';
                    $output .= '<script type="text/javascript">$(".time").datetimepicker({language: "'.$lang.'",pickDate: false});</script>';
                }
            }
            $output .= '</div></div>';
        }

        
        $json = array('output' => $output);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function getproductprice() {
        $json = array();

        $this->load->model('catalog/product');
        $this->load->model('custom/pc');
        $price = 0.00;
        $option_price = 0.00;
        if (isset($this->request->post['products'])) {
            $products = $this->request->post['products'];

            $prd = array();
            foreach ($products as $product) {
                $product_id = $product['product_id'];
                $prd = $this->model_catalog_product->getProduct($product_id);
                //$this->log->write($product_id);
                $price = $prd['price'];
                if($prd['special']){
                    $price = $prd['special'];
                }
                
                foreach($product as $k => $v){
                    if($k == 'option'){
                        foreach($product['option'] as $key => $value){
                            if(is_array ( $value )){
                                foreach($value as $val){
                                    $opt = $this->model_custom_pc->getOptionPrice($val);
                                    if($opt){
                                        $option_price += (float) $opt;
                                    }
                                }                            
                            }else{
                                $opt = $this->model_custom_pc->getOptionPrice($value);
                                if($opt){
                                    $option_price += (float) $opt;
                                }
                            }
                        }
                    }
                }
            }
            
            $json = array('price' => $price, 'option_price' => $option_price);
        } else {
            $product_id = '';
            $json = array('price' => $price, 'option_price' => $option_price);
        }
        
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function updatecart() {
        $this->load->language('checkout/cart');

        $json = array();
        $flag = true;

        if (isset($this->request->post['products'])) {
            $products = $this->request->post['products'];
            //$this->log->write($products);
            foreach ($products as $product) {
                $product_id = (int) $product['product_id'];

                if ($product_id > 0) {
                    //$this->log->write($product_id);
                    $this->load->model('catalog/product');

                    $product_info = $this->model_catalog_product->getProduct($product_id);

                    if ($product_info) {
                        if (isset($product['quantity'])) {
                            $quantity = (int) $product['quantity'];
                        } else {
                            $quantity = 1;
                        }

                        if (isset($product['option'])) {
                            $option = array_filter($product['option']);
                        } else {
                            $option = array();
                        }

                        $product_options = $this->model_catalog_product->getProductOptions($product_id);

                        foreach ($product_options as $product_option) {
                            if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
                                $json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
                                $flag = false;
                                //$this->log->write('option ERROR');
                            }
                        }

                        if (isset($product['recurring_id'])) {
                            $recurring_id = $product['recurring_id'];
                        } else {
                            $recurring_id = 0;
                        }

                        $recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

                        if ($recurrings) {
                            $recurring_ids = array();

                            foreach ($recurrings as $recurring) {
                                $recurring_ids[] = $recurring['recurring_id'];
                            }

                            if (!in_array($recurring_id, $recurring_ids)) {
                                $json['error']['recurring'] = $this->language->get('error_recurring_required');
                                $flag = false;
                                //$this->log->write('recurring ERROR');
                            }
                        }

                        if ($flag) {
                            $this->cart->add($product_id, $quantity, $option, $recurring_id);
                            //$this->log->write('Product ID: ' . $product_id . 'Quantity: ' . $quantity . 'Reccuring ID: ' . $recurring_id);
                            //$this->log->write($option);

                            $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $product_id), $product_info['name'], $this->url->link('checkout/cart'));

							//$this->log->write($json['success']);
                            // Unset all shipping and payment methods
                            unset($this->session->data['shipping_method']);
                            unset($this->session->data['shipping_methods']);
                            unset($this->session->data['payment_method']);
                            unset($this->session->data['payment_methods']);

                            // Totals
							$this->load->model('extension/extension');

							$totals = array();
							$taxes = $this->cart->getTaxes();
							$total = 0;
					
							// Because __call can not keep var references so we put them into an array. 			
							$total_data = array(
								'totals' => &$totals,
								'taxes'  => &$taxes,
								'total'  => &$total
							);

							// Display prices
							if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
								$sort_order = array();

								$results = $this->model_extension_extension->getExtensions('total');

								foreach ($results as $key => $value) {
									$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
								}

								array_multisort($sort_order, SORT_ASC, $results);

								foreach ($results as $result) {
									if ($this->config->get($result['code'] . '_status')) {
										$this->load->model('extension/total/' . $result['code']);

										// We have to put the totals in an array so that they pass by reference.
										$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
									}
								}

								$sort_order = array();

								foreach ($totals as $key => $value) {
									$sort_order[$key] = $value['sort_order'];
								}

								array_multisort($sort_order, SORT_ASC, $totals);
							}

							$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));
						} else {
							$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
						}
                    }
                }
            }
        } else {
            $product_id = 0;
        }

        //$json = array('output' => $output);
        //$this->log->write($json);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
