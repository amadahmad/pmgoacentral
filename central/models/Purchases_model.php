<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Purchases_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getProductNames($term, $limit = 5) {
        $term = $this->db->escape_str($term);
        $this->db->where("type = 'standard' AND (name LIKE '%".$term."%' OR code LIKE '%".$term."%' OR  concat(name, ' (', code, ')') LIKE '%".$term."%')");
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
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

    public function getProductByID($id) {
        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductsByCode($code) {
        $this->db->select('*')->from('products')->like('code', $code, 'both');
        $q = $this->db->get();
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

    public function getProductByName($name) {
        $q = $this->db->get_where('products', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateProductQuantity($product_id, $quantity, $warehouse_id, $product_cost) {
        //if ($this->updatePrice($product_id, $product_cost) && $this->addQuantity($product_id, $warehouse_id, $quantity)) {
        if ($this->addQuantity($product_id, $warehouse_id, $quantity)) {
            return true;
        }
        return false;
    }

    public function calculateAndUpdateQuantity($item_id, $product_id, $quantity, $warehouse_id, $product_cost) {
        if ($this->updatePrice($product_id, $product_cost) && $this->calculateAndAddQuantity($item_id, $product_id, $warehouse_id, $quantity)) {
            return true;
        }
        return false;
    }

    public function calculateAndAddQuantity($item_id, $product_id, $warehouse_id, $quantity) {

        if ($this->getProductQuantity($product_id, $warehouse_id)) {
            $quantity_details = $this->getProductQuantity($product_id, $warehouse_id);
            $product_quantity = $quantity_details['quantity'];
            $item_details = $this->getItemByID($item_id);
            $item_quantity = $item_details->quantity;
            $after_quantity = $product_quantity - $item_quantity;
            $new_quantity = $after_quantity + $quantity;
            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                return TRUE;
            }
        } else {

            if ($this->insertQuantity($product_id, $warehouse_id, $quantity)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addQuantity($product_id, $warehouse_id, $quantity) {
        
        if ($this->getProductQuantity($product_id, $warehouse_id)) {
            $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
            $old_quantity = $warehouse_quantity['quantity'];
            $new_quantity = $old_quantity + $quantity;

            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                return TRUE;
            }
        } else {

            if ($this->insertQuantity($product_id, $warehouse_id, $quantity)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity) {
        $productData = array(
            'product_id' => $product_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $quantity
        );
        if ($this->db->insert('warehouses_products', $productData)) {
            return true;
        }
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity) {
        if ($this->db->update('warehouses_products', array('quantity' => $quantity), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            return true;
        }
        return false;
    }

    public function getProductQuantity($product_id, $warehouse) {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);

        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }

        return FALSE;
    }

    public function updatePrice($id, $unit_cost) {

        if ($this->db->update('products', array('cost' => $unit_cost), array('id' => $id))) {
            return true;
        }

        return false;
    }

    public function getAllPurchases() {
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllPurchaseItems($purchase_id) {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function getItemByID($id) {
        $q = $this->db->get_where('purchase_items', array('id' => $id), 1);
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

    public function getPurchaseByID($id) {
        $q = $this->db->get_where('purchases', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function npQTY($product_id, $quantity) {
        $prD = $this->getProductByID($product_id);
        $nQTY = $prD->quantity + $quantity;
        $this->db->update('products', array('quantity' => $nQTY), array('id' => $product_id));
    }

    public function addPurchase($data, $items) {

        if ($this->db->insert('purchases', $data)) {
            $purchase_id = $this->db->insert_id();

            foreach ($items as $item) {
                $item['purchase_id'] = $purchase_id;
                if($data['status'] == 'received') { 
                    $this->npQTY($item['product_id'], $item['quantity']);
                    $this->updateProductQuantity($item['product_id'], $item['quantity'], $item['warehouse_id'], $item['net_unit_cost']);
                }
                $this->db->insert('purchase_items', $item);
            }
            return true;
        }
        return false;
    }
    
    public function upQTY($product_id, $quantity) {
        $prD = $this->getProductByID($product_id);
        $nQTY = $prD->quantity - $quantity;
        $this->db->update('products', array('quantity' => $nQTY), array('id' => $product_id));
    }

    public function updatePurchase($id, $data, $items = array()) {

        
        $old_inv = $this->getPurchaseByID($id);
        $old_items = $this->getAllPurchaseItems($id);
        if($old_inv->status == 'received') {
            foreach ($old_items as $item) {
                $item_id = $item->id;
                $item_details = $this->getItemByID($item_id);
                $item_qiantity = $item_details->quantity;
                $product_id = $item->product_id;
                $pr_qty_details = $this->getProductQuantity($product_id, $old_inv->warehouse_id);
                $pr_qty = $pr_qty_details['quantity'];
                $qty = $pr_qty - $item_qiantity;

                $this->updateQuantity($product_id, $old_inv->warehouse_id, $qty);
                $this->upQTY($product_id, $item_qiantity);
            }
        }

        if ($this->db->update('purchases', $data, array('id' => $id)) && $this->db->delete('purchase_items', array('purchase_id' => $id))) {
            if($data['status'] == 'received') {
                foreach ($items as $item) {
                    $this->npQTY($item['product_id'], $item['quantity']);
                    $this->updateProductQuantity($item['product_id'], $item['quantity'], $item['warehouse_id'], $item['net_unit_cost']);
                }
            }

            if ($this->db->insert_batch('purchase_items', $items)) {
                return true;
            }
        }

        return false;
    }

    public function deletePurchase($id) {
        $inv = $this->getPurchaseByID($id);
        $items = $this->getAllPurchaseItems($id);
        if($inv->status == 'received') {
            foreach ($items as $item) {
                $product_id = $item->product_id;
                $item_details = $this->getProductQuantity($product_id, $inv->warehouse_id);
                $pr_quantity = $item_details['quantity'];
                $inv_quantity = $item->quantity;
                $new_quantity = $pr_quantity - $inv_quantity;

                $this->updateQuantity($product_id, $inv->warehouse_id, $new_quantity);
                $this->upQTY($product_id, $item->quantity);
            }
        }
        if ($this->db->delete('purchase_items', array('purchase_id' => $id)) && $this->db->delete('purchases', array('id' => $id))) {
            return true;
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

    public function getPurchasePayments($purchase_id) {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    
    public function getPaymentByID($id) {
        $q = $this->db->get_where('payments', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
    
    public function getPaymentsForPurchase($purchase_id) {
        $this->db->select('payments.date, payments.paid_by, payments.amount, payments.reference_no, users.first_name, users.last_name')
        ->join('users', 'users.id=payments.created_by', 'left');
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function addPayment($data = array()) {
        $inv = $this->getPurchaseByID($data['purchase_id']);
        $paid = $inv->paid + $data['amount'];
        if($inv->grand_total > $paid) {
            if ($this->db->insert('payments', $data) && $this->db->update('purchases', array('paid' => $paid, 'payment_status' => 'partial'), array('id' => $data['purchase_id']))) {
                return true;
            }
        } else {
            if ($this->db->insert('payments', $data) && $this->db->update('purchases', array('paid' => $paid, 'payment_status' => 'paid'), array('id' => $data['purchase_id']))) {
                return true;
            }
        }
        return false;
    }
    
    public function updatePayment($id, $data = array()) {
        $opay = $this->getPaymentByID($id);
        $inv = $this->getPurchaseByID($data['purchase_id']);
        $paid = $inv->paid + ($data['amount'] - $opay->amount);
        if($inv->grand_total > $paid) {
            if ($this->db->update('payments', $data, array('id' => $id)) && $this->db->update('purchases', array('paid' => $paid, 'payment_status' => 'partial'), array('id' => $data['purchase_id']))) {
                return true;
            }
        } else {
            if ($this->db->update('payments', $data, array('id' => $id)) && $this->db->update('purchases', array('paid' => $paid, 'payment_status' => 'paid'), array('id' => $data['purchase_id']))) {
                return true;
            }
        }
        return false;
    }
    
    public function deletePayment($id) {
        $opay = $this->getPaymentByID($id);
        $inv = $this->getPurchaseByID($data['purchase_id']);
        $paid = $inv->paid - $opay->amount;
        if($paid <= 0 && $inv->due_date >= date('Y-m-d')) {
            $this->db->update('purchases', array('paid' => $paid, 'payment_status' => 'pending'), array('id' => $data['purchase_id']));
        } elseif($paid <= 0 && $inv->due_date <= date('Y-m-d')) {
            $this->db->update('purchases', array('paid' => $paid, 'payment_status' => 'due'), array('id' => $data['purchase_id']));
        } elseif($inv->grand_total > $paid) {
            $this->db->update('purchases', array('paid' => $paid, 'payment_status' => 'partial'), array('id' => $data['purchase_id']));
        } else {
            $this->db->update('purchases', array('paid' => $paid, 'payment_status' => 'paid'), array('id' => $data['purchase_id']));
        }
        
        if ($this->db->delete('payments', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

}
