<?php

class ModelToolUpdate extends Model {
	private $logger;

    public function getModel($model) {

        $query = $this->db->query("SELECT model FROM `" . DB_PREFIX . "product` WHERE model = '" . $model . "'");

        return $query->row;
    }

    public function updateQuantity($model, $quantity) {
        $query = "UPDATE `" . DB_PREFIX . "product` SET quantity = '$quantity' WHERE model = '$model'";

        //$this->db->query($query);
		$this->log->write($query);
    }
	
	public function logQuantity($message, $type)
    {
        $this->logger = new Log('updateQuantity.log');
        $this->logger->Write($type .': '. $message);
    }

}