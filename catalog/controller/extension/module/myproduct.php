<?php


class ControllerExtensionModuleMyproduct extends Controller
{

    public function index()
    {
        $this->load->language('product/product');

        $this->load->model('catalog/product');

        $this->load->model('catalog/category');


        $data = array();

        $data['products'] = $this->model_catalog_product->getProducts();
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $data['cart'] = $this->load->controller('common/cart');

        // $this->response->setOutput($this->load->view('product/product', $data));
        $this->response->setOutput($this->load->view('extension/module/myview', $data));
    }

    public function addProduct()
    {

        $this->load->model('catalog/product');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            $postData = trim(file_get_contents('php://input'));

            if (isset($postData)) {

                $xml = simplexml_load_string($postData);


                $this->model_catalog_product->addProduct();

            }
        }
    }

    public function getXMLProducts()
    {   
        $this->load->language('product/product');
        
        $this->load->model('catalog/product');

        $rowsData = $this->model_catalog_product->getProducts();

        echo $this->makeXML($rowsData, 'products', 'product');
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
        // $xml->save("report.xml");
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
}
