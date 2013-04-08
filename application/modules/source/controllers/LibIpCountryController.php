<?php

/**
 * Table model for lib_ip_country
 */
class Source_LibIpCountryController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibIpCountry';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibIpCountry';

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
    protected function setupLibIpCountryGrid()
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
        $this->view->result = array("LibIpCountry" => $response->result);
        $this->view->data   = array("LibIpCountry" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibIpCountryGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libIpCountryGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibIpCountryGrid();
    }


}

