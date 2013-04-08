<?php

/**
 * DataAccess Value Object for table lib_authentication_log
 */
class Object_LibAuthenticationLog extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'LibAuthenticationLog';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'lib_authentication_log';

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
        'ip_address' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '50')
                    ))
            ),
        'profile_id' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 4294967295
                        )
                    ))
            )
        );


}

