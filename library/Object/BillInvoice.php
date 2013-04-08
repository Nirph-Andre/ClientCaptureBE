<?php

/**
 * DataAccess Value Object for table bill_invoice
 */
class Object_BillInvoice extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'BillInvoice';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'bill_invoice';

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
        'invoice_nr' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '30')
                    ))
            ),
        'type' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'InArray',
                    'params' => array(
                        'Prorata Invoice',
                        'Tax Invoice',
                        'Credit Note'
                        )
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
            ),
        'lib_currency_id' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 4294967295
                        )
                    ))
            ),
        'vat' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Float',
                    'params' => array()
                    ))
            ),
        'amount' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'Float',
                    'params' => array()
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

