<?php

/**
 * Table model for lib_action_reference
 */
class Source_LibActionReferenceController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibActionReference';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibActionReference';

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
    protected function setupLibActionReferenceGrid()
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
        $this->view->result = array("LibActionReference" => $response->result);
        $this->view->data   = array("LibActionReference" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibActionReferenceGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libActionReferenceGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibActionReferenceGrid();
    }


}

