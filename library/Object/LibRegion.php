<?php

/**
 * DataAccess Value Object for table lib_region
 */
class Object_LibRegion extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'LibRegion';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'lib_region';

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
        'lib_country_id' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 4294967295
                        )
                    ))
            ),
        'name' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '75')
                    ))
            ),
        'iso3166_2_code' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '2')
                    ))
            ),
        'fips10_4_code' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '2')
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

