<?php

/**
 * Table model for material
 */
class Source_MaterialController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Material';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Material';

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
    protected function setupMaterialGrid()
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
        $this->view->result = array("Material" => $response->result);
        $this->view->data   = array("Material" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupMaterialGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function materialGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupMaterialGrid();
    }


}

