<?php

class Util_UtilityController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_Admin';
	
	

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	
    }

    public function setDataFlagAction()
    {
    	$request   = $this->getRequest();
    	$fieldname = $request->getParam('fieldname', null);
    	$filter    = $request->getParam('filter', null);
    	$label     = $request->getParam('filterlabel', null);
    	$dataContext = Struct_Registry::getContext('dataContext');
    	if (is_null($fieldname) || is_null($filter))
    	{
    		return $this->jsonResult(Struct_ActionFeedback::success());
    	}
    	if (isset($dataContext[$fieldname]) && $filter == $dataContext[$fieldname]['value'])
    	{
    		unset($dataContext[$fieldname]);
    	}
    	else
    	{
    		$dataContext[$fieldname] = array(
    				'value' => $filter,
    				'label' => $label
    				);
    	}
    	Struct_Registry::setContext('dataContext', $dataContext);
    	$this->jsonResult(Struct_ActionFeedback::success());
    }
    
    public function clearDataFlagsAction()
    {
    	Struct_Registry::setContext('dataContext', array());
    	$this->jsonResult(Struct_ActionFeedback::success());
    }


}

