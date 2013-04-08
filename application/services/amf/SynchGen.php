<?php

/**
 * Data services for tablet devices.
 */
class SynchGen extends Struct_Abstract_AmfService
{

    /**
     * Create a new Profile entry.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function createProfile($authToken, $data)
    {
        return $this->synch($authToken, 'Profile', 'Create', $data);
    }

    /**
     * Update existing Profile entry.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function updateProfile($authToken, $data)
    {
        return $this->synch($authToken, 'Profile', 'Update', $data);
    }

    /**
     * Delete a new Profile entry.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function deleteProfile($authToken, $data)
    {
        return $this->synch($authToken, 'Profile', 'Delete', $data);
    }

    /**
     * Find existing Profile entry by id.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function findProfile($authToken, $data)
    {
        return $this->synch($authToken, 'Profile', 'Find', $data);
    }

    /**
     * key > value list of Profile entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function listProfile($authToken, $options)
    {
        return $this->synch($authToken, 'Profile', 'List', array(), $options);
    }

    /**
     * Full data grid of Profile entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function gridProfile($authToken, $options)
    {
        return $this->synch($authToken, 'Profile', 'Grid', array(), $options);
    }

    /**
     * Create a new LibAddress entry.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function createLibAddress($authToken, $data)
    {
        return $this->synch($authToken, 'LibAddress', 'Create', $data);
    }

    /**
     * Update existing LibAddress entry.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function updateLibAddress($authToken, $data)
    {
        return $this->synch($authToken, 'LibAddress', 'Update', $data);
    }

    /**
     * Delete a new LibAddress entry.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function deleteLibAddress($authToken, $data)
    {
        return $this->synch($authToken, 'LibAddress', 'Delete', $data);
    }

    /**
     * Find existing LibAddress entry by id.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function findLibAddress($authToken, $data)
    {
        return $this->synch($authToken, 'LibAddress', 'Find', $data);
    }

    /**
     * key > value list of LibAddress entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function listLibAddress($authToken, $options)
    {
        return $this->synch($authToken, 'LibAddress', 'List', array(), $options);
    }

    /**
     * Full data grid of LibAddress entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function gridLibAddress($authToken, $options)
    {
        return $this->synch($authToken, 'LibAddress', 'Grid', array(), $options);
    }

    /**
     * Create a new LibPhoto entry.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function createLibPhoto($authToken, $data)
    {
        return $this->synch($authToken, 'LibPhoto', 'Create', $data);
    }

    /**
     * Update existing LibPhoto entry.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function updateLibPhoto($authToken, $data)
    {
        return $this->synch($authToken, 'LibPhoto', 'Update', $data);
    }

    /**
     * Delete a new LibPhoto entry.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function deleteLibPhoto($authToken, $data)
    {
        return $this->synch($authToken, 'LibPhoto', 'Delete', $data);
    }

    /**
     * Find existing LibPhoto entry by id.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function findLibPhoto($authToken, $data)
    {
        return $this->synch($authToken, 'LibPhoto', 'Find', $data);
    }

    /**
     * key > value list of LibPhoto entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function listLibPhoto($authToken, $options)
    {
        return $this->synch($authToken, 'LibPhoto', 'List', array(), $options);
    }

    /**
     * Full data grid of LibPhoto entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function gridLibPhoto($authToken, $options)
    {
        return $this->synch($authToken, 'LibPhoto', 'Grid', array(), $options);
    }


}

