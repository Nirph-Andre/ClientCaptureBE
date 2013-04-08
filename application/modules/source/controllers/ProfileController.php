<?php

/**
 * Table model for profile
 */
class Source_ProfileController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Profile';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Profile';

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
    protected function setupProfileGrid()
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
        $this->view->result = array("Profile" => $response->result);
        $this->view->data   = array("Profile" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupProfileGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function profileGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupProfileGrid();
    }


}

