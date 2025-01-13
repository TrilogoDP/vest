<?php
class ModelModuleGoogleRemarketing extends Model {
  function buildDynamicRemarketingCode($products, $view, $categories_name = null)
  {
    $id = $this->config->get('config_store_id');
    $debug_mode = $this->config->get('google_debug_mode_google_remarketing_'.$id);
    $google_remarketing_code = $this->config->get('google_remarketing_code_'.$id);

    if($debug_mode)
    {
      $debug_title = 'DYNAMIC REMARKETING CODE';
      $debug_messages = array();
    }

    if(substr_count($google_remarketing_code, 'var google_tag_params = {') == 0 || substr_count($google_remarketing_code, '};') != 1)
    {
      if($debug_mode)
      {
        $debug_messages[] = 'ERROR: GOOGLE DYNAMIC REMARKETING CODE INCORRECT.';
        $this->_log_put($debug_title, $debug_messages);
      }
      return false;
    }
    
    //Corrects views to dynx_
      $dynx_view = $view;
      switch ($view) {
        case 'cart':
          $dynx_view = 'conversionintent';
        break;

        case 'purchase':
          $dynx_view = 'conversion';
        break;

        case 'product':
          $dynx_view = 'offerdetail';
        break;
        
        case 'category':
          $dynx_view = 'offerdetail';
        break;
      }
    //END Corrects views to dynx_

    $params = array(
      'ecomm_prodid' => '""',
      'ecomm_totalvalue' => '""',
      'ecomm_pagetype' => '"'.$view.'"',
      'dynx_itemid' => '""',
      'dynx_itemid2' => '""',
      'dynx_pagetype' => '"'.$dynx_view.'"',
      'dynx_totalvalue' => '""',
    );

    //First all get all info to product in cart
      $product_data = array();
      $total_products = '""';

      if (!empty($products))
      {
        foreach ($products as $product) 
        {
          //Fix to undefined variables 2014/09/29
            if (!isset($product['download'])) $product['download'] = '';
            if (!isset($product['price'])) $product['price'] = 0;
            if (!isset($product['tax_class_id'])) $product['tax_class_id'] = '';

          if (!isset($product['total']))
          {
            if (!empty($product['special']))
              $product['total'] = $this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax'));
            else
              $product['total'] = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
          }
          else
          {
            if (!empty($product['special']))
              $product['total'] = $this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax'));
            else
              $product['total'] = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
          }

          if (!isset($product['product_id']))
            $product['product_id'] = '';
          if (!isset($product['name']))
            $product['name'] = '';
          if (!isset($product['model']))
            $product['model'] = '';
          if (!isset($product['download']))
            $product['download'] = '';
          if (!isset($product['quantity']))
            $product['quantity'] = '';
          if (!isset($product['subtract']))
            $product['subtract'] = '';
          if (!isset($product['price']))
            $product['price'] = '';
          if (!isset($product['total']))
            $product['total'] = '';
          if (!isset($product['tax_class_id']))
            $product['tax_class_id'] = '';
          if (!isset($product['reward']))
            $product['reward'] = '';

          $product_data[] = array(
            'product_id' => $this->config->get('google_remarketing_id_preffix_'.$id).$product['product_id'].$this->config->get('google_remarketing_id_suffix_'.$id),
            'name'       => $product['name'],
            'model'      => $product['model'],
            'download'   => $product['download'],
            'quantity'   => $product['quantity'],
            'subtract'   => $product['subtract'],
            'price'      => $product['price'],
            'total'      => $product['total'],
            'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
            'reward'     => $product['reward']
          ); 
        }
        $total_price = 0;
        foreach ($product_data as $key => $product)
        {
          if ($key == 0) $total_price = 0;
          $total_price += $product['total'];
        }
        if ($total_price == 0) $total_price = '""';
        else $total_products = number_format($total_price, 2, '.', '');
      }
    //END First all get all info to product in cart

    //ecomm_prodid  -   dynx_itemid   -   dynx_itemid2
      if(!empty($product_data) && count($product_data) > 1)
      {
        $params['ecomm_prodid'] = '[';
        $params['dynx_itemid'] = '[';
        $params['dynx_itemid2'] = '[';
          foreach ($product_data as $key => $pro) {
            $params['ecomm_prodid'] .= '"'.$pro['product_id'].'",';
            $params['dynx_itemid'] .= '"'.$pro['product_id'].'",';
            $params['dynx_itemid2'] .= '"'.$pro['product_id'].'",';
          }
          $params['ecomm_prodid'] = substr($params['ecomm_prodid'], 0, -1);
          $params['dynx_itemid'] = substr($params['dynx_itemid'], 0, -1);
          $params['dynx_itemid2'] = substr($params['dynx_itemid2'], 0, -1);
        $params['ecomm_prodid'] .= ']';
        $params['dynx_itemid'] .= ']';
        $params['dynx_itemid2'] .= ']';
      }
      elseif(!empty($product_data) && count($product_data) == 1)
      {
        $params['ecomm_prodid'] = '"'.$product['product_id'].'"';
        $params['dynx_itemid'] = '"'.$product['product_id'].'"';
        $params['dynx_itemid2'] = '"'.$product['product_id'].'"';
      }
    //END ecomm_prodid  -   dynx_itemid   -   dynx_itemid2

    //ecomm_totalvalue  -   dynx_totalvalue
      $params['ecomm_totalvalue'] = $total_products;
      $params['dynx_totalvalue'] = $total_products;
    //END ecomm_totalvalue  -   dynx_totalvalue

    //ecomm_pcat
      if($view == "category" && !empty($categories_name))
      {
        $categories_name_temp = '""';

        if (count($categories_name) == 1)
          $categories_name_temp = '"'.$categories_name[0].'"';
        else
        {
          $categories_name_temp = "[";
          foreach ($categories_name as $key=>$cat_name)
          {
            $categories_name_temp .= '"'.$cat_name.'"';
            if (($key+1) != count($categories_name))
              $categories_name_temp .= ",";
          }
          $categories_name_temp .= "]";
        }
        $params['ecomm_pcat'] = "ecomm_pcat: ".$categories_name_temp.','."\n";
      }
    //END ecomm_pcat

    //Divide code
      $code_1 = $this->config->get('google_remarketing_code_'.$id);
      $code_1 = explode('var google_tag_params = {', $code_1);
      $code_1 = $code_1[0]."\n".'var google_tag_params = {'."\n";

      $code_2 = $this->config->get('google_remarketing_code_'.$id);
      $code_2 = explode('};', $code_2);
      $code_2 = "\n".'};'.$code_2[1];
    //END Divide code

    //Replace params in code
      $final_params = '';
      $params_names = array('ecomm_prodid', 'ecomm_totalvalue', 'ecomm_pagetype', 'dynx_itemid', 'dynx_itemid2', 'dynx_pagetype', 'dynx_totalvalue');

      if(!empty($params['ecomm_pcat']))
        $final_params .= $params['ecomm_pcat'];

      foreach ($params_names as $key => $name)
      {
        if(substr_count($google_remarketing_code, $name.':') != 0 && $params[$name] != '""')
          $final_params .= $name.': '.$params[$name].",\n";
      }

      $final_params = substr($final_params, 0, -2);
      
      $google_remarketing_code = $code_1.$final_params.$code_2;

      if($debug_mode)
      {
        $debug_messages[] = 'CODE GENERATED: '.html_entity_decode($google_remarketing_code);
        $this->_log_put($debug_title, $debug_messages);
      }
    //END Replace params in code
    return html_entity_decode($google_remarketing_code);
  }

  function getDynamicRemarketingCode()
  {
    //-- CASE HOME VIEW
    if (!isset($this->request->get["route"]))
    {         
      return $this->buildDynamicRemarketingCode('', 'home');
    }
    
    //-- CASE HOME VIEW
    elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "common/home")
    {             
      return $this->buildDynamicRemarketingCode('', 'home');
    }

    //-- CASE PRODUCT VIEW
    elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "product/product")
    {   
      $product_info = $this->model_catalog_product->getProduct($this->request->get["product_id"]);
      $product_info_temp = array();
      $product_info_temp[] = $product_info;
      return $this->buildDynamicRemarketingCode($product_info_temp, 'product');
    }

    //-- CASE CART VIEW
    elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "checkout/checkout" || $this->request->get["route"] == "checkout/cart" || $this->request->get["route"] == "supercheckout/supercheckout" || $this->request->get["route"] == "checkout/simplecheckout")
    {   
      return $this->buildDynamicRemarketingCode($this->cart->getProducts(), 'cart');         
    }
    
    //-- CASE PURCHASE VIEW
    elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "checkout/success")
    {
      if(!empty($_SESSION['previus_cart']) && count($_SESSION['previus_cart']) > 0)
        return $this->buildDynamicRemarketingCode($_SESSION['previus_cart'], 'purchase');
    }

    //-- CASE CATEGORY VIEW
    elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "product/category")
    {
      $category_names = array();
      if (isset($this->request->get['path'])) {

        $path = '';

        $parts = explode('_', (string)$this->request->get['path']);

        foreach ($parts as $path_id) {
          if (!$path) {
            $path = $path_id;
          } else {
            $path .= '_' . $path_id;
          }

          $category_info = $this->model_catalog_category->getCategory($path_id);

          if ($category_info) {
            $category_names[] = $category_info['name'];
          }
        }
      }

      return $this->buildDynamicRemarketingCode('', 'category', $category_names);
    }

    //-- CASE MANUFACTURER VIEW
    elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "product/manufacturer/info")
    {
      $manufacturers_names = array();
      if (isset($this->request->get['manufacturer_id'])) {
        $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);
        if(!empty($manufacturer_info))
         $manufacturers_names[] = $manufacturer_info['name'];
      }
      return $this->buildDynamicRemarketingCode('', 'category', $manufacturers_names);
    }

    //-- CASE SEARCHRESULT VIEW
    elseif (isset($this->request->get["route"]) && $this->request->get["route"] == "product/search")
    {
      
      //-- GET ALL PRODUCTS FILTERED
        if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
          if (isset($this->request->get['search'])) {
            $search = $this->request->get['search'];
          } else {
            $search = '';
          } 

          if (isset($this->request->get['tag'])) {
            $tag = $this->request->get['tag'];
          } elseif (isset($this->request->get['search'])) {
            $tag = $this->request->get['search'];
          } else {
            $tag = '';
          } 

          if (isset($this->request->get['description'])) {
            $description = $this->request->get['description'];
          } else {
            $description = '';
          } 

          if (isset($this->request->get['category_id'])) {
            $category_id = $this->request->get['category_id'];
          } else {
            $category_id = 0;
          } 

          if (isset($this->request->get['sub_category'])) {
            $sub_category = $this->request->get['sub_category'];
          } else {
            $sub_category = '';
          } 

          if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
          } else {
            $sort = 'p.sort_order';
          } 

          if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
          } else {
            $order = 'ASC';
          }

          if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
          } else {
            $page = 1;
          }

          if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
          } else {
            $limit = $this->config->get('config_catalog_limit');
          }

          $data_filter = array(
            'filter_name'         => $search, 
            'filter_tag'          => $tag, 
            'filter_description'  => $description,
            'filter_category_id'  => $category_id, 
            'filter_sub_category' => $sub_category, 
            'sort'                => $sort,
            'order'               => $order,
            'start'               => ($page - 1) * $limit,
            'limit'               => $limit
          );

          $results = $this->model_catalog_product->getProducts($data_filter);
  
          return $this->buildDynamicRemarketingCode($results, 'searchresults');
        }
      //-- END GET ALL PRODUCTS FILTERED
        else
        {
          return $this->buildDynamicRemarketingCode('', 'searchresults');
        }
    }
    //-- CASE OTHER VIEWS
    else
    {
      return $this->buildDynamicRemarketingCode('', 'other');
    }
  }

  protected function getPath($parent_id, $current_path = '')
  {
    $category_info = $this->model_catalog_category->getCategory($parent_id);

    if ($category_info) {
      if (!$current_path) {
        $new_path = $category_info['category_id'];
      } else {
        $new_path = $category_info['category_id'] . '_' . $current_path;
      } 

      $path = $this->getPath($category_info['parent_id'], $new_path);

      if ($path) {
        return $path;
      } else {
        return $new_path;
      }
    }
  }
}
?>