<?php

/**
 * Table model for mobile_content
 */
class Source_MobileContentController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_MobileContent';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'MobileContent';

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
    protected function setupMobileContentGrid()
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
        $this->view->result = array("MobileContent" => $response->result);
        $this->view->data   = array("MobileContent" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupMobileContentGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function mobileContentGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupMobileContentGrid();
    }


}

