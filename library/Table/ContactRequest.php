<?php

/**
 * Table model for contact_request
 */
class Table_ContactRequest extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'contact_request';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array();

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
            'TABLE_NAME' => 'contact_request',
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
        'person_name' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'contact_request',
            'COLUMN_NAME' => 'person_name',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '100',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'trading_name' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'contact_request',
            'COLUMN_NAME' => 'trading_name',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '100',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'email' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'contact_request',
            'COLUMN_NAME' => 'email',
            'COLUMN_POSITION' => 4,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '255',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'mobile' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'contact_request',
            'COLUMN_NAME' => 'mobile',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '20',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'telephone' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'contact_request',
            'COLUMN_NAME' => 'telephone',
            'COLUMN_POSITION' => 6,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '20',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'subject' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'contact_request',
            'COLUMN_NAME' => 'subject',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '100',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'message' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'contact_request',
            'COLUMN_NAME' => 'message',
            'COLUMN_POSITION' => 8,
            'DATA_TYPE' => 'text',
            'DEFAULT' => null,
            'NULLABLE' => true,
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
        'created' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'contact_request',
            'COLUMN_NAME' => 'created',
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
            'FLAGS' => 32,
            'FLAG_LIST' => 'FIELD_INSERT_DATETIME'
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
        'person_name' => 'PersonName',
        'trading_name' => 'TradingName',
        'email' => 'Email',
        'mobile' => 'Mobile',
        'telephone' => 'Telephone',
        'subject' => 'Subject',
        'message' => 'Message',
        'created' => 'Created'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'person_name' => null,
        'trading_name' => null,
        'email' => null,
        'mobile' => null,
        'telephone' => null,
        'subject' => null,
        'message' => null,
        'created' => null
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[person_name]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[contact_request_person_name]';


}

