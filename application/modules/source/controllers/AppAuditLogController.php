<?php

/**
 * Table model for app_audit_log
 */
class Source_AppAuditLogController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_AppAuditLog';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'AppAuditLog';

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
    protected function setupAppAuditLogGrid()
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
        $this->view->result = array("AppAuditLog" => $response->result);
        $this->view->data   = array("AppAuditLog" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupAppAuditLogGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function appAuditLogGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupAppAuditLogGrid();
    }


}

