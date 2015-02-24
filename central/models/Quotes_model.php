<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Quotes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function getProductNames($term, $warehouse_id, $limit = 5) {
        $this->db->select('products.id, code, name, warehouses_products.quantity, price, tax_rate')
                ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
                ->group_by('products.id')
                ->where("warehouses_products.warehouse_id = '" . $warehouse_id . "' AND "
                        . "(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getProductByCode($code) {

        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
    
    public function getWHProduct($id) {
        $this->db->select('products.id, code, name, warehouses_products.quantity, cost, tax_rate')
                ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
                ->group_by('products.id');
        $q = $this->db->get_where('products', array('warehouses_products.product_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getItemByID($id) {

        $q = $this->db->get_where('quote_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
    

    public function getAllQuoteItemsWithDetails($quote_id) {
        $this->db->select('quote_items.id, quote_items.product_name, quote_items.product_code, quote_items.quantity, quote_items.serial_no, quote_items.tax, quote_items.unit_price, quote_items.val_tax, quote_items.discount_val, quote_items.gross_total, products.details');
        $this->db->join('products', 'products.id=quote_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('quotes_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }


    public function getQuoteByID($id) {

        $q = $this->db->get_where('quotes', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllQuoteItems($quote_id) {
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
    public function addQuote($data = array(), $items = array()) {

        if ($this->db->insert('quotes', $data)) {
            $quote_id = $this->db->insert_id();

            $addOn = array('quote_id' => $quote_id);
            end($addOn);
            foreach ($items as &$var) {
                $var = array_merge($addOn, $var);
            }

            if ($this->db->insert_batch('quote_items', $items)) {
                return true;
            }
        }

        return false;
    }


    public function updateQuote($id, $data, $items = array()) {

        if ($this->db->update('quotes', $data, array('id' => $id)) && $this->db->delete('quote_items', array('quote_id' => $id))) {

            if ($this->db->insert_batch('quote_items', $items)) {
                return true;
            }
        }

        return false;
    }


    public function deleteQuote($id) {

        if ($this->db->delete('quote_items', array('quote_id' => $id)) && $this->db->delete('quotes', array('id' => $id))) {
            return true;
        }

        return FALSE;
    }

    public function getProductByName($name) {

        $q = $this->db->get_where('products', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getWarehouseProductQuantity($warehouse_id, $product_id) {

        $q = $this->db->get_where('warehouses_products', array('warehouse_id' => $warehouse_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }


}
