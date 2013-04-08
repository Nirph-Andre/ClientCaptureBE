<?php

/**
 * Table model for bill_invoice_line_item
 */
class Source_BillInvoiceLineItemController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_BillInvoiceLineItem';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'BillInvoiceLineItem';

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
    protected function setupBillInvoiceLineItemGrid()
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
        $this->view->result = array("BillInvoiceLineItem" => $response->result);
        $this->view->data   = array("BillInvoiceLineItem" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupBillInvoiceLineItemGrid();
        $this->dataContext = "listBillInvoice";
        $this->listDataReturnView("Object_BillInvoice");
        $this->dataContext = "listLibService";
        $this->listDataReturnView("Object_LibService");
    }

    /**
     * Retrieve data grid for display.
     */
    public function billInvoiceLineItemGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupBillInvoiceLineItemGrid();
    }


}

