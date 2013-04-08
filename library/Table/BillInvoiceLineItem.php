<?php

/**
 * Table model for bill_invoice_line_item
 */
class Table_BillInvoiceLineItem extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'bill_invoice_line_item';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array(
        'BillInvoice' => array(
            'columns' => 'bill_invoice_id',
            'refTableClass' => 'Table_BillInvoice',
            'refColumns' => 'id'
            ),
        'LibService' => array(
            'columns' => 'lib_service_id',
            'refTableClass' => 'Table_LibService',
            'refColumns' => 'id'
            )
        );

    /**
     * Tables dependant on this one.
     */
    protected $_dependentTables = array();

    /**
     * Data dependancy chain.
     */
    protected $dependancyChain = array();

    /**
     * Field meta data.
     */
    protected $_metadata = array(
        'id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice_line_item',
            'COLUMN_NAME' => 'id',
            'COLUMN_POSITION' => 1,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => true,
            'PRIMARY_POSITION' => 1,
            'IDENTITY' => true,
            'FLAGS' => 2051,
            'FLAG_LIST' => 'FIELD_AUTOKEY | FIELD_REQUIRED'
            ),
        'bill_invoice_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice_line_item',
            'COLUMN_NAME' => 'bill_invoice_id',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'lib_service_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice_line_item',
            'COLUMN_NAME' => 'lib_service_id',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'description' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice_line_item',
            'COLUMN_NAME' => 'description',
            'COLUMN_POSITION' => 4,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '50',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'units' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice_line_item',
            'COLUMN_NAME' => 'units',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'decimal',
            'DEFAULT' => '1.00',
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => '2',
            'PRECISION' => '6',
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'months' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice_line_item',
            'COLUMN_NAME' => 'months',
            'COLUMN_POSITION' => 6,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'vat' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice_line_item',
            'COLUMN_NAME' => 'vat',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'decimal',
            'DEFAULT' => '0.00',
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => '2',
            'PRECISION' => '10',
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'total' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice_line_item',
            'COLUMN_NAME' => 'total',
            'COLUMN_POSITION' => 8,
            'DATA_TYPE' => 'decimal',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => '2',
            'PRECISION' => '10',
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            )
        );

    /**
     * No flags
     */
    protected $tableFlags = 0;

    /**
     * Field used to flag entry as archived.
     */
    protected $archiveField = false;

    /**
     * Field db-name to code-name mapping.
     */
    protected $fieldNames = array(
        'id' => 'Id',
        'bill_invoice_id' => 'BillInvoiceId',
        'lib_service_id' => 'LibServiceId',
        'description' => 'Description',
        'units' => 'Units',
        'months' => 'Months',
        'vat' => 'Vat',
        'total' => 'Total'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'bill_invoice_id' => null,
        'lib_service_id' => null,
        'description' => null,
        'units' => '1.00',
        'months' => null,
        'vat' => '0.00',
        'total' => null
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[description]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[bill_invoice_line_item_description]';


}

