<?php

/**
 * DataAccess Value Object for table lib_address
 */
class Object_LibAddress extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'LibAddress';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'lib_address';

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
        'lib_city_id' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 4294967295
                        )
                    ))
            ),
        'address' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '250')
                    ))
            ),
        'postal_code' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '10')
                    ))
            ),
        'latitude' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 4294967295
                        )
                    ))
            ),
        'longitude' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 4294967295
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

