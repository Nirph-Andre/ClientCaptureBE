<?php

/**
 * DataAccess Value Object for table mobile_content
 */
class Object_MobileContent extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'MobileContent';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'mobile_content';

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
        'type' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '25')
                    ))
            ),
        'name' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '250')
                    ))
            ),
        'html' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => 65535)
                    ))
            ),
        'js' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => 65535)
                    ))
            )
        );


}

