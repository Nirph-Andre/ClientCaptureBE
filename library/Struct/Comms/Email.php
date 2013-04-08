<?php


/**
 * Zend email wrapper class
 * @author Tjaart Viljoen
 * @copyright 2012 Nirph Online
 */
class Struct_Comms_Email 
{
	/**
	 * Zend Mail object
	 * @var Zend_Mail
	 */
	protected $_service;
	
	public function __construct()
	{
		$this->_service = new Zend_Mail();
	}
	
	public function setContext(array $aContext = array())
	{
		$service = new Zend_Mail();
		foreach ($aContext as $sParam => $mValue)
		{
			switch ($sParam)
			{
				case 'From':
					$this->_service->setFrom($mValue);
					break;
				case 'To':
					$this->_service->addTo($mValue);
					break;
				case 'Cc':
					$this->_service->addCc($mValue);
					break;
				case 'Bcc':
					$this->_service->addBcc($mValue);
					break;
				case 'Subject':
					$this->_service->setSubject($mValue);
					break;
				case 'Body':
					$this->_service->setBodyText($mValue);
					break;
				case 'Html':
					$this->_service->setBodyHtml($mValue);
					break;
				case 'ComplexAttachment':
					foreach ($mValue as $cid => $meta)
					{
						if(isset($meta['data']) && !empty($meta['data']))
						{
							$file = new Zend_Mime_Part($meta['data']);
							$file->disposition = Zend_Mime::DISPOSITION_INLINE;
							$file->encoding    = Zend_Mime::ENCODING_BASE64;
							$file->description = 'Attached file';
							isset($meta['type'])
								&& $file->type = $meta['type'];
							isset($meta['filename'])
								&& $file->filename = $meta['filename'];
							$file->id = $cid;
							$this->_service->addAttachment($file);
						}
					}
					break;
				case 'Attachment':
					foreach ($mValue as $fileName => $fileData)
					{
						if(!empty($fileData))
						{
							$file = new Zend_Mime_Part($fileData);
							$file->disposition = Zend_Mime::DISPOSITION_INLINE;
							$file->encoding    = Zend_Mime::ENCODING_BASE64;
							$file->description = 'Attached file';
							$file->filename    = $fileName;
							$this->_service->addAttachment($file);
						}
					}
					break;
			}
		}
	}
	
	public function send(array $aContext = array())
	{
		empty($aContext)
			|| $this->setContext($aContext);
		IS_DEV_ENV && $this->_service->addTo('tjaart.viljoen@nirph.com');
		return $this->_service->send();
	}
	
	
}

