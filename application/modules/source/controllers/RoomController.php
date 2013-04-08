<?php

/**
 * Table model for room
 */
class Source_RoomController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Room';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Room';

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
    protected function setupRoomGrid()
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
        $this->view->result = array("Room" => $response->result);
        $this->view->data   = array("Room" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupRoomGrid();
        $this->dataContext = "listFloor";
        $this->listDataReturnView("Object_Floor");
    }

    /**
     * Retrieve data grid for display.
     */
    public function roomGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupRoomGrid();
    }


}

