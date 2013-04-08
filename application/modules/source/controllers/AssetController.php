<?php

/**
 * Table model for asset
 */
class Source_AssetController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Asset';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Asset';

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
    protected function setupAssetGrid()
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
        $this->view->result = array("Asset" => $response->result);
        $this->view->data   = array("Asset" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupAssetGrid();
        $this->dataContext = "listItem";
        $this->listDataReturnView("Object_Item");
    }

    /**
     * Retrieve data grid for display.
     */
    public function assetGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupAssetGrid();
    }


}

