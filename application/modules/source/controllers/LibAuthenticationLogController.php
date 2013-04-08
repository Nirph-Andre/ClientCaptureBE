<?php

/**
 * Table model for lib_authentication_log
 */
class Source_LibAuthenticationLogController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibAuthenticationLog';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibAuthenticationLog';

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
    protected function setupLibAuthenticationLogGrid()
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
        $this->view->result = array("LibAuthenticationLog" => $response->result);
        $this->view->data   = array("LibAuthenticationLog" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibAuthenticationLogGrid();
        $this->dataContext = "listProfile";
        $this->listDataReturnView("Object_Profile");
    }

    /**
     * Retrieve data grid for display.
     */
    public function libAuthenticationLogGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibAuthenticationLogGrid();
    }


}

