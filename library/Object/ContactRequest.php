<?php

/**
 * DataAccess Value Object for table contact_request
 */
class Object_ContactRequest extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'ContactRequest';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'contact_request';

    /**
     * Unique identification field(s).
     */
    protected $_uniqueIdentifier = array();

    /**
     * Validation meta-data.
     */
    protected $_validation = array(
        'id' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Digits',
                    'params' => array()
                    ))
            ),
        'person_name' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '100')
                    ))
            ),
        'trading_name' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '100')
                    ))
            ),
        'email' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'EmailAddress',
                    'params' => array()
                    ))
            ),
        'mobile' => array(
            'required' => false,
            'validators' => array(
                array(
                    'type' => 'StringLength',
                    'params' => array('max' => 16)
                    ),
                array(
                    'type' => 'Digits',
                    'params' => array()
                    )
                )
            ),
        'telephone' => array(
            'required' => false,
            'validators' => array(
                array(
                    'type' => 'StringLength',
                    'params' => array('max' => 16)
                    ),
                array(
                    'type' => 'Digits',
                    'params' => array()
                    )
                )
            ),
        'subject' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '100')
                    ))
            ),
        'message' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => 65535)
                    ))
            )
        );


}

