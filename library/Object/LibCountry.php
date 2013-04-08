<?php

/**
 * DataAccess Value Object for table lib_country
 */
class Object_LibCountry extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'LibCountry';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'lib_country';

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
        'name' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '50')
                    ))
            ),
        'country_code_iso' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '2')
                    ))
            ),
        'country_code_fips' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '2')
                    ))
            ),
        'dialing_code' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 255
                        )
                    ))
            ),
        'archived' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'InArray',
                    'params' => array(
                        '0',
                        '1'
                        )
                    ))
            )
        );


}

