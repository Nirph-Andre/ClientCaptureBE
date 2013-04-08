<?php

/**
 * Table model for lib_notification_log
 */
class Source_LibNotificationLogController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibNotificationLog';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibNotificationLog';

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
    protected function setupLibNotificationLogGrid()
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
        $this->view->result = array("LibNotificationLog" => $response->result);
        $this->view->data   = array("LibNotificationLog" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibNotificationLogGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libNotificationLogGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibNotificationLogGrid();
    }


}

