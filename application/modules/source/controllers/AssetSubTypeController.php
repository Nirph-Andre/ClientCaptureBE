<?php

/**
 * Table model for asset_sub_type
 */
class Source_AssetSubTypeController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_AssetSubType';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'AssetSubType';

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
    protected function setupAssetSubTypeGrid()
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
        $this->view->result = array("AssetSubType" => $response->result);
        $this->view->data   = array("AssetSubType" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupAssetSubTypeGrid();
        $this->dataContext = "listAssetType";
        $this->listDataReturnView("Object_AssetType");
    }

    /**
     * Retrieve data grid for display.
     */
    public function assetSubTypeGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupAssetSubTypeGrid();
    }


}

