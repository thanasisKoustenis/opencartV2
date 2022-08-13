<?php


class ControllerProductMyproduct extends Controller
{

    public function index()
    {
        $this->load->language('product/product');

        $this->load->model('catalog/category');

        $rowsData = $this->model_catalog_category->getCategories();

        echo $this->createXML($rowsData, 'categories', 'category');

        $data = array();
        $data['example'] = 'Hello';
        $data['example2'] = "Hey";
        $data['example3'] = $rowsData[0]['category_id'];
        
        $this->response->setOutput($this->load->view('product/testview', $data));
    
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
