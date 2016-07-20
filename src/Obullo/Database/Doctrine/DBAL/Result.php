<?php

namespace Obullo\Database\Doctrine\DBAL;

use PDO;
use Doctrine\DBAL\Driver\Statement;

/**
 * Database Result
 * 
 * @copyright 2009-2016 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class Result
{
    /**
     * Pdo Statement
     * 
     * @var object
     */
    public $stmt;

    /**
     * Create pdo statement object
     * 
     * @param PDOStatement $stmt pdo statement object
     */
    public function __construct(Statement $stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * Returns number of rows.
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * Get row as object & if fail return false
     *
     * @param boolean $default return value to if operation fail
     * 
     * @return array | object otherwise false
     */
    public function getRow($default = false)
    {
        $result = $this->stmt->fetch(PDO::FETCH_OBJ);

        return ($result) ? $result : $default;
    }

    /**
     * Get row as array & if fail return 
     * 
     * @param boolean $default return value to if operation fail
     * 
     * @return array | object otherwise false
     */
    public function getRowArray($default = false)
    {
        $result = $this->stmt->fetch(PDO::FETCH_ASSOC);
        
        return ($result) ? $result : $default;
    }

    /**
     * Get results as array & if fail return ARRAY
     * 
     * @param boolean $default return value to if operation fail
     * 
     * @return array | object otherwise false
     */
    public function getResult($default = false)
    {
        $result = $this->stmt->fetchAll(PDO::FETCH_OBJ);

        return ($result) ? $result : $default;
    }

    /**
     * Get results as array & if fail return ARRAY
     * 
     * @param boolean $default return value to if operation fail
     * 
     * @return array | object otherwise false
     */
    public function getArrayResult($default = false)
    {
        $result = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ($result) ? $result : $default;
    }

}