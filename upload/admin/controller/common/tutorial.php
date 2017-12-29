<?php
class ControllerCommonTutorial extends Controller {
    public function test() {
        if (!defined("OPENCART_CLI_MODE") || OPENCART_CLI_MODE === FALSE) {
            echo "Intruder alert."; exit;
        }
		$test = $this->newdb->query("SELECT * FROM oc_product");
		$test2 = $this->db->query("SELECT * FROM oc_product WHERE date_modified > '2015-08-30'"); //SELECT * FROM tickets WHERE date_modified > '2011-08-26'
		$this->load->model('catalog/product');
		$test3 = $this->model_catalog_product->getProduct(28);
        oc_cli_output("Hello, Shagy!");
        oc_cli_output("test3: ".$test3['name']);
		//oc_cli_output("test: " .$test->row['product_id'][0]);
        //oc_cli_output("test2: " .$test2->row['product_id'][1]);
		oc_cli_output("test3: ".$test3['quantity']);
		
		$size = $test->num_rows;
		$size2 = $test2->num_rows;
		
		oc_cli_output("size: ".$size);
		oc_cli_output("size2: ".$size2);
		
		for($i = 0; $i < $size; $i++){
			$ok = false;
			for($y = 0; $y < $size2; $y++){
				
				if((int)$test->rows[$i]['product_id'] === (int)$test2->rows[$y]['product_id']){
					$ok = true;
					oc_cli_output("id: " .$test->rows[$i]['product_id']);
					oc_cli_output("id2: " .$test2->rows[$y]['product_id']);
					if((int)$test->rows[$i]['quantity'] === (int)$test2->rows[$y]['quantity']){
						oc_cli_output("i: " .$i. " y: " .$y);
						oc_cli_output("EQUAL Quantity");
						oc_cli_output('quantity: ' .$test->rows[$i]['quantity'] .' = ' .$test2->rows[$y]['quantity']);
						//$this->model_catalog_product->editProduct($id, $data);
					}else{
						$quantity = (int)$test->rows[$i]['quantity'];
						//$this->db->query('UPDATE oc_product SET quantity = ' .$quantity. ' WHERE product_model = '.$model);
						oc_cli_output("NOT EQUAL Quantity, So updated, new value is: " .$quantity);
						oc_cli_output("not quantity: " .$test->rows[$i]['quantity'] ." != " .$test2->rows[$y]['quantity']);
						//$this->model_catalog_product->editProduct(28, $test3);
					}
				}
			}
			
			if(!$ok){
				oc_cli_output("THIS IS NEW PRODUCT!!!");
			}
		}
		
		/*
		if($test->row['model'] === $test2->row['model']){
			oc_cli_output("EQUAL");
			//$this->model_catalog_product->editProduct($id, $data);
		}else{
			//$test3['quantity'] = 939;
			//$this->db->query('UPDATE oc_product SET quantity = 939 WHERE product_id = 28');
			oc_cli_output("NOT EQUAL");
			//$this->model_catalog_product->editProduct(28, $test3);
		}
		*/
        //oc_cli_output("test: " .var_dump($test));
        //oc_cli_output("test2: " .var_dump($test2));
    }
}
?>
