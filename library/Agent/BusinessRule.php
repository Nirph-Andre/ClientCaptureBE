<?php

/**
 * Handler agent for general default business rules.
 * @author andre.fourie
 *
 */
class Agent_BusinessRule
{

	/**
	 * User message for feedback on disallow or error.
	 * @var string
	 */
	protected $_message = null;
	/**
	 * Bidding increments.
	 * @var string
	 */
	protected $_increments = false;

	
	/* ---------------------------------------------------------------------- *\
	 * 	Event Interface
	\* ---------------------------------------------------------------------- */

	/**
	 * Retrieve last disallow/error message stored.
	 * @return string
	 */
	public function getLastMessage()
	{
		return $this->_message;
	}

	/**
	 * Modify new profile data to set hashed password and salt.
	 * @events Profile.Creating, Profile.Updating
	 * @param  string $domainEvent
	 * @param  Struct_Abstract_DataAccess $originator
	 * @return void
	 */
	public function setPasswordAndSalt($domainEvent, Struct_Abstract_DataAccess $originator)
	{
		#-> Collect data.
		$request = $originator->getRequest();
		$changeData = $originator->getDataChanges();
		$auth = Struct_Registry::getAuthData();
		$oProfile = new Object_Profile();
		$filter = array();
		$data = $oProfile->view($auth['id'], $filter)->data;
		
		#-> Mobile number handling.
		if (isset($changeData['mobile']))
		{
			if ('+' == substr($changeData['mobile'], 0, 1))
			{
				$changeData['mobile'] = substr($changeData['mobile'], 1);
			}
			if ('0' == substr($changeData['mobile'], 0, 1))
			{
				$changeData['mobile'] = '27' . substr($changeData['mobile'], 1);
			}
			elseif ('270' == substr($changeData['mobile'], 0, 3))
			{
				$changeData['mobile'] = '27' . substr($changeData['mobile'], 3);
			}
			elseif ('27' == substr($changeData['mobile'], 0, 2))
			{
				$changeData['mobile'] = $changeData['mobile'];
			}
			else
			{
				$changeData['mobile'] = '27' . $changeData['mobile'];
			}
			$originator->overrideDataChange('mobile', $changeData['mobile']);
		}
		
		#-> Only an administrator may create admin accounts.
		if (!Struct_Registry::isAuthenticated()
				&& Struct_Registry::isUserType('Administrator'))
		{
			$originator->overrideDataChange('user_type', 'User');
		}
		
		#-> Pin change by logged in user, safety check.
		if (isset($request->data['current_pin']) && sha1(sha1($request->data['current_pin']).'Salt'.$data['password_salt']) != $data['password'])
		{
			$this->_message = 'Incorrect Pin provided.';
			return false;
		}
		
		#-> Filter.
		if ('Profile.Updating' == $domainEvent
			&& (!isset($request->data['generate_pin']) || 'false' == $request->data['generate_pin']))
		{
			return;
		}
		
		#-> Profile creation.
		if ('Profile.Creating' == $domainEvent)
		{
			if (!Struct_Registry::isAuthenticated())
			{
				$originator->overrideDataChange('user_type', 'User');
			}
		}
		
		#-> New pin handling.
		$pass = isset($request->data['pin']) && !empty($request->data['pin']) ? $request->data['pin'] : mt_rand(1000, 9999);
		$salt = sha1(mt_rand(1000000, 9999999));
		$originator->overrideDataChange('password', sha1(sha1($pass) . 'Salt' . $salt));
		$originator->overrideDataChange('password_salt', $salt);
		if (!isset($request->data['pin']) || empty($request->data['pin']))
		{
			$templateParams = array(
				'first_name' => $request->data['first_name'],
				'family_name' => $request->data['family_name'],
				'email' => $request->data['email'],
				'pin' => $pass,
				'user' => $auth['first_name'] . ' ' . $auth['family_name']
			);
			Component_Notification::sendFromTemplate(
				$request->data['email'], false, null, 'Profile Registered', $templateParams
			);
		}
	}

	/**
	 * Notify admin of a new contact request.
	 * @events ContactRequest.Created
	 * @param  string $domainEvent ContactRequest.Created
	 * @param  Struct_Abstract_DataAccess $originator
	 * @return void
	 */
	public function sendContactRequestNotification($domainEvent, Struct_Abstract_DataAccess $originator)
	{
		#-> Collect params we're going to need for the template.
		$templateParams = $originator->getData();

		#-> Send it off.
		Component_Notification::skipSubscriptionCheck();
		Component_Notification::sendFromTemplate(
			Component_Config::getAdminEmail(), false, null, 'Contact Request', $templateParams
		);
	}

	/**
	 * Set newsletter status.
	 * @event  LibNewsletter.Creating LibNewsletter.Updating
	 * @param  string $domainEvent
	 * @param  Struct_Abstract_DataAccess $originator
	 * @return void
	 */
	public function prepNewsletter($domainEvent, Struct_Abstract_DataAccess $originator)
	{
		$requestData = $originator->getRequest()->data;
		if (isset($requestData['status'])
			&& ('Send' == $requestData['status']))
		{
			$originator->overrideDataChange('status', 'Sent');
		}
		if (isset($requestData['status'])
			&& ('Test' == $requestData['status'])
			&& 'LibNewsletter.Creating' == $domainEvent)
		{
			$originator->overrideDataChange('status', 'Draft');
		}
	}

	/**
	 * Send newsletter out to either administrator as a test or to all subscribers.
	 * @event  LibNewsletter.Created LibNewsletter.Updated
	 * @param  string $domainEvent
	 * @param  Struct_Abstract_DataAccess $originator
	 * @return void
	 */
	public function sendNewsletter($domainEvent, Struct_Abstract_DataAccess $originator)
	{
		$changeData = $originator->getData();
		$requestData = $originator->getRequest()->data;
		if (isset($requestData['status'])
			&& ('Send' == $requestData['status'] || 'Test' == $requestData['status']))
		{
			$bTest = ('Test' == $requestData['status']) ? true : false;
			Component_Notification::sendNewsletter($changeData['id'], $bTest);
		}
	}

	/**
	 * 
	 * @event  
	 * @param  string $domainEvent
	 * @param  Struct_Abstract_DataAccess $originator
	 * @param  array $params
	 * @return void
	 */
	/* public function methodTemplate($domainEvent, Struct_Abstract_DataAccess $originator, array $params)
	  {

	  } */



	/* ---------------------------------------------------------------------- *\
	 * 	Internal Utilities
	\* ---------------------------------------------------------------------- */

	/**
	 * Generate a [prepend] . 12 char string reference number.
	 * @param string $prepend
	 * @return string
	 */
	protected function _genReferenceNumber($prepend)
	{
		$map = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
		$date = date('ymdHis');
		$reference_number = '';
		for ($i = 0; $i < 12; $i++)
		{
			$number = substr($date, $i, 1);
			$reference_number .= ($i < 6) ? $number : $map[$number];
		}
		return $prepend . $reference_number;
	}

}

