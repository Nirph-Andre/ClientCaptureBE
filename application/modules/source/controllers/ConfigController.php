<?php

/**
 * Table model for config
 */
class Source_ConfigController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Config';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Config';

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
    protected function setupConfigGrid()
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
        $this->view->result = array("Config" => $response->result);
        $this->view->data   = array("Config" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupConfigGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function configGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupConfigGrid();
    }


}

