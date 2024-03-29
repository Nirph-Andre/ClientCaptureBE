<?php

/**
 * Table model for item
 */
class Source_ItemController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Item';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Item';

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
    protected function setupItemGrid()
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
        $this->view->result = array("Item" => $response->result);
        $this->view->data   = array("Item" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupItemGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function itemGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupItemGrid();
    }


}

