<?php

/**
 * Table model for content
 */
class Source_ContentController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Content';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Content';

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
    protected function setupContentGrid()
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
        $this->view->result = array("Content" => $response->result);
        $this->view->data   = array("Content" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupContentGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function contentGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupContentGrid();
    }


}

