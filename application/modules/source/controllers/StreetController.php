<?php

/**
 * Table model for street
 */
class Source_StreetController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Street';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Street';

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
    protected function setupStreetGrid()
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
        $this->view->result = array("Street" => $response->result);
        $this->view->data   = array("Street" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupStreetGrid();
        $this->dataContext = "listTown";
        $this->listDataReturnView("Object_Town");
    }

    /**
     * Retrieve data grid for display.
     */
    public function streetGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupStreetGrid();
    }


}

