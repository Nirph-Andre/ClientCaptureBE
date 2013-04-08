<?php

/**
 * Table model for pole_length
 */
class Source_PoleLengthController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_PoleLength';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'PoleLength';

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
    protected function setupPoleLengthGrid()
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
        $this->view->result = array("PoleLength" => $response->result);
        $this->view->data   = array("PoleLength" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupPoleLengthGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function poleLengthGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupPoleLengthGrid();
    }


}

