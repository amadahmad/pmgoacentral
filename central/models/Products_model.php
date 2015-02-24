<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAllProducts() {
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
    
    public function getProductOptions($pid) {
        $q = $this->db->get_where('product_options', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
    
    public function getProductOptionsWithWH($pid) {
        $this->db->select('product_options.*, warehouses.name as wh_name')->join('warehouses', 'warehouses.id=product_options.warehouse_id', 'left')->group_by('product_options.id');
        $q = $this->db->get_where('product_options', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
    
    public function getProductComboItems($pid) {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name')->join('products', 'products.code=combo_items.item_code', 'left')->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function getProductByID($id) {

        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getProductDetails($id) {
        $this->db->select('products.code, products.name, categories.code as category_code, products.cost, products.price, products.quantity, products.alert_quantity')
        ->join('categories', 'categories.id=products.category_id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getProductByCategoryID($id) {

        $q = $this->db->get_where('products', array('category_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return true;
        }

        return FALSE;
    }


    public function getProductQuantity($product_id, $warehouse_id) {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id), 1);

        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }

        return FALSE;
    }
    
    public function getAllWarehousesWithPQ($product_id) {
        $this->db->select('warehouses.*, warehouses_products.quantity, warehouses_products.rack')
        ->join('warehouses_products', 'warehouses_products.warehouse_id=warehouses.id', 'left')
        ->where('warehouses_products.product_id', $product_id)
        ->group_by('warehouses.id');
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getProductPhotos($id) {
        $q = $this->db->get_where("product_photos", array('product_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getSupplierByID($id) {

        $q = $this->db->get_where('suppliers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getProductByCode($code) {

        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function addProduct($data, $items, $warehouse_qty, $product_attributes, $photos) {

        if ($this->db->insert('products', $data)) {
            $product_id = $this->db->insert_id();
            
            if($items) {
                foreach($items as $item) {
                    $item['product_id'] = $product_id;
                    $this->db->insert('combo_items', $item);
                }
            }
            
            if($data['type'] == 'combo') {
                $warehouses = $this->site->getAllWarehouses();
                foreach($warehouses as $warehouse) {
                    $this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse->id, 'quantity' => 0));
                }
            } 
            
            if($warehouse_qty) {
                foreach($warehouse_qty as $wh_qty) {
                    $wh_qty['product_id'] = $product_id;
                    $this->db->insert('warehouses_products', $wh_qty);
                }
                $tax_rate = $this->site->getTaxRateByID($data['tax_rate']);
                $tax_rate_id = $tax_rate ? $tax_rate->rate : NULL;
                $tax = $tax_rate ? (($tax_rate->type == 1) ? $tax_rate->rate . "%" : $tax_rate->rate) : NULL;

                if($tax_rate) {
                    if ($tax_rate->type == 1 && $tax_rate->rate != 0) {
                        if ($data['tax_method'] == '0') {
                            $pr_tax_val = ($data['cost'] * parseFloat($tax_rate->rate)) / (100 + parseFloat($tax_rate->rate));
                            $net_item_cost -= $pr_tax_val;
                            $item_tax = ((($data['quantity'] * $item_net_cost) * $tax_rate->rate) / 100);
                        } else {
                            $net_item_cost = $data['cost'];
                            $item_tax = ((($data['quantity'] * $item_net_cost) * $tax_rate->rate) / 100);
                        }
                    } else {
                        $net_item_cost = $data['cost'];
                        $item_tax = $tax_rate->rate;
                    }
                } else {
                    $net_item_cost = $data['cost'];
                    $item_tax = 0;
                }

                $subtotal = (($item_net_cost * $data['quantity']) + $item_tax);
                $item = array(
                    'product_id' => $product_id,
                    'product_code' => $data['code'],
                    'product_name' => $data['name'],
                    'net_unit_cost' => $net_item_cost,
                    'quantity' => $data['quantity'],
                    'quantity_balance' => $data['quantity'],
                    'item_tax' => $item_tax,
                    'tax_rate_id' => $tax_rate_id,
                    'tax' => $tax,
                    'subtotal' => $subtotal,
                    'warehouse_id' => $data['warehouse_id'],
                    'date' => date('Y-m-d'),
                    'status' => 'received',
                    );
                $this->db->insert('purchase_items', $item);
            }

            if($product_attributes) {
                foreach($product_attributes as $pr_attr) {
                    $pr_attr['product_id'] = $product_id;
                    $this->db->insert('product_options', $pr_attr);
                }
            }

            if($photos) {
                foreach($photos as $photo) {
                    $this->db->insert('product_photos', array('product_id' =>$product_id, 'photo' => $photo));
                }
            }

            return true;
        } 

        return false;

    }

    public function addAjaxProduct($data) {

        if ($this->db->insert('products', $data)) {
            $product_id = $this->db->insert_id();
            return $this->getProductByID($product_id);
        } 

        return false;

    }

    public function add_products($data = array()) {

        if ($this->db->insert_batch('products', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateProduct($id, $data, $items, $warehouse_qty, $product_attributes, $photos) {

        if ($this->db->update('products', $data, array('id' => $id))) {

            if($items) {
                $this->db->delete('combo_items', array('product_id' => $id));
                foreach($items as $item) {
                    $item['product_id'] = $id;
                    $this->db->insert('combo_items', $item);
                }
            }

            if($warehouse_qty) {
                foreach($warehouse_qty as $wh_qty) {
                    $wh_qty['product_id'] = $id;
                    $this->db->insert('warehouses_products', $wh_qty);
                }
            }

            if($product_attributes) {
                $this->db->delete('product_options', array('product_id' => $id));
                foreach($product_attributes as $pr_attr) {
                    $pr_attr['product_id'] = $id;
                    $this->db->insert('product_options', $pr_attr);
                }
            }

            if($photos) {
                foreach($photos as $photo) {
                    $this->db->insert('product_photos', array('product_id' =>$id, 'photo' => $photo));
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function getPurchasedItemDetails($product_id) {
        $q = $this->db->get_where('purchase_items', array('product_id' => $product_id, 'purchase_id' => NULL, 'transfer_id' => NULL), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function updatePrice($data = array()) {

        if ($this->db->update_batch('products', $data, 'code')) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteProduct($id) {
        if ($this->db->delete('products', array('id' => $id)) && $this->db->delete('warehouses_products', array('product_id' => $id))) {
            return true;
        }
        return FALSE;
    }


    public function totalCategoryProducts($category_id) {
        $q = $this->db->get_where('products', array('category_id' => $category_id));

        return $q->num_rows();
    }

    public function getSubcategoryByID($id) {
        $q = $this->db->get_where('subcategories', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getCategoryByCode($code) {
        $q = $this->db->get_where('categories', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getSubcategoryByCode($code) {

        $q = $this->db->get_where('subcategories', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getTaxRateByName($name) {
        $q = $this->db->get_where('tax_rates', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getSubCategories() {
        $this->db->select('id as id, name as text');
        $q = $this->db->get("subcategories");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getSubCategoriesForCategoryID($category_id) {
        $this->db->select('id as id, name as text');
        $q = $this->db->get_where("subcategories", array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getSubCategoriesByCategoryID($category_id) {
        $q = $this->db->get_where("subcategories", array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getDamagePdByID($id) {

        $q = $this->db->get_where('damage_products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function addDamage($product_id, $date, $quantity, $warehouse, $note) {

        if ($wh_qty_details = $this->getProductQuantity($product_id, $warehouse)) {
            $balance_qty = $wh_qty_details['quantity'] - $quantity;
            $this->updateQuantity($product_id, $warehouse, $balance_qty);
        } else {
            $balance_qty = 0 - $quantity;
            $this->insertQuantity($product_id, $warehouse, $balance_qty);
        }
        $prd = $this->getProductByID($product_id);
        $nQTY = $prd->quantity - $quantity;

        $data = array(
            'date' => $date,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'warehouse_id' => $warehouse,
            'note' => $note,
            'user' => $this->session->userdata('username')
            );

        if ($this->db->insert('damage_products', $data) && $this->db->update('products', array('quantity' => $nQTY), array('id' => $product_id))) {
            return true;
        } else {
            return false;
        }
    }

    public function updateDamage($id, $product_id, $date, $quantity, $warehouse, $note) {

        $wh_qty_details = $this->getProductQuantity($product_id, $warehouse);
        $dp_details = $this->getDamagePdByID($id);
        $old_quantity = $wh_qty_details['quantity'] + $dp_details->quantity;
        $balance_qty = $old_quantity - $quantity;
        $prd = $this->getProductByID($product_id);
        $nQTY = ($prd->quantity + $dp_details->quantity) - $quantity;

        $data = array(
            'product_id' => $product_id,
            'quantity' => $quantity,
            'warehouse_id' => $warehouse,
            'note' => $note,
            'user' => USER_NAME
            );
        if($date) { $data['date'] = $date; }
        if ($this->db->update('damage_products', $data, array('id' => $id)) && $this->updateQuantity($product_id, $warehouse, $balance_qty) && $this->db->update('products', array('quantity' => $nQTY), array('id' => $product_id))) {
            return true;
        } else {
            return false;
        }
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity) {

        $productData = array(
            'product_id' => $product_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $quantity
            );

        if ($this->db->insert('warehouses_products', $productData)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity) {

        $productData = array(
            'quantity' => $quantity
            );

        if ($this->db->update('warehouses_products', $productData, array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteDamage($id) {
        $dp_details = $this->getDamagePdByID($id);
        $wh_qty_details = $this->getProductQuantity($dp_details->product_id, $dp_details->warehouse_id);
        $old_quantity = $wh_qty_details['quantity'] + $dp_details->quantity;
        if ($this->updateQuantity($dp_details->product_id, $dp_details->warehouse_id, $old_quantity) && $this->db->delete('damage_products', array('id' => $id))) {
            return true;
        }

        return false;
    }

    public function products_count($category_id, $subcategory_id = NULL) {
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        $this->db->from('products');
        return $this->db->count_all_results();
    }

    public function fetch_products($category_id, $limit, $start, $subcategory_id = NULL) {

        $this->db->limit($limit, $start);
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        $this->db->order_by("id", "asc");
        $query = $this->db->get("products");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAllAttributes() {
        $this->db->select("attributes.id as id, title, type, group_concat(concat(attribute_options.option) SEPARATOR '|') as options")
        ->join('attribute_options', 'attributes.id=attribute_options.attribute_id', 'left')
        ->group_by("attributes.id")
        ->order_by('attributes.id, attribute_options.id');
        $q = $this->db->get('attributes');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    /* public function getCategoryAttributes($category_id) {
      $this->db->select("attributes.id as id, title, categories.name as category_name, categories.id as category_id, type, group_concat(concat(attribute_options.option) SEPARATOR ', ') as options")
      ->join('attribute_options', 'attributes.id=attribute_options.attribute_id', 'left')
      ->join('categories', 'categories.id=attributes.category_id', 'left')
      ->group_by("attributes.id");
      $this->db->order_by('attributes.id, attribute_options.id');
      $q = $this->db->get_where('attributes', array('category_id' => $category_id));
      if ($q->num_rows() > 0) {
      foreach (($q->result()) as $row) {
      $data[] = $row;
      }

      return $data;
      }
  } */
}
