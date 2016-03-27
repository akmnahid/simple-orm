<?php
/**
 * @link http://www.akmnahid.com
 * @author Nahid Hossain <communicate@akmnahid.com>
 * @package akmnahid\simple-orm
 * @copyright Copyright &copy; Nahid Hossain, 2016
 * @phone +8801727456280
 * @created 27/03/2016 01:15 PM
 * @license GNU GPL
 */

namespace akmnahid\simpleORM;


interface DbInterface
{
    /**
     * @return string
     */
    public static function getTableName();

    /**
     * @return array
     */
     public static function getColumns();

    /**
     * @return string
     */
    public static function getPrimaryKey();


}