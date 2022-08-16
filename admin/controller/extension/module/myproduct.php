<?php

class ControllerExtensionModuleMyproduct extends Controller
{

    public function index()
    {
        $this->load->language('catalog/product');

        $this->load->model('catalog/product');

        $this->load->model('catalog/category');


        $data = array();

        $url = '';

        // $data['products'] = $this->model_catalog_product->getProducts();
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $data['add'] = $this->url->link('extension/module/myproduct/addProduct', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['get'] = $this->url->link('extension/module/myproduct/getXMLProducts', 'user_token=' . $this->session->data['user_token'] . $url, true);

        // $this->response->setOutput($this->load->view('product/product', $data));
        $this->response->setOutput($this->load->view('extension/module/myview', $data));
    }

    public function addProduct()
    {

        $this->load->model('catalog/product');

        $postData = $this->request->post;

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            // $postData = trim(file_get_contents('php://input'));

            if (isset($postData)) {

                
                // $xmlDoc = new DOMDocument();
                // $xmlDoc->load($postData);

                // foreach ($xmlDoc->childNodes as $item) {
                //     echo $item->nodeName . ' = ' . $item->nodeValue . '<br>';
                // }

                $xml = simplexml_load_string($postData);
                $array = $this->convertXMLtoArray($xml);
                $this->model_catalog_product->addProduct($array);
            }

            $this->response->redirect($this->url->link('extension/module/myproduct', 'user_token=' . $this->session->data['user_token'], true));
        }
    }

    public function getXMLProducts()
    {
        $this->load->language('product/product');

        $this->load->model('catalog/product');

        $rowsData = $this->model_catalog_product->getProducts();

        // $this->response->redirect($this->url->link('extension/module/myproduct', 'user_token=' . $this->session->data['user_token'], true));

        $data['prodXML'] = $this->makeXML($rowsData, 'products', 'product');
        $data['index'] = $this->url->link('extension/module/myproduct', 'user_token=' . $this->session->data['user_token'], true);

        $this->response->setOutput($this->load->view('extension/module/getProductsXML', $data));
        // $this->response->setOutput($this->load->view('extension/module/myview', $data));



    }

    public function getXMLCategories()
    {
        $this->load->model('catalog/category');

        $rowsData = $this->model_catalog_category->getCategories();

        echo $this->createXML($rowsData, 'categories', 'category');
    }

    public function getCategories()
    {

        $this->load->model('catalog/category');

        $rowsData = $this->model_catalog_category->getCategories();

        $data = array();
        $data['example'] = 'Hello';
        $data['example2'] = "Hey";
        $data['example3'] = $rowsData[0]['category_id'];
        $data['example4'] = "Good morning";
        $data['categories'][] = $rowsData;

        $this->response->setOutput($this->load->view('product/testview', $data));
    }

    public function convertXMLtoArray($xml) {
    
        $json = json_encode($xml);
        $array = json_decode($json, true);
        return $array;
    }

    public function createXML($rows, $xmlParent, $xmlChild)
    {

        // $this->load->language('product/product');

        // $this->load->model('catalog/category');

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
        $xml->save("products.xml");
    }

    public function makeXML($rows, $xmlParent, $xmlChild)
    {
        $xml = new DOMDocument('1.0');
        $xml->formatOutput = true;
        $parentName = $xml->createElement("$xmlParent");
        $xml->appendChild($parentName);

        $row_num = 0;


        foreach ($rows as $product_key => $row) {

            $childName = $xml->createElement("$xmlChild");
            $parentName->appendChild($childName);

            foreach ($row as $key => $value) {

                $colName = $xml->createElement("$key", $value);
                $childName->appendChild($colName);
            }
            $row_num = $row_num + 1;
        }

        return "" . $xml->saveXML() . "";
        // $xml->save("report.xml");
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/account')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}
