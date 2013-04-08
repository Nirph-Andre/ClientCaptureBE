<?php

/**
 * Table model for lib_address
 */
class Source_LibAddressController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibAddress';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibAddress';

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
    protected function setupLibAddressGrid()
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
        $this->view->result = array("LibAddress" => $response->result);
        $this->view->data   = array("LibAddress" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibAddressGrid();
        $this->dataContext = "listLibCountry";
        $this->listDataReturnView("Object_LibCountry");
    }

    /**
     * Retrieve data grid for display.
     */
    public function libAddressGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibAddressGrid();
    }

    /**
     * Handle data dependancy dropdown lists.
     */
    public function listDependancySelectAction()
    {
        $this->listDependancyDataReturnSelectOptions(array(
        		"Lib City" => "Object_LibCity",
        		"Lib Region" => "Object_LibRegion",
        		));
    }


}

