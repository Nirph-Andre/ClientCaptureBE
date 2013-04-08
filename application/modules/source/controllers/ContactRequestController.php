<?php

/**
 * Table model for contact_request
 */
class Source_ContactRequestController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_ContactRequest';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'ContactRequest';

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
    protected function setupContactRequestGrid()
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
        $this->view->result = array("ContactRequest" => $response->result);
        $this->view->data   = array("ContactRequest" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupContactRequestGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function contactRequestGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupContactRequestGrid();
    }


}

