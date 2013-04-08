<?php

/**
 * Table model for lib_video
 */
class Source_LibVideoController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibVideo';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibVideo';

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
    protected function setupLibVideoGrid()
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
        $this->view->result = array("LibVideo" => $response->result);
        $this->view->data   = array("LibVideo" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibVideoGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libVideoGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibVideoGrid();
    }


}

