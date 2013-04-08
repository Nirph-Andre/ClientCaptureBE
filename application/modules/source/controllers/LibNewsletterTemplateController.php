<?php

/**
 * Table model for lib_newsletter_template
 */
class Source_LibNewsletterTemplateController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibNewsletterTemplate';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibNewsletterTemplate';

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
    protected function setupLibNewsletterTemplateGrid()
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
        $this->view->result = array("LibNewsletterTemplate" => $response->result);
        $this->view->data   = array("LibNewsletterTemplate" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibNewsletterTemplateGrid();
        $this->dataContext = "listHeaderLibPhoto";
        $this->listDataReturnView("Object_LibPhoto");
        $this->dataContext = "listFooterLibPhoto";
        $this->listDataReturnView("Object_LibPhoto");
    }

    /**
     * Retrieve data grid for display.
     */
    public function libNewsletterTemplateGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibNewsletterTemplateGrid();
    }


}

