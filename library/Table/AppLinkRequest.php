<?php

/**
 * Table model for app_link_request
 */
class Table_AppLinkRequest extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'app_link_request';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array('Profile' => array(
            'columns' => 'profile_id',
            'refTableClass' => 'Table_Profile',
            'refColumns' => 'id'
            ));

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
            'TABLE_NAME' => 'app_link_request',
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
        'profile_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_link_request',
            'COLUMN_NAME' => 'profile_id',
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
        'device_os' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_link_request',
            'COLUMN_NAME' => 'device_os',
            'COLUMN_POSITION' => 3,
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
        'device_cpu' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_link_request',
            'COLUMN_NAME' => 'device_cpu',
            'COLUMN_POSITION' => 4,
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
        'ip_address' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_link_request',
            'COLUMN_NAME' => 'ip_address',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '23',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'is_blacklisted' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_link_request',
            'COLUMN_NAME' => 'is_blacklisted',
            'COLUMN_POSITION' => 6,
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
            ),
        'is_disabled' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_link_request',
            'COLUMN_NAME' => 'is_disabled',
            'COLUMN_POSITION' => 7,
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
            ),
        'status' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_link_request',
            'COLUMN_NAME' => 'status',
            'COLUMN_POSITION' => 8,
            'DATA_TYPE' => 'enum(\'Requested\',\'Active\',\'Disabled\',\'BlackListed\',\'Archived\')',
            'DEFAULT' => 'Requested',
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
        'created' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'app_link_request',
            'COLUMN_NAME' => 'created',
            'COLUMN_POSITION' => 9,
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
            'TABLE_NAME' => 'app_link_request',
            'COLUMN_NAME' => 'updated',
            'COLUMN_POSITION' => 10,
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
            'TABLE_NAME' => 'app_link_request',
            'COLUMN_NAME' => 'archived',
            'COLUMN_POSITION' => 11,
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
        'profile_id' => 'ProfileId',
        'device_os' => 'DeviceOs',
        'device_cpu' => 'DeviceCpu',
        'ip_address' => 'IpAddress',
        'is_blacklisted' => 'IsBlacklisted',
        'is_disabled' => 'IsDisabled',
        'status' => 'Status',
        'created' => 'Created',
        'updated' => 'Updated',
        'archived' => 'Archived'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'profile_id' => null,
        'device_os' => null,
        'device_cpu' => null,
        'ip_address' => null,
        'is_blacklisted' => '0',
        'is_disabled' => '0',
        'status' => 'Requested',
        'created' => null,
        'updated' => null,
        'archived' => '0'
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[device_os]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[app_link_request_device_os]';


}

