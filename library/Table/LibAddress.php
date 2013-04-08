<?php

/**
 * Table model for lib_address
 */
class Table_LibAddress extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'lib_address';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array('LibCity' => array(
            'columns' => 'lib_city_id',
            'refTableClass' => 'Table_LibCity',
            'refColumns' => 'id'
            ));

    /**
     * Tables dependant on this one.
     */
    protected $_dependentTables = array('lib_contact' => 'Table_LibContact');

    /**
     * Data dependancy chain.
     */
    protected $dependancyChain = array('LibCity' => array(
            array(
                'columns' => 'lib_region_id',
                'refTableClass' => 'Table_LibRegion',
                'refColumns' => 'id'
                ),
            array(
                'columns' => 'lib_country_id',
                'refTableClass' => 'Table_LibCountry',
                'refColumns' => 'id'
                )
            ));

    /**
     * Field meta data.
     */
    protected $_metadata = array(
        'id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_address',
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
        'name' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_address',
            'COLUMN_NAME' => 'name',
            'COLUMN_POSITION' => 2,
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
        'lib_city_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_address',
            'COLUMN_NAME' => 'lib_city_id',
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
        'address' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_address',
            'COLUMN_NAME' => 'address',
            'COLUMN_POSITION' => 4,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '250',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'postal_code' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_address',
            'COLUMN_NAME' => 'postal_code',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '10',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'latitude' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_address',
            'COLUMN_NAME' => 'latitude',
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
        'longitude' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_address',
            'COLUMN_NAME' => 'longitude',
            'COLUMN_POSITION' => 7,
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
        'created' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_address',
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
            'TABLE_NAME' => 'lib_address',
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
            'TABLE_NAME' => 'lib_address',
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
        'name' => 'Name',
        'lib_city_id' => 'LibCityId',
        'address' => 'Address',
        'postal_code' => 'PostalCode',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'created' => 'Created',
        'updated' => 'Updated',
        'archived' => 'Archived'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'name' => null,
        'lib_city_id' => null,
        'address' => null,
        'postal_code' => null,
        'latitude' => null,
        'longitude' => null,
        'created' => null,
        'updated' => null,
        'archived' => '0'
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[name]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[lib_address_name]';


}

