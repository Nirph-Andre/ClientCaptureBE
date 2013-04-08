<?php


/**
 * Easy notification functionality for anything that needs it.
 * @author andre.fourie
 */
class Component_Notification
{
	
	/**
	 * Data for repeater functionality.
	 * @var array
	 */
	static protected $_data = null;
	/**
	 * Send template as newsletter to all subscribers on sendFromTemplate.
	 * @var boolean
	 */
	static protected $_sendAsNewsletter = false;
	/**
	 * Skip unsubscribe check on sendFromTemplate.
	 * @var boolean
	 */
	static protected $_skipSubscriptionCheck = false;
	
	
	/**
	 * Set repeater data for next notification sent.
	 * @param array $data
	 */
	static public function setRepeaterData(array $data)
	{
		self::$_data = $data;
	}
	
	/**
	 * Set next sendFromTemplate to send to all users subscribed to newsletters.
	 */
	static public function setSendAsNewsletter()
	{
		self::$_sendAsNewsletter = true;
	}
	
	/**
	 * Skip unsubscribe check on next sendFromTemplate.
	 */
	static public function skipSubscriptionCheck()
	{
		self::$_skipSubscriptionCheck = false;
	}
	
	/**
	 * Send email and/or sms notification.
	 * @param string $email
	 * @param string $mobile
	 * @param string $subject
	 * @param string $template
	 * @param array $params
	 * @param array $attachments
	 * @param boolean $disableSms
	 * @return void
	 */
	static public function sendFromTemplate(
			$email, $mobile, $subject, $templateName, array $params,
			array $attachments = array(), array $complexAttachments = array(),
			$disableSms = false
	)
	{
		#-> Unsubscribe check.
		if (!self::$_sendAsNewsletter && !self::$_skipSubscriptionCheck)
		{
			$oProfile = new Object_Profile();
			$profile = $email
				? $oProfile->view(null, array('email' => $email))->data
				: $oProfile->view(null, array('mobile' => $mobile))->data;
			if (!empty($profile) && !$profile['subscribe_reminders'])
			{
				return;
			}
		}
		self::$_skipSubscriptionCheck = false;
		
		#-> Retrieve template.
		$oTemplate = new Object_LibTemplate();
		$oRepeater = new Object_LibRepeaterTemplate();
		$response = $oTemplate->view(null, array('name' => $templateName));
		if (!$response->ok())
		{
			return;
		}
		$template = $response->data;
		
		#-> Compile the template for use.
		$subject = ($subject)
			? $subject
			: $template['subject'];
		$tagList = explode(',', $template['tags']);
		$search  = array('{APP_HOST}');
		$replace = array(APP_HOST);
		
		#-> Catering for data-grid?
		$repeater = $oRepeater->view(null, array('lib_template_id' => $template['id']))->data;
		if (!empty($repeater))
		{
			if (!is_array(self::$_data))
			{
				Struct_Debug::errorLog(__CLASS__, 'Data required but not provided for template: ' . $templateName);
				return;
			}
			$repeatContent = '';
			$groupField = ($repeater['group_field'])
				? $repeater['group_field']
				: false;
			$group = '';
			$i = 1;
			foreach (self::$_data as $row)
			{
				$repSearch  = array();
				foreach ($row as $field => $value)
				{
					if (is_array($value))
					{
						foreach ($value as $subField => $subValue)
						{
							$repSearch["$field.$subField"] = "[$field.$subField]";
							$repReplace["$field.$subField"] = $subValue;
						}
					}
					else
					{
						$repSearch[$field] = "[$field]";
						$repReplace[$field] = $value;
					}
				}
				if ($groupField && $repReplace[$groupField] != $group)
				{
					$group = $repReplace[$groupField];
					$repeatContent .= str_replace($repSearch, $repReplace, $repeater['group_repeater']) . "\n";
				}
				$repeatContent .= ($i % 2)
					? str_replace($repSearch, $repReplace, $repeater['row_repeater_odd']) . "\n"
					: str_replace($repSearch, $repReplace, $repeater['row_repeater_even']) . "\n";
				$i++;
			}
			$params['repeater'] = $repeatContent;
		}
		
		#-> Build up the template(s)
		foreach ($tagList as $key)
		{
			$key = trim($key);
			if (!isset($params[$key]))
			{
				Struct_Debug::errorLog(__CLASS__, "All template tags not supplied for sending ($templateName): " . $key);
				Struct_Debug::errorLog('tags', $tagList);
				Struct_Debug::errorLog('params', $params);
				return;
			}
			$search[]  = "[$key]";
			$replace[] = $params[$key];
		}
		$emailTemplate = !empty($template['email_template'])
			? str_replace($search, $replace, $template['email_template'])
			: false;
		$smsTemplate = !empty($template['sms_template'])
			? str_replace($search, $replace, $template['sms_template'])
			: false;
		if (!self::$_sendAsNewsletter)
		{
			self::send($email, $mobile, $subject, $emailTemplate, $smsTemplate,
					$attachments, $complexAttachments, $disableSms);
		}
		else
		{
			$oProfile = new Object_Profile();
			$profiles = $oProfile->grid(array('profile.status' => 'Active', 'profile.subscribe_newsletter' => 1))->data;
			foreach ($profiles as $profile)
			{
				self::send($profile['email'], false, $subject, $emailTemplate, $smsTemplate,
					$attachments, $complexAttachments, $disableSms);
			}
		}
		self::$_data = null;
		self::$_sendAsNewsletter = false;
	}
	
	/**
	 * Send newsletter to all who are subscribed, or just to admin for a test.
	 * @param integer $newsletterId
	 * @param boolean $test
	 * @return void
	 */
	static public function sendNewsletter($newsletterId, $test = false)
	{
		#-> Retrieve data handlers.
		$oTemplate   = new Object_LibTemplate();
		$oNlTemplate = new Object_LibNewsletterTemplate();
		$oNewletter  = new Object_LibNewsletter();
		$oAttachment = new Object_LibAttachment();
		$oProfile    = new Object_Profile();
		
		#-> Collect some data.
		$template = $oTemplate->view(null, array('name' => 'Newsletter - Basic'))->data;
		$newsletter = $oNewletter->view($newsletterId)->data;
		$pics = $oNlTemplate->view(1, array(), true)->data;
		$complexAttachments = array();
		/* $complexAttachments['header-image'] = array(
				'data'     => $pics['header_lib_photo']['photo'],
				'type'     => $pics['header_lib_photo']['mime_type'],
				'filename' => 'bid4cars-email-header.png'
				);
		$complexAttachments['footer-image'] = array(
				'data'     => $pics['footer_lib_photo']['photo'],
				'type'     => $pics['footer_lib_photo']['mime_type'],
				'filename' => 'bid4cars-email-footer.png'
				); */
		$search  = array('[HeaderImageSource]', '[FooterImageSource]', '[Body]');
		//$replace = array('cid:header-image', 'cid:footer-image', $newsletter['content']);
		$replace = array(
				'http://' . APP_HOST . '/images/EmailHeader.png',
				'http://' . APP_HOST . '/images/EmailFooter.png',
				$newsletter['content']
				);
		
		$emailTemplate = str_replace($search, $replace, $template['email_template']);
		$attachments = array();
		if ($newsletter['lib_attachment_id'])
		{
			$attachment = $oAttachment->view($newsletter['lib_attachment_id'])->data;
			$attachments[$attachment['filename']] = $attachment['document'];
		}
		
		$search = array('[first_name]', '[family_name]', '[mobile]');
		$profiles = $oProfile->grid(array('profile.status' => 'Active', 'profile.subscribe_newsletter' => 1))->data;
		if ($test)
		{
			$auth = Struct_Registry::getAuthData();
			$replace = array(
					'John', 'Doe', '0820820820'
			);
			self::send($auth['email'], false,
					str_replace($search, $replace, $newsletter['subject']),
					str_replace($search, $replace, $emailTemplate), '',
					$attachments, $complexAttachments, true);
			return;
		}
		else
		{
			foreach ($profiles as $profile)
			{
				$replace = array(
						$profile['first_name'], $profile['family_name'], $profile['mobile']
						);
				self::send($profile['email'], false,
						str_replace($search, $replace, $newsletter['subject']), 
						str_replace($search, $replace, $emailTemplate), '',
						$attachments, $complexAttachments, true);
			}
		}
		self::$_data = null;
	}
	
	
	
	/**
	 * Send email and/or sms notification.
	 * @param string $email
	 * @param string $mobile
	 * @param string $subject
	 * @param string $emailTemplate
	 * @param string $smsTemplate
	 * @param array $attachments
	 * @param boolean $disableSms
	 * @return void
	 */
	static private function send(
			$email, $mobile, $subject, $emailTemplate, $smsTemplate,
			array $attachments = array(), array $complexAttachments = array(),
			$disableSms = false
			)
	{
		#-> Send the email off, into the big wide world, with a message of hope, or something.
		try
		{
			if ($emailTemplate && $email)
			{
				IS_DEV_ENV
					&& $email = DEV_TEST_EMAIL_ADDRESS;
				$emailTemplate = str_replace('{APP_HOST}', APP_HOST, $emailTemplate);
				$mailer = new Struct_Comms_Email();
				$mailer->send(array(
						'From'       		=> Component_Config::getEmailSourceAddress(),
						'To'         		=> $email,
						'Subject'    		=> $subject,
						'Html'       		=> $emailTemplate,
						'Attachment'		=> $attachments,
						'ComplexAttachment' => $complexAttachments
						));
			}
		}
		catch (Exception $e)
		{
			Struct_Debug::errorLog('Email Sending', "$e");
			Struct_Debug::errorLog('Email Sending', '-----------------------------------------------------------------------------------');
			Struct_Debug::errorLog('Email Sending', $emailTemplate);
			Struct_Debug::errorLog('Email Sending', '-----------------------------------------------------------------------------------');
		}
		
		#-> Send the sms hurtling through cyberspace at insane speeds.
		$apiMsgId = '';
		try
		{
			if (!$disableSms && $smsTemplate && $mobile)
			{
				if (!IS_DEV_ENV)
				{
					$sms = new Struct_Comms_Sms();
					$apiMsgId = $sms->send(array(
							'To'      => $mobile,
							'From'    => Component_Config::getSmsSourceAddress(),
							'Subject' => 'Bid4Cars: ',
							'Body'    => $smsTemplate
							));
					$apiMsgId = (false == $apiMsgId)
						? ''
						: $apiMsgId;
				}
			}
		}
		catch (Exception $e)
		{
			Struct_Debug::errorLog(__CLASS__, "$e");
		}
		
		#-> Log notification entry.
		$oLog = new Object_LibNotificationLog();
		$oLog->save(null, array(), array(
				'email_to'      => $email,
				'email_subject' => $subject,
				'email_body'    => $emailTemplate,
				'sms_to'        => $mobile,
				'sms_body'      => $smsTemplate,
				'api_msg_id'    => $apiMsgId
				));
		return;
	}
	
}

