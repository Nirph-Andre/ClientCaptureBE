<?php

/**
 * Table model for config
 */
class Central_IndexController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Profile';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Profile';

    /**
     * Action controller initializer.
     */
    public function init()
    {
        if (!Struct_Registry::isAuthenticated())
        {
        	header("Location: /");
        	exit();
        }
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        // nothing to do yet
    }


}

