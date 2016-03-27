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

class Expression
{
    private $value;
    public $column=null;
    private $quote = false;

    public function __construct($value,$column=null,$quote=false){
        $this->value = $value;
        $this->column = $column;
        $this->quote = $quote;
    }

    /**
     * @return string
     */
    public function __toString(){
        if($this->quote==false)
            return addslashes($this->value);

        return "'".addslashes($this->value)."'";
    }


}