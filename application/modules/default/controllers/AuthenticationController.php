<?php

/**
 * Table model for authentication
 */
class AuthenticationController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Authentication';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Authentication';

    /**
     * Action controller initializer.
     */
    public function init()
    {
    }

    /**
     * Setup data grid on default value object.
     */
    protected function setupAuthenticationGrid()
    {
        $this->view->authenticationGrid = new Struct_Grid(
        	$this->getObject(),
        	$this->getRequest(),
        	"default/authentication",
        	"authentication-grid"
        	);
        $this->view->authenticationGrid
        	->useField('authentication_display_name', 'Display Name', 'authentication.display_name')
        	->useField('authentication_email', 'Email', 'authentication.email')
        	->useField('authentication_status', 'Status', 'authentication.status');
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupAuthenticationGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function authenticationGridAction()
    {
        $this->setupAuthenticationGrid();
        $this->view->authenticationGrid->publishGrid();
        exit();
    }

    /**
     * Sava data entry to database.
     */
    public function authenticationSaveAction()
    {
        $this->saveDataReturnJson();
    }

    /**
     * Remove data entry from database.
     */
    public function authenticationDeleteAction()
    {
        $this->removeDataReturnJson();
    }


}

