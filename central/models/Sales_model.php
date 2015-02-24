<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sales_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function getProductNames($term, $warehouse_id, $limit = 5) {
        $this->db->select('products.id, code, name, type, warehouses_products.quantity, price, tax_rate')
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
    
    public function getProductComboItems($pid, $warehouse_id) {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, warehouses_products.quantity as quantity')
        ->join('products', 'products.code=combo_items.item_code', 'left')
        ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
        ->where('warehouses_products.warehouse_id', $warehouse_id)
        ->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
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

    public function addQuantity($product_id, $warehouse_id, $quantity) {

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

    public function insertQuantity($product_id, $warehouse_id, $quantity) {
        if ($this->db->insert('warehouses_products', array( 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity ))) {
            return true;
        } 
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity) {		
        if ($this->db->update('warehouses_products', array( 'quantity' => $quantity ), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
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
    
    public function getProductOptions($product_id, $warehouse_id) {
        $this->db->select('product_options.id as id, product_options.attribute as attribute, product_options.price as price, product_options.quantity as quantity, warehouses.name as wh_name')
        ->join('warehouses', 'warehouses.id=product_options.warehouse_id', 'left')
        ->where('product_options.product_id', $product_id)
        ->where('product_options.warehouse_id', $warehouse_id)
        ->where('product_options.quantity >', 0)
        ->group_by('product_options.id');
        $q = $this->db->get('product_options');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getItemByID($id) {

        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllInvoiceItems($sale_id) {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getAllReturnItems($return_id) {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('return_items', array('return_id' => $return_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getAllInvoiceItemsWithDetails($sale_id) {
        $this->db->select('sale_items.id, sale_items.product_name, sale_items.product_code, sale_items.quantity, sale_items.serial_no, sale_items.tax, sale_items.net_unit_price, sale_items.item_tax, sale_items.item_discount, sale_items.subtotal, products.details');
        $this->db->join('products', 'products.id=sale_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getInvoiceByID($id) {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getReturnByID($id) {
        $q = $this->db->get_where('return_sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getReturnBySID($sale_id) {
        $q = $this->db->get_where('return_sales', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getProductOptionByID($id) {
        $q = $this->db->get_where('product_options', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getPurchasedItems($product_id, $warehouse_id) {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, item_tax, avg(net_unit_cost) as avg_net_unit_cost, avg(item_tax) as avg_item_tax');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance >', 0);
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function updateOptionQuantity($option_id, $quantity) {
        if($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity - $quantity;
            if($this->db->update('product_options', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addOptionQuantity($option_id, $quantity) {
        if($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity + $quantity;
            if($this->db->update('product_options', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function calculateAVCost($product_id, $warehouse_id, $net_unit_price, $unit_price, $quantity, $product_name, $option_id) {
        $pis = $this->getPurchasedItems($product_id, $warehouse_id);
        $cost_row = array();
        $balance_qty = $quantity;
        foreach($pis as $pi) {
            if(!empty($pi) && $pi->quantity > 0 && $balance_qty <= $quantity && $quantity > 0) {
                $purchase_unit_cost = ($pi->avg_net_unit_cost + ($pi->avg_item_tax/$pi->quantity));
                if($pi->quantity_balance >= $quantity && $quantity > 0) {
                    $balance_qty = $pi->quantity_balance - $quantity;
                    $cost_row = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => $pi->id, 'quantity' => $quantity, 'purchase_net_unit_cost' => $pi->avg_net_unit_cost, 'purchase_unit_cost' => $purchase_unit_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => $balance_qty, 'inventory' => 1);
                    $quantity = 0;
                } elseif($quantity > 0) {
                    $quantity = $quantity - $pi->quantity_balance;
                    $balance_qty = $quantity;
                    $cost_row = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => $pi->id, 'quantity' => $pi->quantity_balance, 'purchase_net_unit_cost' => $pi->avg_net_unit_cost, 'purchase_unit_cost' => $purchase_unit_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => 0, 'inventory' => 1);
                }
            }
            if(empty($cost_row)) { break; }
            $cost[] = $cost_row;
            if($quantity == 0) { break; }
        }
        if($quantity > 0 && !$this->Settings->overselling) { 
            $this->session->set_flashdata('error', sprintf(lang("quantity_out_of_stock_for_%s"), ($pi->product_name ? $pi->product_name : $product_name)));
            redirect($_SERVER["HTTP_REFERER"]);
        } elseif($quantity > 0) {
            $cost[] = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $quantity, 'purchase_net_unit_cost' => NULL, 'purchase_unit_cost' => NULL, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => NULL, 'overselling' => 1, 'inventory' => 1);
        }
        return $cost;
    }
    
    public function calculateCost($product_id, $warehouse_id, $net_unit_price, $unit_price, $quantity, $product_name, $option_id) {
        $pis = $this->getPurchasedItems($product_id, $warehouse_id);
        $balance_qty = $quantity;
        foreach($pis as $pi) {
            if(!empty($pi) && $balance_qty <= $quantity && $quantity > 0) {
                $purchase_unit_cost = ($pi->net_unit_cost + ($pi->item_tax/$pi->quantity));
                if($pi->quantity_balance >= $quantity && $quantity > 0) {
                    $balance_qty = $pi->quantity_balance - $quantity;
                    $cost_row = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => $pi->id, 'quantity' => $quantity, 'purchase_net_unit_cost' => $pi->net_unit_cost, 'purchase_unit_cost' => $purchase_unit_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => $balance_qty, 'inventory' => 1);
                    $quantity = 0;
                } elseif($quantity > 0) {
                    $quantity = $quantity - $pi->quantity_balance;
                    $balance_qty = $quantity;
                    $cost_row = array('date' => date('Y-m-d'), 'product_id' => $product_id, 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => $pi->id, 'quantity' => $pi->quantity_balance, 'purchase_net_unit_cost' => $pi->net_unit_cost, 'purchase_unit_cost' => $purchase_unit_cost, 'sale_net_unit_price' => $net_unit_price, 'sale_unit_price' => $unit_price, 'quantity_balance' => 0, 'inventory' => 1);
                }
            }
            $cost[] = $cost_row;
            if($quantity == 0) { break; }
        }
        if($quantity > 0) { 
            $this->session->set_flashdata('error', sprintf(lang("quantity_out_of_stock_for_%s"), ($pi->product_name ? $pi->product_name : $product_name)));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        return $cost;
    }
    
    public function nsQTY($product_id, $quantity) {
        $prD = $this->site->getProductByID($product_id);
        $nQTY = $prD->quantity - $quantity;
        $this->db->update('products', array('quantity' => $nQTY), array('id' => $product_id));
    }

    public function addSale($data = array(), $items = array(), $payment = array()) {

        if($data['sale_status'] == 'completed') {
            if($this->Settings->accounting_method != 2 && !$this->Settings->overselling) {
                foreach ($items as $item) {
                    if($this->site->getProductByID($item['product_id'])) {
                        if ($item['product_type'] == 'standard') {
                            $cost[] = $this->calculateCost($item['product_id'], $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id']);
                        } elseif ($item['product_type'] == 'combo') {
                            $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                            foreach ($combo_items as $combo_item) {
                                $cost[] = $this->calculateCost($combo_item->id, $item['warehouse_id'], ($combo_item->qty*$item['quantity']), $item['product_name'], $item['option_id']);
                            }
                        }
                    } elseif ($item['product_type'] == 'manual') {
                        $cost[] = array(array('date' => date('Y-m-d'), 'product_id' => $item['product_id'], 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $item['quantity'], 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $item['net_unit_price'], 'sale_unit_price' => $item['unit_price'], 'quantity_balance' => NULL, 'inventory' => NULL));
                    }
                }
            } else {
                foreach ($items as $item) {
                    if($this->site->getProductByID($item['product_id'])) {
                        if ($item['product_type'] == 'standard') {
                            $cost[] = $this->calculateAVCost($item['product_id'], $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id']);
                        } elseif ($item['product_type'] == 'combo') {
                            $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                            foreach ($combo_items as $combo_item) {
                                $cost[] = $this->calculateAVCost($combo_item->id, $item['warehouse_id'], ($combo_item->qty*$item['quantity']), $item['product_name'], $item['option_id']);
                            }
                        }
                    } elseif ($item['product_type'] == 'manual') {
                        $cost[] = array(array('date' => date('Y-m-d'), 'product_id' => $item['product_id'], 'sale_item_id' => 'sale_items.id', 'purchase_item_id' => NULL, 'quantity' => $item['quantity'], 'purchase_net_unit_cost' => 0, 'purchase_unit_cost' => 0, 'sale_net_unit_price' => $item['net_unit_price'], 'sale_unit_price' => $item['unit_price'], 'quantity_balance' => NULL, 'inventory' => NULL));
                    }
                }
            }
        }

        //$this->sma->print_arrays($cost);

        if ($this->db->insert('sales', $data)) {
            $sale_id = $this->db->insert_id();
            $this->site->updateReference('so');
            foreach ($items as $item) {
                $item['sale_id'] = $sale_id;
                $this->db->insert('sale_items', $item);
                $sale_item_id = $this->db->insert_id();
                if($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {
                    if($item['product_type'] == 'standard') {
                        $this->nsQTY($item['product_id'], $item['quantity']);
                        $this->addQuantity($item['product_id'], $item['warehouse_id'], $item['quantity']);
                        if(isset($item['option_id']) && !empty($item['option_id'])) {
                            $this->updateOptionQuantity($item['option_id'], $item['quantity']);
                        }
                    } elseif($item['product_type'] == 'combo') {
                        $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                        foreach($combo_items as $combo_item) {
                            $qty = $combo_items->qty*$item['quantity'];
                            $this->nsQTY($combo_items->id, $qty);
                            $this->addQuantity($combo_items->id, $item['warehouse_id'], $qty);
                        }
                    }
                } 

                foreach ($cost as $item_costs) {
                    foreach ($item_costs as $item_cost) {
                        if($item_cost['product_id'] == $item['product_id']) {
                            $item_cost['sale_item_id'] = $sale_item_id;
                            $costing[] = $item_cost;
                        }
                    }
                }
            }

            if($data['payment_status'] == 'partial' || $data['payment_status'] == 'paid' && !empty($payment)){
                $payment['sale_id'] = $sale_id;
                $this->db->insert('payments', $payment);
                $this->site->updateReference('pay');
            }

            foreach ($costing as $item_cost) {
                $item_cost['sale_id'] = $sale_id; 
                $this->db->insert('costing', $item_cost);
                if($item_cost['inventory']) {
                    $this->db->update('purchase_items', array('quantity_balance' => $item_cost['quantity_balance']), array('id' => $item_cost['purchase_item_id']));
                }
            }

            return true;

        }

        return false;
    }

    public function usQTY($product_id, $quantity) {
        $prD = $this->site->getProductByID($product_id);
        $nQTY = $prD->quantity + $quantity;
        $this->db->update('products', array('quantity' => $nQTY), array('id' => $product_id));
    }

    public function updateSale($id, $data, $items = array()) {

        $old_items = $this->getAllInvoiceItems($id);
        foreach ($old_items as $oitem) {
            if($old_items->sale_status == 'completed' && $this->site->getProductByID($oitem->product_id)) {
                $pr_qty_details = $this->getProductQuantity($oitem->product_id, $oitem->warehouse_id);
                $qty = $pr_qty_details['quantity'] + $oitem->quantity;
                $this->updateQuantity($oitem->product_id, $oitem->warehouse_id, $qty);
                $this->usQTY($oitem->product_id, $oitem->quantity);
            }
        }

        if ($this->db->update('sales', $data, array('id' => $id)) && $this->db->delete('sale_items', array('sale_id' => $id))) {

            foreach ($items as $item) {
                $this->db->insert('sale_items', $item);
                if($data['sale_status'] == 'completed') {
                    $this->nsQTY($item['product_id'], $item['quantity']);
                    $this->addQuantity($item['product_id'], $item['warehouse_id'], $item['quantity']);
                }
            }
            return true;

        }

        return false;
    }

    public function deleteSale($id) {
        $old_items = $this->getAllInvoiceItems($id);
        foreach ($old_items as $oitem) {
            if($old_items->sale_status == 'completed' && $this->site->getProductByID($oitem->product_id)) {
                $pr_qty_details = $this->getProductQuantity($oitem->product_id, $oitem->warehouse_id);
                $qty = $pr_qty_details['quantity'] + $oitem->quantity;
                $this->updateQuantity($oitem->product_id, $oitem->warehouse_id, $qty);
                $this->usQTY($oitem->product_id, $oitem->quantity);
            }
        }
        
        if ($this->db->delete('sale_items', array('sale_id' => $id)) && $this->db->delete('sales', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function updatePurchaseItem($id, $qty){
        $pi = $this->getPurchaseItemById($id);
        $bln = $pi->quantity_balance + $qty;
        $this->db->update('purchase_items', array('quantity_balance' => $bln), array('id' => $id));
    }

    public function returnSale($data = array(), $items = array(), $payment = array()) {
   
        foreach ($items as $item) {
            if($costings = $this->getCostingLines($item['sale_item_id'], $item['product_id'])) {
                $quantity = $item['quantity'];
                foreach ($costings as $cost) {
                    if ($cost->quantity >= $quantity) {
                        $qty = $cost->quantity - $quantity;
                        $bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0; 
                        $this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
                        $this->updatePurchaseItem($cost->purchase_item_id, $quantity);
                        $quantity = 0;
                    } elseif ($cost->quantity < $quantity) {
                        $qty = $quantity - $cost->quantity;
                        $this->db->delete('costing', array('id' => $cost->id));
                        $this->updatePurchaseItem($cost->purchase_item_id, $quantity);
                        $quantity = $qty;
                    } 
                }
            }
        }
        //$this->sma->print_arrays($items);

        if ($this->db->insert('return_sales', $data)) {
            $return_id = $this->db->insert_id();
            
            foreach ($items as $item) {
                $item['return_id'] = $return_id;
                $this->db->insert('return_items', $item);

                if($this->site->getProductByID($item['product_id'])) {
                    if($item['product_type'] == 'standard') {
                        $this->usQTY($item['product_id'], $item['quantity']);
                        $pr_qty_details = $this->getProductQuantity($item['product_id'], $item['warehouse_id']);
                        $qty = $pr_qty_details['quantity'] + $item['quantity'];
                        $this->updateQuantity($item['product_id'], $item['warehouse_id'], $qty);

                        if(isset($item['option_id']) && !empty($item['option_id']) && $item['option_id'] != 0) {
                            $this->addOptionQuantity($item['option_id'], $item['quantity']);
                        }
                    } elseif($item['product_type'] == 'combo') {
                        $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                        foreach($combo_items as $combo_item) {
                            $qty = $combo_items->qty*$item['quantity'];
                            $this->usQTY($combo_items->id, $qty);
                            $pr_qty_details = $this->getProductQuantity($item['product_id'], $item['warehouse_id']);
                            $wqty = $pr_qty_details['quantity'] + $qty;
                            $this->updateQuantity($item['product_id'], $item['warehouse_id'], $wqty);
                        }
                    }
                } 
            }
            if(!empty($payment)) {
                $payment['sale_id'] = $data['sale_id'];
                $payment['return_id'] = $return_id;
                $this->db->insert('payments', $payment);
                $this->site->updateReference('pay');
            }

            return true;

        }

        return false;
    }

    public function getCostingLines($sale_item_id, $product_id) {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('costing', array('sale_item_id' => $sale_item_id, 'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchaseItemByID($id) {
        $q = $this->db->get_where('purchase_items', array('id' => $id), 1);
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

    public function addDelivery($data = array()) {
        if ($this->db->insert('deliveries', $data)) {
            return true;
        }
        return false;
    }

    public function updateDelivery($id, $data = array()) {
        if ($this->db->update('deliveries', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function getDeliveryByID($id) {
        $q = $this->db->get_where('deliveries', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function deleteDelivery($id) {
        if ($this->db->delete('deliveries', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
    
    public function getInvoicePayments($sale_id) {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
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
    
    public function getPaymentsForSale($sale_id) {
        $this->db->select('payments.date, payments.paid_by, payments.amount, payments.reference_no, users.first_name, users.last_name, type')
        ->join('users', 'users.id=payments.created_by', 'left');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function addPayment($data = array()) {
        $inv = $this->getInvoiceByID($data['sale_id']);
        $paid = $inv->paid + $data['amount'];
        if($inv->grand_total > $paid) {
            if ($this->db->insert('payments', $data) && $this->db->update('sales', array('paid' => $paid, 'payment_status' => 'partial'), array('id' => $data['sale_id']))) {
                return true;
            }
        } else {
            if ($this->db->insert('payments', $data) && $this->db->update('sales', array('paid' => $paid, 'payment_status' => 'paid'), array('id' => $data['sale_id']))) {
                return true;
            }
        }
        return false;
    }
    
    public function updatePayment($id, $data = array()) {
        $opay = $this->getPaymentByID($id);
        $inv = $this->getInvoiceByID($data['sale_id']);
        $paid = $inv->paid + ($data['amount'] - $opay->amount);
        if($inv->grand_total > $paid) {
            if ($this->db->update('payments', $data, array('id' => $id)) && $this->db->update('sales', array('paid' => $paid, 'payment_status' => 'partial'), array('id' => $data['sale_id']))) {
                return true;
            }
        } else {
            if ($this->db->update('payments', $data, array('id' => $id)) && $this->db->update('sales', array('paid' => $paid, 'payment_status' => 'paid'), array('id' => $data['sale_id']))) {
                return true;
            }
        }
        return false;
    }
    
    public function deletePayment($id) {
        $opay = $this->getPaymentByID($id);
        $inv = $this->getInvoiceByID($data['sale_id']);
        $paid = $inv->paid - $opay->amount;
        if($paid <= 0 && $inv->due_date >= date('Y-m-d')) {
            $this->db->update('sales', array('paid' => $paid, 'payment_status' => 'pending'), array('id' => $data['sale_id']));
        } elseif($paid <= 0 && $inv->due_date <= date('Y-m-d')) {
            $this->db->update('sales', array('paid' => $paid, 'payment_status' => 'due'), array('id' => $data['sale_id']));
        } elseif($inv->grand_total > $paid) {
            $this->db->update('sales', array('paid' => $paid, 'payment_status' => 'partial'), array('id' => $data['sale_id']));
        } else {
            $this->db->update('sales', array('paid' => $paid, 'payment_status' => 'paid'), array('id' => $data['sale_id']));
        }
        
        if ($this->db->delete('payments', array('id' => $id))) {
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
    
    /* ----------------- Gift Cards --------------------- */
    
    public function addGiftCard($data = array()) {
        if ($this->db->insert('gift_cards', $data)) {
            return true;
        }

        return false;
    }

    public function updateGiftCard($id, $data = array()) {
        $this->db->where('id', $id);
        if ($this->db->update('gift_cards', $data)) {
            return true;
        }

        return false;
    }

    public function deleteGiftCard($id) {

        if ($this->db->delete('gift_cards', array('id' => $id))) {
            return true;
        }

        return FALSE;
    }

    public function getPaypalSettings() {
        $q = $this->db->get_where('paypal', array('id' => 1));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSkrillSettings() {
        $q = $this->db->get_where('skrill', array('id' => 1));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

}
