<?php
class ControllerExtensionAnalyticsUniversalChat extends Controller {

    private function getEcommerceView() {

        $piwik_product_info_sku = "";

        $universal_chat_use_sku = $this->config->get('universal_chat_use_sku');

        /* Get the Category info */
        // First, check the GET variable 'path' is set
        // Set to false - category reporting not fully supported in this version
        if (isset($this->request->get['path']) and false) {
            //Initialise variables etc
            $piwik_category = '';

            // Split the path variable into its ID parts
            // Path variable is format of 'x' for a top category,
            // format 'x_x' for a second-level category,
            // format 'x_x_x' for a third level, etc.
            // Each 'x' is the ID of the category at that level
            $parts = explode('_', (string)$this->request->get['path']);

            // For each ID in the path...
            foreach ($parts as $path_id) {
                // Get the info for this category ID
                // Uses function from the catalog/category model
                $category_info = $this->model_catalog_category->getCategory($path_id);

                if ($category_info) {
                    if (!$piwik_category) {
                        // First item in category list
                        // Set start of string up for Javascript array
                        // Then add name of category
                        $piwik_category = '"' . $category_info['name'];
                    } else {
                        // Somewhere in middle of category list
                        // Add name of category
                        $piwik_category .= ' > ' . $category_info['name'];
                    }
                }
            }
            // Finish off the end text for the Javascript string
            $piwik_category .= '"';
        } else {
            // If there is no 'path' GET variable, then we are not in a category
            // So set the appropriate 'false' text to use (see piwik JavaScript function)
            $piwik_category = "categoryName = false";
        }


        /* Get the Product info */
        if (isset($this->request->get['product_id'])) {
            // Read the product ID from the GET variable
            $product_id = $this->request->get['product_id'];

            // Look up the product info using the product ID
            // Uses function from the catalog/product model
            $product_info = $this->model_catalog_product->getProduct($product_id);

            // Get the individual pieces of info
            if ($universal_chat_use_sku) {
                $piwik_product_info_sku = '"' . $product_info['sku'] . '"';
            } else {
                $piwik_product_info_sku = '"' . $product_info['model'] . '"';
            }

            $piwik_product = '"' . $product_info['name'] . '"';
            $piwik_price = (string)$product_info['price'];
        } else {
            // If there is no 'product_id' GET variable, then we are not in a product
            // So set the appropriate 'false' text to use (see piwik JavaScript function)
            $piwik_product_info_sku = "productSKU = false";
            $piwik_product = "productName = false";
            $piwik_price = "price = false";
        }

        // Return the javascript text to insert into footer
        return '_paq.push(["setEcommerceView",' .
        $piwik_product_info_sku . ',' .
        $piwik_product . ',' .
        $piwik_category . ',' .
        $piwik_price . ']);';
    }

    private function getEcommerceCartUpdate() {

        $universal_chat_use_sku = $this->config->get('universal_chat_use_sku');

        $ecommerceCartUpdate = "";

        /* Get the Cart info */
        // First, check if the cart has items in
        if ($this->cart->hasProducts()) {
            $cart_total = 0;

            // Read all the info about items in the cart
            $products = $this->cart->getProducts();

            $piwik_category = "categoryName = false";

            // For product in the cart...
            foreach ($products as $product) {

                // Decide whether to use 'Model' or 'SKU' from product info
                if ($universal_chat_use_sku) {
                    $piwik_product_info_sku = '"' . $product['sku'] . '"';
                } else {
                    $piwik_product_info_sku = '"' . $product['model'] . '"';
                }

                // Add this cart item to the piwik ecommerce cart
                $piwik_cart_item = '"' . $product['name'] . '"';
                $piwik_price = (string)$product['price'];
                $piwik_quantity = (string)$product['quantity'];

                $ecommerceCartUpdate .= '_paq.push(["addEcommerceItem",' .
                    $piwik_product_info_sku . ',' .
                    $piwik_cart_item . ',' .
                    $piwik_category . ',' .
                    $piwik_price . ',' .
                    $piwik_quantity . ']);' . "\n";

                $cart_total += ($product['price'] * $product['quantity']);
            }

            $ecommerceCartUpdate .= '_paq.push(["trackEcommerceCartUpdate",' .
                $cart_total . ']);'. "\n";


        }

        return $ecommerceCartUpdate;
    }

    private function getOrderTrackingCode() {

        $universal_chat_use_sku = $this->config->get('universal_chat_use_sku');

        $orderTrackingCode = "";

        if(isset($this->request->get['route']) && $this->request->get['route'] == "checkout/success")
        {
            if (isset($this->session->data['last_order_id']) && ( ! empty($this->session->data['last_order_id']))  ) {
                $order_id = $this->session->data['last_order_id'];

                $this->load->model('account/order');

                //$order_info = $this->model_account_order->getOrder($order_id);
                $order_info_products = $this->model_account_order->getOrderProducts($order_id);
                $order_info_totals = $this->model_account_order->getOrderTotals($order_id);

                $piwik_category = "categoryName = false";

                // Add ecommerce items for each product in the order before tracking
                foreach ($order_info_products as $product) {
                    // Get the info for this product ID
                    //$product = $this->model_catalog_product->getProduct($order_product['product_id']);

                    // Decide whether to use 'Model' or 'SKU' from product info
                    if ($universal_chat_use_sku) {
                        $piwik_product_info_sku = '"' . $product['sku'] . '"';
                    } else {
                        $piwik_product_info_sku = '"' . $product['model'] . '"';
                    }


                    $piwik_order_item = '"' . $product['name'] . '"';
                    $piwik_price = (string)$product['price'];
                    $piwik_quantity = (string)$product['quantity'];

                    // Add this cart item to the piwik ecommerce cart (Piwik PHP API function)
                    $orderTrackingCode .= '_paq.push(["addEcommerceItem",' .
                        $piwik_product_info_sku . ',' .
                        $piwik_order_item . ',' .
                        $piwik_category . ',' .
                        $piwik_price . ',' .
                        $piwik_quantity . ']);' . "\n";
                }

                // Set everything to zero to start with
                $order_shipping = 0;
                $order_subtotal = 0;
                $order_taxes = 0;
                $order_grandtotal = 0;
                $order_discount = 0;

                // Find out shipping / taxes / total values
                foreach ($order_info_totals as $order_totals) {
                    switch ($order_totals['code']) {
                        case "shipping":
                            $order_shipping += $order_totals['value'];
                            break;
                        case "sub_total":
                            $order_subtotal += $order_totals['value'];
                            break;
                        case "tax":
                            $order_taxes += $order_totals['value'];
                            break;
                        case "total":
                            $order_grandtotal += $order_totals['value'];
                            break;
                        case "coupon":
                            $order_discount += $order_totals['value'];
                            break;
                        case "voucher":
                            $order_discount += $order_totals['value'];
                            break;
                        default:
                            $this->log->write("Piwik OpenCart mod: unknown order total code '" .
                                $order_totals['code'] . "'.");
                            break;
                    }
                }

                $order_id = '"' . $order_id . '"';
                $order_grandtotal = (string)$order_grandtotal;
                $order_subtotal = (string)$order_subtotal;
                $order_taxes = (string)$order_taxes;
                $order_shipping = (string)$order_shipping;
                $order_discount = (string)$order_discount;

                // Now track the Ecommerce order for the above items (Piwik PHP API function)
                $orderTrackingCode .= '_paq.push(["trackEcommerceOrder",' .
                    $order_id . ',' .               // Order ID
                    $order_grandtotal . ',' .       // Grand Total
                    $order_subtotal . ',' .         // Sub Total
                    $order_taxes . ',' .            // Tax
                    $order_shipping . ',' .         // Shipping
                    $order_discount . ']);'. "\n"; // Discount from coupon/vouchers

            }
        }

        return $orderTrackingCode;

    }

    private function getUserId(){
        $userIdText = "";

        if ($this->customer->isLogged()) {

            $customer = '"' . $this->customer->getId() . '"';

            $userIdText .= '_paq.push(["setUserId", ' .
                $customer .  ']);'. "\n";
        }

        return $userIdText;
    }

    public function index() {

        $ecommerceText = "var _paq = _paq || [];" . "\n" . $this->getUserId() . $this->getOrderTrackingCode() . $this->getEcommerceCartUpdate() . $this->getEcommerceView();

        $htmlWidgetCode = $this->config->get('universal_chat_code');
        $htmlWidgetCode = str_replace("var _paq = _paq || [];", $ecommerceText, $htmlWidgetCode);

		return html_entity_decode($htmlWidgetCode, ENT_QUOTES, 'UTF-8');
	}
}
