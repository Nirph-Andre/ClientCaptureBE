<?php

/**
 * Table model for condition
 */
class Source_ConditionController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Condition';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Condition';

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
    protected function setupConditionGrid()
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
        $this->view->result = array("Condition" => $response->result);
        $this->view->data   = array("Condition" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupConditionGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function conditionGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupConditionGrid();
    }


}

