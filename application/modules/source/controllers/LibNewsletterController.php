<?php

/**
 * Table model for lib_newsletter
 */
class Source_LibNewsletterController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibNewsletter';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibNewsletter';

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
    protected function setupLibNewsletterGrid()
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
        $this->view->result = array("LibNewsletter" => $response->result);
        $this->view->data   = array("LibNewsletter" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibNewsletterGrid();
        $this->dataContext = "listLibNewsletterTemplate";
        $this->listDataReturnView("Object_LibNewsletterTemplate");
        $this->dataContext = "listLibAttachment";
        $this->listDataReturnView("Object_LibAttachment");
    }

    /**
     * Retrieve data grid for display.
     */
    public function libNewsletterGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibNewsletterGrid();
    }


}

