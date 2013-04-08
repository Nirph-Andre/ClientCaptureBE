<?php

/**
 * Table model for asset_description
 */
class Source_AssetDescriptionController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_AssetDescription';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'AssetDescription';

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
    protected function setupAssetDescriptionGrid()
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
        $this->view->result = array("AssetDescription" => $response->result);
        $this->view->data   = array("AssetDescription" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupAssetDescriptionGrid();
        $this->dataContext = "listAssetSubType";
        $this->listDataReturnView("Object_AssetSubType");
    }

    /**
     * Retrieve data grid for display.
     */
    public function assetDescriptionGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupAssetDescriptionGrid();
    }


}

