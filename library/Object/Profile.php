<?php

/**
 * DataAccess Value Object for table profile
 */
class Object_Profile extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'Profile';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'profile';

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
                    'params' => array('max' => '100')
                    ))
            ),
        'family_name' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '100')
                    ))
            ),
        'id_number' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '13')
                    ))
            ),
        'date_of_birth' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Regex',
                    'params' => array('pattern' => '/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/')
                    ))
            ),
        'mobile' => array(
            'required' => true,
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
            'required' => false,
            'validators' => array(array(
                    'type' => 'EmailAddress',
                    'params' => array()
                    ))
            ),
        'username' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '40')
                    ))
            ),
        'password' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '40')
                    ))
            ),
        'password_salt' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '40')
                    ))
            ),
        'user_type' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'InArray',
                    'params' => array(
                        'User',
                        'Administrator'
                        )
                    ))
            ),
        'status' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'InArray',
                    'params' => array(
                        'Active',
                        'Suspended'
                        )
                    ))
            ),
        'subscribe_newsletter' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 255
                        )
                    ))
            ),
        'subscribe_reminders' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 255
                        )
                    ))
            ),
        'last_login' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Regex',
                    'params' => array('pattern' => '/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01]) (0?[0-9]|1[0-9]|2[0-4]):(0?[0-9]|[1-5][0-9])(:(0?[0-9]|[1-5][0-9]))?$/')
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

