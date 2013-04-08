<?php

/**
 * Table model for app_audit_log
 */
class Table_AppAuditLog extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'app_audit_log';

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
            'TABLE_NAME' => 'app_audit_log',
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
        'customer_context' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_audit_log',
            'COLUMN_NAME' => 'customer_context',
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
        'customer_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_audit_log',
            'COLUMN_NAME' => 'customer_id',
            'COLUMN_POSITION' => 3,
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
        'action' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_audit_log',
            'COLUMN_NAME' => 'action',
            'COLUMN_POSITION' => 4,
            'DATA_TYPE' => 'enum(\'Add\',\'Update\',\'Delete\')',
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
        'table_name' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_audit_log',
            'COLUMN_NAME' => 'table_name',
            'COLUMN_POSITION' => 5,
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
        'record_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_audit_log',
            'COLUMN_NAME' => 'record_id',
            'COLUMN_POSITION' => 6,
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
        'data_packet' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_audit_log',
            'COLUMN_NAME' => 'data_packet',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'mediumtext',
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
            'TABLE_NAME' => 'app_audit_log',
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
        'customer_context' => 'CustomerContext',
        'customer_id' => 'CustomerId',
        'action' => 'Action',
        'table_name' => 'TableName',
        'record_id' => 'RecordId',
        'data_packet' => 'DataPacket',
        'created' => 'Created'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'customer_context' => null,
        'customer_id' => null,
        'action' => null,
        'table_name' => null,
        'record_id' => null,
        'data_packet' => null,
        'created' => null
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[table_name]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[app_audit_log_table_name]';


}

