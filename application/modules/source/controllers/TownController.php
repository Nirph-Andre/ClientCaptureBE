<?php

/**
 * Table model for town
 */
class Source_TownController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Town';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Town';

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
    protected function setupTownGrid()
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
        $this->view->result = array("Town" => $response->result);
        $this->view->data   = array("Town" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupTownGrid();
        $this->dataContext = "listLocation";
        $this->listDataReturnView("Object_Location");
    }

    /**
     * Retrieve data grid for display.
     */
    public function townGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupTownGrid();
    }


}

