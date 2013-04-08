<?php

/**
 * Table model for asset_type
 */
class Source_AssetTypeController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_AssetType';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'AssetType';

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
    protected function setupAssetTypeGrid()
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
        $this->view->result = array("AssetType" => $response->result);
        $this->view->data   = array("AssetType" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupAssetTypeGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function assetTypeGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupAssetTypeGrid();
    }


}

