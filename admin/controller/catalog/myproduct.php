<?php


class ControllerCatalogMyproduct extends Controller
{

    public function index()
    {   
        $url = '';

        $this->load->language('catalog/product');
        $this->load->model('catalog/product');
        $this->document->setTitle($this->language->get('heading_title'));

        $data =array();
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->response->redirect($this->url->link('catalog/myproduct', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->response->setOutput($this->load->view('catalog/product', $data));
        
    }

    public function getCategories() {
        $this->load->language('catalog/category');

        $this->load->model('catalog/category');

        $rowsData = $this->model_catalog_category->getCategories();

        echo $this->createXML($rowsData, 'categories', 'category');

        $data = array();
        $data['example'] = 'Hello';
        $data['example2'] = "Hey";
        $data['example3'] = $rowsData[0]['category_id'];
        $data['categories'][] = $rowsData;
        
        $this->response->setOutput($this->load->view('catalog/testview', $data));
    }

    public function getAllProducts() {
        $this->load->language('product/product');

        $this->load->model('catalog/product');

        $rows = $this->model_catalog_product->getProducts();

        echo $this->createXML($rows, 'products', 'product');
    }

    public function addProduct() {
        $this->load->language('product/product');

        $this->load->model('catalog/category');
    }

    public function createXML($rows, $xmlParent, $xmlChild)
    {

        $this->load->language('product/product');

        $this->load->model('catalog/category');

        $xml = new DOMDocument('1.0');
        $xml->formatOutput = true;
        $parentName = $xml->createElement("$xmlParent");
        $xml->appendChild($parentName);

        $row_num = 0;


        while (isset($rows[$row_num])) {

            $childName = $xml->createElement("$xmlChild");
            $parentName->appendChild($childName);

            foreach ($rows[$row_num] as $key => $value) {

                $colName = $xml->createElement("$key", $value);
                $childName->appendChild($colName);

            }
            $row_num = $row_num + 1;
        }

        return "" . $xml->saveXML() . "";
        $xml->save("report.xml");
    }
}
