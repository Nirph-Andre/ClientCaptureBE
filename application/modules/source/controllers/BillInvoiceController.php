<?php

/**
 * Table model for bill_invoice
 */
class Source_BillInvoiceController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_BillInvoice';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'BillInvoice';

    /**
     * Action controller initializer.
     */
    public function init()
    {
        if (!Struct_Registry::isAuthenticated())
        {
        	header("Location: /login");
        	exit();
        }
    }

    /**
     * Setup data grid on default value object.
     */
    protected function setupBillInvoiceGrid()
    {
        $sqf = new Struct_Util_SmartQueryFilter();
        $response = $sqf->handleGrid(
        	$this->getRequest(), false, $this->_defaultObjectName,
        	array(), // default filters
        	array(), // default order
        	array(), // exclude
        	array(), // chain
        	10, "Json"
        );
        $this->view->result = array("BillInvoice" => $response->result);
        $this->view->data   = array("BillInvoice" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupBillInvoiceGrid();
        $this->dataContext = "listProfile";
        $this->listDataReturnView("Object_Profile");
        $this->dataContext = "listLibCurrency";
        $this->listDataReturnView("Object_LibCurrency");
    }

    /**
     * Retrieve data grid for display.
     */
    public function billInvoiceGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupBillInvoiceGrid();
    }


}

