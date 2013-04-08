<?php

/**
 * Table model for lib_city
 */
class Source_LibCityController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibCity';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibCity';

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
    protected function setupLibCityGrid()
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
        $this->view->result = array("LibCity" => $response->result);
        $this->view->data   = array("LibCity" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibCityGrid();
        $this->dataContext = "listLibCountry";
        $this->listDataReturnView("Object_LibCountry");
    }

    /**
     * Retrieve data grid for display.
     */
    public function libCityGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibCityGrid();
    }

    /**
     * Handle data dependancy dropdown lists.
     */
    public function listDependancySelectAction()
    {
        $this->listDependancyDataReturnSelectOptions(array(
        		"Lib Region" => "Object_LibRegion",
        		));
    }


}

