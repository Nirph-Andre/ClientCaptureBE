<?php

/**
 * Table model for lib_service
 */
class Source_LibServiceController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibService';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibService';

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
    protected function setupLibServiceGrid()
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
        $this->view->result = array("LibService" => $response->result);
        $this->view->data   = array("LibService" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibServiceGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libServiceGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibServiceGrid();
    }


}

