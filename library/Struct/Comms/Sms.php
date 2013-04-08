<?php


/**
 * This class facilitates basic sms sending.
 *
 * @author Andre Fourie
 */
class Struct_Comms_Sms
{
    #-> Error Messages.
    const ERROR_ADDRESS     = 'Struct_Comms_Sms, invalid Address supplied.';

		#-> Status codes.
		static private $statusCode      = array(
				'001' => 'Message unknown',
				'002' => 'Message queued',
				'003' => 'Delivered to gateway',
				'004' => 'Received by recipient',
				'005' => 'Error with message',
				'006' => 'User cancelled message delivery',
				'007' => 'Error delivering message',
				'008' => 'OK',
				'009' => 'Routing error',
				'010' => 'Message expired',
				'011' => 'Message queued for later delivery',
				'012' => 'Out of credit',
				'014' => 'Maximum MT limit exceeded'
				);

    #-> Params
    private $sBody          = '';
    private $sTo            = '';
    private $sCc            = '';
    private $sBcc           = '';
    private $sFrom          = '';
    private $sSubject       = '';
    private $sSmscId        = '';



    /* ---------------------------------------------------------------------------------------- */
    #-> Summoning.

    public function __construct() {}



    /* ---------------------------------------------------------------------------------------- */
    #-> Private Functions
    
    private function checkNumericNotZero($val)
    {
    	return is_numeric($val) && 0 != $val;
    }


    /* ---------------------------------------------------------------------------------------- */
    #-> Public Functions - Util.
    
    /**
     * Swap sms status code for short status text.
     * @param  string $code
     * @return string
     */
    static public function getStatusText($code)
    {
    	$code = str_pad($code, 3, '0', STR_PAD_LEFT);
    	return isset(self::$statusCode[$code])
    		? self::$statusCode[$code]
    		: 'Unknown message status';
    }


    /* ---------------------------------------------------------------------------------------- */
    #-> Public Functions - Sending.

    /**
     * Set from.
     *
     * @param string $sFrom
     * @return boolean
     */
    public function setFrom($sFrom)
    {
        if ($sFrom)
        {
            $this->sFrom = $sFrom;
            return true;
        }
        return false;
    }

    /**
     * Set to.
     *
     * @param  multi   $mTo
     * @return boolean
     */
    public function setTo($mTo)
    {
        #-> Add to list
        if (!is_array($mTo) && is_numeric($mTo))
        {
            $this->sTo .= empty($this->sTo)
                ? $mTo
                : ',' . $mTo;
        }
        if (is_array($mTo) && !empty($mTo))
        {
            foreach ($mTo as $mAddress)
            {
                if (is_numeric($mAddress))
                {
                    $this->sTo .= empty($this->sTo)
                        ? $mAddress
                        : ',' . $mAddress;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Set to without concat.
     *
     * @param  String   $sTo
     * @return boolean
     */
    public function setToNoConcat($sTo)
    {
        #-> Add to list
        if (!empty($sTo))
        {
            $this->sTo = $sTo;
            return true;
        }
        return false;
    }

    /**
     * Set cc.
     *
     * @param  multi   $mCc
     * @return boolean
     */
    public function setCc($mCc)
    {
        return $this->SetTo($mCc);
    }

    /**
     * Set bcc.
     *
     * @param  multi   $mBcc
     * @return boolean
     */
    public function setBcc($mBcc)
    {
        return $this->SetTo($mBcc);
    }

    /**
     * Set subject.
     *
     * @param  string  $sSubject
     * @return boolean
     */
    public function setSubject($sSubject = 0)
    {
        if ($sSubject)
        {
            $this->sSubject = $sSubject;
            return true;
        }
        return false;
    }

    /**
     * Set body.
     *
     * @param  string  $sBody
     * @return boolean
     */
    public function setBody($sBody)
    {
        if ($sBody)
        {
            $this->sBody = $sBody;
            return true;
        }
        return false;
    }

    /**
     * Set smsc id.
     *
     * @param  string  $sSmscId
     * @return boolean
     */
    public function setSmscId($sSmscId)
    {
        if ($sSmscId)
        {
            $this->sSmscId = $sSmscId;
            return true;
        }
        return false;
    }

    /**
     * Set context parameters from array.
     *
     * @param  array   $aContext
     * @return boolean
     */
    public function setContext(array $aContext = array())
    {
        #-> Check context.
        foreach ($aContext as $sParam => $mValue)
        {
            switch ($sParam)
            {
                case 'To':
                case 'Cc':
                case 'Bcc':
                    $this->setTo($mValue);
                    break;
                case 'From':
                    $this->setFrom($mValue);
                    break;
                case 'Subject':
                case 'Body':
                    $this->sBody .= empty($this->sBody)
                        ? $mValue
                        : "\n" . $mValue;
                    break;
            }
        }

    }

    /**
     * Send the sms.
     *
     * @param  array   $aContext
     * @return boolean
     */
    public function send(array $aContext = array())
    {
        #-> Check context.
        empty($aContext)
            || $this->setContext($aContext);

        #-> Send using api.
        $sUrl = "https://api.clickatell.com/http/sendmsg.php?"
              . "user=".SMS_USERNAME."&"
              . "api_id=".SMS_API_ID."&"
              . "password=".SMS_PASSWORD."&concat=4&"
              . "callback=2&"
              . "to=$this->sTo&text=".substr(urlencode($this->sBody),0,620);

        if (!empty($this->sSmscId))
        {
            $sUrl .= "&smsc=" . $this->sSmscId;
        }

       /*  if (!empty($this->sFrom))
        {
            $sUrl .= "&from=" . $this->sFrom;
        } */

        try
        {
            $aRet = file($sUrl);
            return empty($aRet) || substr($aRet[0], 0, 2) == 'ID'
                ? substr($aRet[0], 4)
                : false;
        }
        catch (Exception $oException)
        {
            error_log($oException->getMessage());
            error_log($oException->getTraceAsString());
        }
        return false;
    }
    
	
}


