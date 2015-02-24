<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transfers_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getProductNames($term, $warehouse_id, $limit = 5) {
        $this->db->select('products.id, code, name, warehouses_products.quantity, cost, tax_rate')
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

    public function addTransfer($data = array(), $items = array()) {
        $status = $data['status'];
        if ($status == 'sent') {
            foreach ($items as $item) {
                $product_id = $item['product_id'];
                $product_quantity = $item['quantity'];
                $from_warehouse_id = $data['from_warehouse_id'];
                $to_warehouse_id = $data['to_warehouse_id'];
                $this->updateWarehouseQuantity($product_id, $from_warehouse_id, $product_quantity, 'from');
            }
        } elseif ($status == 'completed') {
            foreach ($items as $item) {
                $product_id = $item['product_id'];
                $product_quantity = $item['quantity'];
                $from_warehouse_id = $data['from_warehouse_id'];
                $to_warehouse_id = $data['to_warehouse_id'];

                $this->updateWarehouseQuantity($product_id, $from_warehouse_id, $product_quantity, 'from');
                $this->updateWarehouseQuantity($product_id, $to_warehouse_id, $product_quantity, 'to');
            }
        }

        if ($this->db->insert('transfers', $data)) {
            $transfer_id = $this->db->insert_id();
            foreach ($items as $item) {
                $item['transfer_id'] = $transfer_id;
                if ($status == 'completed') {
                    $item['date'] = date('Y-m-d');
                    $item['status'] = 'received';
                    $this->db->insert('purchase_items', $item);
                    //$this->db->insert('transfer_items', $item);
                } else {
                    $this->db->insert('transfer_items', $item);
                }
            }

            return true;
        }
        return false;
    }
    
    public function updateTransfer($id, $data = array(), $items = array()) {

        $otransfer = $this->transfers_model->getTransferByID($id);
        $oitems = $this->transfers_model->getAllTransferItems($id, $otransfer->status);
        $ostatus = $otransfer->status;
        if ($ostatus == 'sent') {
            foreach ($oitems as $oitem) {
                $oproduct_id = $oitem->product_id;
                $oproduct_quantity = $oitem->quantity;
                $ofrom_warehouse_id = $otransfer->from_warehouse_id;
                $oto_warehouse_id = $otransfer->to_warehouse_id;
                $this->updateOldWarehouseQuantity($oproduct_id, $ofrom_warehouse_id, $oproduct_quantity, 'from');
            }
        } elseif ($ostatus == 'completed') {
            foreach ($oitems as $oitem) {
                $oproduct_id = $oitem->product_id;
                $oproduct_quantity = $oitem->quantity;
                $oto_product_quantity = $oitem->quantity + ($oitem->quantity - $oitem->quantity_balance);
                $ofrom_warehouse_id = $otransfer->from_warehouse_id;
                $oto_warehouse_id = $otransfer->to_warehouse_id;
                $this->updateOldWarehouseQuantity($oproduct_id, $ofrom_warehouse_id, $oproduct_quantity, 'from');
                $this->updateOldWarehouseQuantity($oproduct_id, $oto_warehouse_id, $oto_product_quantity, 'to');
            }
        }

        $status = $data['status'];
        if ($status == 'sent') {
            foreach ($items as $item) {
                $product_id = $item['product_id'];
                $product_quantity = $item['quantity'];
                $from_warehouse_id = $data['from_warehouse_id'];
                $to_warehouse_id = $data['to_warehouse_id'];
                $this->updateWarehouseQuantity($product_id, $from_warehouse_id, $product_quantity, 'from');
            }
        } elseif ($status == 'completed') {
            foreach ($items as $item) {
                $product_id = $item['product_id'];
                $product_quantity = $item['quantity'];
                $from_warehouse_id = $data['from_warehouse_id'];
                $to_warehouse_id = $data['to_warehouse_id'];

                $this->updateWarehouseQuantity($product_id, $from_warehouse_id, $product_quantity, 'from');
                $this->updateWarehouseQuantity($product_id, $to_warehouse_id, $product_quantity, 'to');
            }
        }

        if ($this->db->update('transfers', $data, array('id' => $id))) {
            
            if($ostatus == 'completed') {
                $this->db->delete('purchase_items', array('transfer_id' => $id));
            } else {
                $this->db->delete('transfer_items', array('transfer_id' => $id));
            }
            
            $addOn = array('date' => date('Y-m-d'), 'status' => 'received');
            end($addOn);
            foreach ($items as &$var) {
                $var = array_merge($addOn, $var);
            }

            if($status == 'completed') {
                $this->db->insert_batch('purchase_items', $items);
            } else {
                $this->db->insert_batch('transfer_items', $items);
            }
            
            return true;
        }

        return false;
    }

    public function getProductByCategoryID($id) {

        $q = $this->db->get_where('products', array('category_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return true;
        }

        return FALSE;
    }
    
    public function updateWarehouseQuantity($product_id, $warehouse_id, $quantity, $scope) {
        if($scope == 'from') { 
            
            if ($this->getProductQuantity($product_id, $warehouse_id)) {
                $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
                $warehouse_quantity = $warehouse_quantity['quantity'];
                $new_quantity = $warehouse_quantity - $quantity;
                if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                    return TRUE;
                }
            } else {
                if ($this->insertQuantity($product_id, $warehouse_id, -$quantity)) {
                    return TRUE;
                }
            }
            
        } elseif($scope == 'to') {
            
            if ($this->getProductQuantity($product_id, $warehouse_id)) {
                $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
                $warehouse_quantity = $warehouse_quantity['quantity'];
                $new_quantity = $warehouse_quantity + $quantity;
                if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                    return TRUE;
                }
            } else {
                if ($this->insertQuantity($product_id, $warehouse_id, $quantity)) {
                    return TRUE;
                }
            }
            
        }

        return FALSE;
    }
    
    public function updateOldWarehouseQuantity($product_id, $warehouse_id, $quantity, $scope) {
        if($scope == 'from') { 
            
            if ($this->getProductQuantity($product_id, $warehouse_id)) {
                $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
                $warehouse_quantity = $warehouse_quantity['quantity'];
                $new_quantity = $warehouse_quantity + $quantity;
                if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                    return TRUE;
                }
            } else {
                if ($this->insertQuantity($product_id, $warehouse_id, $quantity)) {
                    return TRUE;
                }
        }
            
        } elseif($scope == 'to') {
            
            if ($this->getProductQuantity($product_id, $warehouse_id)) {
                $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
                $warehouse_quantity = $warehouse_quantity['quantity'];
                $new_quantity = $warehouse_quantity - $quantity;
                if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                    return TRUE;
                }
            } else {
                if ($this->insertQuantity($product_id, $warehouse_id, $quantity)) {
                    return TRUE;
                }
            }
            
        }

        return FALSE;
    }
    
    public function getProductQuantity($product_id, $warehouse = DEFAULT_WAREHOUSE) {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }
    
    public function insertQuantity($product_id, $warehouse_id, $quantity) {
        if ($this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
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
    
    public function getTransferByID($id) {

        $q = $this->db->get_where('transfers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllTransferItems($transfer_id, $status) {
        if($status == 'completed') {
            $q = $this->db->get_where('purchase_items', array('transfer_id' => $transfer_id));
        } else {
            $q = $this->db->get_where('transfer_items', array('transfer_id' => $transfer_id));
        }
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    
    public function getWarehouseProductQuantity($warehouse_id, $product_id) {
        $q = $this->db->get_where('warehouses_products', array('warehouse_id' => $warehouse_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function deleteTransfer($id) {
        $otransfer = $this->transfers_model->getTransferByID($id);
        $oitems = $this->transfers_model->getAllTransferItems($id, $otransfer->status);
        $ostatus = $otransfer->status;
        if ($ostatus == 'sent') {
            foreach ($oitems as $oitem) {
                $oproduct_id = $oitem->product_id;
                $oproduct_quantity = $oitem->quantity;
                $ofrom_warehouse_id = $otransfer->from_warehouse_id;
                $oto_warehouse_id = $otransfer->to_warehouse_id;
                $this->updateOldWarehouseQuantity($oproduct_id, $ofrom_warehouse_id, $oproduct_quantity, 'from');
            }
        } elseif ($ostatus == 'completed') {
            foreach ($oitems as $oitem) {
                $oproduct_id = $oitem->product_id;
                $oproduct_quantity = $oitem->quantity;
                $oto_product_quantity = $oitem->quantity + ($oitem->quantity - $oitem->quantity_balance);
                $ofrom_warehouse_id = $otransfer->from_warehouse_id;
                $oto_warehouse_id = $otransfer->to_warehouse_id;
                $this->updateOldWarehouseQuantity($oproduct_id, $ofrom_warehouse_id, $oproduct_quantity, 'from');
                $this->updateOldWarehouseQuantity($oproduct_id, $oto_warehouse_id, $oto_product_quantity, 'to');
            }
        }
        
        if ($this->db->delete('transfers', array('id' => $id))) {
            if($otransfer->status == 'completed') {
                $this->db->delete('purchase_items', array('transfer_id' => $id));
            } else {
                $this->db->delete('transfer_items', array('transfer_id' => $id));
            }
            return true;
        }
        return FALSE;
    }
    /*
    
    
    
    
    
    
    
    
    

    public function getAllTransfers() {
        $q = $this->db->get('transfers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    

    

    public function searched_products_count($name) {
        $this->db->like("name", $name);
        $query = $this->db->get('products');
        return $query->num_rows();
    }

    public function transferQuantity($product_id, $warehouseFrom, $warehouseTo, $quantity) {

        //check if entry exist then update else inster
        if ($this->getProductQuantity($product_id, $warehouseTo)) {

            $to_product_details = $this->getProductQuantity($product_id, $warehouseTo);
            $to_old_quantity = $to_product_details['quantity'];
            $to_quantity = $to_old_quantity + $quantity;

            $from_product_details = $this->getProductQuantity($product_id, $warehouseFrom);
            $from_old_quantity = $from_product_details['quantity'];
            $from_quantity = $from_old_quantity - $quantity;

            if ($this->updateQuantity($product_id, $warehouseTo, $to_quantity) && $this->updateQuantity($product_id, $warehouseFrom, $from_quantity)) {
                return TRUE;
            }
        } else {

            $from_product_details = $this->getProductQuantity($product_id, $warehouseFrom);
            $from_old_quantity = $from_product_details['quantity'];
            $from_quantity = $from_old_quantity - $quantity;

            if ($this->insertQuantity($product_id, $warehouseTo, $quantity) && $this->updateQuantity($product_id, $warehouseFrom, $from_quantity)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function transferProducts($transferDetails = array(), $items = array()) {


        foreach ($items as $item) {
            $product_id = $item['product_id'];
            $product_quantity = $item['quantity'];
            $from_warehouse_id = $transferDetails['from_warehouse_id'];
            $to_warehouse_id = $transferDetails['to_warehouse_id'];

            $this->updateFromWarehouseQuantity($product_id, $from_warehouse_id, $product_quantity);
            $this->updateToWarehouseQuantity($product_id, $to_warehouse_id, $product_quantity);
        }


        // sale data
        $transferData = array(
            'transfer_no' => $transferDetails['transfer_no'],
            'date' => $transferDetails['date'],
            'from_warehouse_id' => $transferDetails['from_warehouse_id'],
            'from_warehouse_code' => $transferDetails['from_warehouse_code'],
            'from_warehouse_name' => $transferDetails['from_warehouse_name'],
            'to_warehouse_id' => $transferDetails['to_warehouse_id'],
            'to_warehouse_code' => $transferDetails['to_warehouse_code'],
            'to_warehouse_name' => $transferDetails['to_warehouse_name'],
            'note' => $transferDetails['note'],
            'user' => USER_NAME,
            'total_tax' => $transferDetails['total_tax'],
            'tr_total' => $transferDetails['tr_total'],
            'total' => $transferDetails['total'],
        );

        if ($this->db->insert('transfers', $transferData)) {
            $transfer_id = $this->db->insert_id();

            $addOn = array('transfer_id' => $transfer_id);
            end($addOn);
            foreach ($items as &$var) {
                $var = array_merge($addOn, $var);
            }

            if ($this->db->insert_batch('transfer_items', $items)) {
                return true;
            }
        }
        return false;
    }

    public function updateToWarehouseQuantity($product_id, $warehouse_id, $quantity) {
        //check if entry exist then update else inster
        if ($this->getProductQuantity($product_id, $warehouse_id)) {

            //get product details to calculate nwe quantity
            $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
            $warehouse_quantity = $warehouse_quantity['quantity'];
            $new_quantity = $warehouse_quantity + $quantity;

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

    public function updateFromWarehouseQuantity($product_id, $warehouse_id, $quantity) {

        if ($this->getProductQuantity($product_id, $warehouse_id)) {
            
            $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
            $warehouse_quantity = $warehouse_quantity['quantity'];
            $new_quantity = $warehouse_quantity - $quantity;

            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                return TRUE;
            }
        } else {

            if ($this->insertQuantity($product_id, $warehouse_id, -$quantity)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function addQuantity($product_id, $warehouse_id, $quantity) {

        //check if entry exist then update else inster
        if ($this->getProductQuantity($product_id, $warehouse_id)) {

            $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
            $old_quantity = $warehouse_quantity['quantity'];
            $new_quantity = $old_quantity - $quantity;

            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                return TRUE;
            }
        } else {

            if ($this->insertQuantity($product_id, $warehouse_id, -$quantity)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    

    

    

    public function oupdateToWarehouseQuantity($product_id, $warehouse_id, $quantity) {
        //check if entry exist then update else inster
        if ($this->getProductQuantity($product_id, $warehouse_id)) {

            //get product details to calculate nwe quantity
            $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
            $warehouse_quantity = $warehouse_quantity['quantity'];
            $new_quantity = $warehouse_quantity - $quantity;

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

    public function oupdateFromWarehouseQuantity($product_id, $warehouse_id, $quantity) {
        //check if entry exist then update else inster
        if ($this->getProductQuantity($product_id, $warehouse_id)) {

            //get product details to calculate nwe quantity
            $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
            $warehouse_quantity = $warehouse_quantity['quantity'];
            $new_quantity = $warehouse_quantity + $quantity;

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

    
    */
}
