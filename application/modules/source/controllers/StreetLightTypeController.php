<?php

/**
 * Table model for street_light_type
 */
class Source_StreetLightTypeController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_StreetLightType';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'StreetLightType';

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
    protected function setupStreetLightTypeGrid()
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
        $this->view->result = array("StreetLightType" => $response->result);
        $this->view->data   = array("StreetLightType" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupStreetLightTypeGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function streetLightTypeGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupStreetLightTypeGrid();
    }


}

