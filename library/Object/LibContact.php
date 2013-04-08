<?php

/**
 * DataAccess Value Object for table lib_contact
 */
class Object_LibContact extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'LibContact';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'lib_contact';

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
        'first_name' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '50')
                    ))
            ),
        'family_name' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '50')
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
        'office' => array(
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
        'fax' => array(
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
        'email' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'EmailAddress',
                    'params' => array()
                    ))
            ),
        'lib_address_id' => array(
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

