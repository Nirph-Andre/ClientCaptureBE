<?php

/**
 * Table model for lib_template
 */
class Source_LibTemplateController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibTemplate';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibTemplate';

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
    protected function setupLibTemplateGrid()
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
        $this->view->result = array("LibTemplate" => $response->result);
        $this->view->data   = array("LibTemplate" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibTemplateGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libTemplateGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibTemplateGrid();
    }


}

