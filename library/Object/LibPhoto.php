<?php

/**
 * DataAccess Value Object for table lib_photo
 */
class Object_LibPhoto extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'LibPhoto';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'lib_photo';

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
        'photo' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => 16777215)
                    ))
            ),
        'thumbnail' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => 16777215)
                    ))
            ),
        'mime_type' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '200')
                    ))
            )
        );


}

