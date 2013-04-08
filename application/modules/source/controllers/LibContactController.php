<?php

/**
 * Table model for lib_contact
 */
class Source_LibContactController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibContact';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibContact';

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
    protected function setupLibContactGrid()
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
        $this->view->result = array("LibContact" => $response->result);
        $this->view->data   = array("LibContact" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibContactGrid();
        $this->dataContext = "listLibAddress";
        $this->listDataReturnView("Object_LibAddress");
    }

    /**
     * Retrieve data grid for display.
     */
    public function libContactGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibContactGrid();
    }


}

