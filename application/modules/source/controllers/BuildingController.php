<?php

/**
 * Table model for building
 */
class Source_BuildingController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Building';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Building';

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
    protected function setupBuildingGrid()
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
        $this->view->result = array("Building" => $response->result);
        $this->view->data   = array("Building" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupBuildingGrid();
        $this->dataContext = "listLocation";
        $this->listDataReturnView("Object_Location");
    }

    /**
     * Retrieve data grid for display.
     */
    public function buildingGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupBuildingGrid();
    }


}

