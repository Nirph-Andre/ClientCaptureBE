<?php

/**
 * Table model for lib_document
 */
class Source_LibDocumentController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibDocument';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibDocument';

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
    protected function setupLibDocumentGrid()
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
        $this->view->result = array("LibDocument" => $response->result);
        $this->view->data   = array("LibDocument" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibDocumentGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libDocumentGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibDocumentGrid();
    }


}

