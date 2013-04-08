<?php

/**
 * DataAccess Value Object for table bill_invoice_line_item
 */
class Object_BillInvoiceLineItem extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'BillInvoiceLineItem';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'bill_invoice_line_item';

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
        'bill_invoice_id' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 4294967295
                        )
                    ))
            ),
        'lib_service_id' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 4294967295
                        )
                    ))
            ),
        'description' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '50')
                    ))
            ),
        'units' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Float',
                    'params' => array()
                    ))
            ),
        'months' => array(
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
        'total' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'Float',
                    'params' => array()
                    ))
            )
        );


}

