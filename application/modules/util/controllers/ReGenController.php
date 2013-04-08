<?php

class Util_ReGenController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$forceRefresh = $this->getRequest()->getParam('force', false);
    	$rebuild = $this->getRequest()->getParam('rebuild', false);

    	ReGen_Registry::setContext('projectLocation', 'c:\Apache2\htdocs\ClientCaptureBE');
    	ReGen_Registry::setContext('applicationName', 'application');
    	ReGen_Registry::setContext('entityName', 'source');
    	ReGen_Registry::setContext('dataContext', array());
    	ReGen_Registry::setContext('dataContextWhitelist', array());
    	ReGen_Registry::setContext('dataDependancies', array(
    			'lib_city_id' => 'lib_region_id',
    			'lib_region_id' => 'lib_country_id'
    			));

    	$aliasMap = array(
    			'lib' => 'Library'
    	);

    	$amfMap = array(
    		'SynchGen' => array(
	    			'profile' => 'profile',
	    			'lib_address' => 'lib_address',
	    			'lib_photo' => 'lib_photo'
    				),
    		'Lookup' => array(
	    			'lib_city' => 'lib_city',
	    			'lib_region' => 'lib_region',
	    			'lib_country' => 'lib_country'
    				)
    	);

    	$classModelGen = new ReGen_Generate_Model_Data();
        $classModelGen->setupDataModel(
        		'client_capture', 'root', '', $aliasMap, $amfMap, $forceRefresh, $rebuild
        		);
    }


}

