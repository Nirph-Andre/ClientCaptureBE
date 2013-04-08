<?php

/**
 * Table model for app_link_request
 */
class Source_AppLinkRequestController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_AppLinkRequest';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'AppLinkRequest';

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
    protected function setupAppLinkRequestGrid()
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
        $this->view->result = array("AppLinkRequest" => $response->result);
        $this->view->data   = array("AppLinkRequest" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupAppLinkRequestGrid();
        $this->dataContext = "listProfile";
        $this->listDataReturnView("Object_Profile");
    }

    /**
     * Retrieve data grid for display.
     */
    public function appLinkRequestGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupAppLinkRequestGrid();
    }


}

