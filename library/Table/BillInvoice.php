<?php

/**
 * Table model for bill_invoice
 */
class Table_BillInvoice extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'bill_invoice';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array(
        'Profile' => array(
            'columns' => 'profile_id',
            'refTableClass' => 'Table_Profile',
            'refColumns' => 'id'
            ),
        'LibCurrency' => array(
            'columns' => 'lib_currency_id',
            'refTableClass' => 'Table_LibCurrency',
            'refColumns' => 'id'
            )
        );

    /**
     * Tables dependant on this one.
     */
    protected $_dependentTables = array('bill_invoice_line_item' => 'Table_BillInvoiceLineItem');

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
            'TABLE_NAME' => 'bill_invoice',
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
        'invoice_nr' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice',
            'COLUMN_NAME' => 'invoice_nr',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '30',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'type' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice',
            'COLUMN_NAME' => 'type',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'enum(\'Prorata Invoice\',\'Tax Invoice\',\'Credit Note\')',
            'DEFAULT' => 'Prorata Invoice',
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'profile_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice',
            'COLUMN_NAME' => 'profile_id',
            'COLUMN_POSITION' => 4,
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
        'lib_currency_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice',
            'COLUMN_NAME' => 'lib_currency_id',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'int',
            'DEFAULT' => '1',
            'NULLABLE' => false,
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
            'TABLE_NAME' => 'bill_invoice',
            'COLUMN_NAME' => 'vat',
            'COLUMN_POSITION' => 6,
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
        'amount' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice',
            'COLUMN_NAME' => 'amount',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'decimal',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => '2',
            'PRECISION' => '12',
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'created' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice',
            'COLUMN_NAME' => 'created',
            'COLUMN_POSITION' => 8,
            'DATA_TYPE' => 'datetime',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 35,
            'FLAG_LIST' => 'FIELD_REQUIRED | FIELD_INSERT_DATETIME'
            ),
        'updated' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice',
            'COLUMN_NAME' => 'updated',
            'COLUMN_POSITION' => 9,
            'DATA_TYPE' => 'datetime',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 288,
            'FLAG_LIST' => 'FIELD_UPDATE_DATETIME'
            ),
        'archived' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'bill_invoice',
            'COLUMN_NAME' => 'archived',
            'COLUMN_POSITION' => 10,
            'DATA_TYPE' => 'tinyint',
            'DEFAULT' => '0',
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            )
        );

    /**
     * TABLE_NO_DELETE | TABLE_PSEUDO_DELETE
     */
    protected $tableFlags = 12;

    /**
     * Field used to flag entry as archived.
     */
    protected $archiveField = 'archived';

    /**
     * Field db-name to code-name mapping.
     */
    protected $fieldNames = array(
        'id' => 'Id',
        'invoice_nr' => 'InvoiceNr',
        'type' => 'Type',
        'profile_id' => 'ProfileId',
        'lib_currency_id' => 'LibCurrencyId',
        'vat' => 'Vat',
        'amount' => 'Amount',
        'created' => 'Created',
        'updated' => 'Updated',
        'archived' => 'Archived'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'invoice_nr' => null,
        'type' => 'Prorata Invoice',
        'profile_id' => null,
        'lib_currency_id' => '1',
        'vat' => '0.00',
        'amount' => null,
        'created' => null,
        'updated' => null,
        'archived' => '0'
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[invoice_nr]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[bill_invoice_invoice_nr]';


}

