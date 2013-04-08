<?php

/**
 * Table model for owner
 */
class Source_OwnerController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Owner';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Owner';

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
    protected function setupOwnerGrid()
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
        $this->view->result = array("Owner" => $response->result);
        $this->view->data   = array("Owner" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupOwnerGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function ownerGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupOwnerGrid();
    }


}

