<?php

/**
 * Table model for lib_xmlrpc_profile
 */
class Source_LibXmlrpcProfileController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibXmlrpcProfile';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibXmlrpcProfile';

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
    protected function setupLibXmlrpcProfileGrid()
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
        $this->view->result = array("LibXmlrpcProfile" => $response->result);
        $this->view->data   = array("LibXmlrpcProfile" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibXmlrpcProfileGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libXmlrpcProfileGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibXmlrpcProfileGrid();
    }


}

