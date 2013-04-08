<?php

/**
 * Table model for floor
 */
class Source_FloorController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Floor';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Floor';

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
    protected function setupFloorGrid()
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
        $this->view->result = array("Floor" => $response->result);
        $this->view->data   = array("Floor" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupFloorGrid();
        $this->dataContext = "listBuilding";
        $this->listDataReturnView("Object_Building");
    }

    /**
     * Retrieve data grid for display.
     */
    public function floorGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupFloorGrid();
    }


}

