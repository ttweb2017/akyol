<?php
class ControllerToolUpdate extends Controller {
	private $error = array();
	
	public function index() {
        $this->load->language('tool/update');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('tool/update', 'token=' . $this->session->data['token'], true)
        );

        $data['token'] = $this->session->data['token'];
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_progress'] = $this->language->get('entry_progress');
		
		$data['tab_restore'] = $this->language->get('tab_restore');

		$data['button_import'] = $this->language->get('button_import');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/update', $data));
    }
	
	public function import() {
        $this->load->language('tool/update');

        $json = array();

        if (!$this->user->hasPermission('modify', 'tool/update')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (isset($this->request->files['import']['tmp_name']) && is_uploaded_file($this->request->files['import']['tmp_name'])) {
            $filename = tempnam(DIR_UPLOAD, 'bac');

            move_uploaded_file($this->request->files['import']['tmp_name'], $filename);
        } elseif (isset($this->request->get['import'])) {
            $filename = html_entity_decode($this->request->get['import'], ENT_QUOTES, 'UTF-8');
        } else {
            $filename = '';
        }

        if (!is_file($filename)) {
            $json['error'] = $this->language->get('error_file');
        }

        if (isset($this->request->get['position'])) {
            $position = $this->request->get['position'];
        } else {
            $position = 0;
        }
        
        $this->load->model('tool/update');

		$left = $this->language->get('text_left');
		
		if (!$json) {

            $handle = fopen($filename, 'r');

            fseek($handle, $position, SEEK_SET);

            while ($line = fgetcsv($handle)) {
                //$position = ftell($handle);

                //$line = fgets($handle, 1000000);

                $model = $this->db->escape($line[0]);
                
				$new_product = true;
				
                $local_model = array();
				
				if($model != 'MODEL' && $model != ''){
                    $local_model = $this->model_tool_update->getModel($model);
                }else{
					$new_product = false;
				}
                
                if($local_model){
                    $quantity = $this->db->escape($line[1]);
                
                    $quantity_int = (int)strtok($quantity, 'P');
                
                    $this->model_tool_update->updateQuantity($local_model['model'], $quantity_int);
					
                    $new_product = false;
                }
                
                if($new_product){
                    $this->model_tool_update->logQuantity($left . $model, 'Update Quantity');
                }
                
            }

            $position = ftell($handle);

            $size = filesize($filename);

            $json['total'] = round(($position / $size) * 100);

            if ($position && !feof($handle)) {
                $json['next'] = str_replace('&amp;', '&', $this->url->link('tool/update/import', 'token=' . $this->session->data['token'] . '&import=' . $filename . '&position=' . $position, true));

                fclose($handle);
            } else {
                fclose($handle);

                unlink($filename);

                $json['success'] = $this->language->get('text_success');

                $this->cache->delete('*');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
