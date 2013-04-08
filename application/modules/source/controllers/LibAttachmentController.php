<?php

/**
 * Table model for lib_attachment
 */
class Source_LibAttachmentController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibAttachment';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibAttachment';

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
    protected function setupLibAttachmentGrid()
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
        $this->view->result = array("LibAttachment" => $response->result);
        $this->view->data   = array("LibAttachment" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibAttachmentGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libAttachmentGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibAttachmentGrid();
    }


}

