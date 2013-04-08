<?php

/**
 * Table model for asset_sub_description
 */
class Source_AssetSubDescriptionController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_AssetSubDescription';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'AssetSubDescription';

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
    protected function setupAssetSubDescriptionGrid()
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
        $this->view->result = array("AssetSubDescription" => $response->result);
        $this->view->data   = array("AssetSubDescription" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupAssetSubDescriptionGrid();
        $this->dataContext = "listAssetDescription";
        $this->listDataReturnView("Object_AssetDescription");
    }

    /**
     * Retrieve data grid for display.
     */
    public function assetSubDescriptionGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupAssetSubDescriptionGrid();
    }


}

