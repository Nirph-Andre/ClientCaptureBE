<?php

/**
 * Table model for lib_photo
 */
class Source_LibPhotoController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibPhoto';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibPhoto';

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
    protected function setupLibPhotoGrid()
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
        $this->view->result = array("LibPhoto" => $response->result);
        $this->view->data   = array("LibPhoto" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibPhotoGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libPhotoGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibPhotoGrid();
    }


}

