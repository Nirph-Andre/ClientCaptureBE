<?php

/**
 * DataAccess Value Object for table content
 */
class Object_Content extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'Content';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'content';

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
                    'params' => array('max' => '100')
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

