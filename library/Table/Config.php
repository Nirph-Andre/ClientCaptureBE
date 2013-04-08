<?php

/**
 * Table model for config
 */
class Table_Config extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'config';

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
            'TABLE_NAME' => 'config',
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
        'country_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'config',
            'COLUMN_NAME' => 'country_id',
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
        'date_format' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'config',
            'COLUMN_NAME' => 'date_format',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '20',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'time_format' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'config',
            'COLUMN_NAME' => 'time_format',
            'COLUMN_POSITION' => 4,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '20',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'lib_currency_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'config',
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
        'currency_prefix' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'config',
            'COLUMN_NAME' => 'currency_prefix',
            'COLUMN_POSITION' => 6,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '5',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'vat_percentage' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'config',
            'COLUMN_NAME' => 'vat_percentage',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'decimal',
            'DEFAULT' => '0.00',
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => '2',
            'PRECISION' => '5',
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'notification_source_email' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'config',
            'COLUMN_NAME' => 'notification_source_email',
            'COLUMN_POSITION' => 8,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '255',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'notification_source_number' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'config',
            'COLUMN_NAME' => 'notification_source_number',
            'COLUMN_POSITION' => 9,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '20',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'administrative_email' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'config',
            'COLUMN_NAME' => 'administrative_email',
            'COLUMN_POSITION' => 10,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '255',
            'SCALE' => null,
            'PRECISION' => null,
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
        'country_id' => 'CountryId',
        'date_format' => 'DateFormat',
        'time_format' => 'TimeFormat',
        'lib_currency_id' => 'LibCurrencyId',
        'currency_prefix' => 'CurrencyPrefix',
        'vat_percentage' => 'VatPercentage',
        'notification_source_email' => 'NotificationSourceEmail',
        'notification_source_number' => 'NotificationSourceNumber',
        'administrative_email' => 'AdministrativeEmail'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'country_id' => null,
        'date_format' => null,
        'time_format' => null,
        'lib_currency_id' => '1',
        'currency_prefix' => null,
        'vat_percentage' => '0.00',
        'notification_source_email' => null,
        'notification_source_number' => null,
        'administrative_email' => null
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[date_format]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[config_date_format]';


}

