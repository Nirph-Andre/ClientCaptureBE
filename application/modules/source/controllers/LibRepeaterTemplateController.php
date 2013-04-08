<?php

/**
 * Table model for lib_repeater_template
 */
class Source_LibRepeaterTemplateController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibRepeaterTemplate';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibRepeaterTemplate';

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
    protected function setupLibRepeaterTemplateGrid()
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
        $this->view->result = array("LibRepeaterTemplate" => $response->result);
        $this->view->data   = array("LibRepeaterTemplate" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibRepeaterTemplateGrid();
        $this->dataContext = "listLibTemplate";
        $this->listDataReturnView("Object_LibTemplate");
    }

    /**
     * Retrieve data grid for display.
     */
    public function libRepeaterTemplateGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibRepeaterTemplateGrid();
    }


}

