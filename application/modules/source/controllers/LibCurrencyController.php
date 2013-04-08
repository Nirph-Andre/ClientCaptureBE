<?php

/**
 * Table model for lib_currency
 */
class Source_LibCurrencyController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibCurrency';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibCurrency';

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
    protected function setupLibCurrencyGrid()
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
        $this->view->result = array("LibCurrency" => $response->result);
        $this->view->data   = array("LibCurrency" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibCurrencyGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libCurrencyGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibCurrencyGrid();
    }


}

