<?php
/**
 * @link http://www.akmnahid.com
 * @author Nahid Hossain <communicate@akmnahid.com>
 * @package akmnahid\simple-orm
 * @copyright Copyright &copy; Nahid Hossain, 2016
 * @phone +8801727456280
 * @created 27/03/2016 12:56 PM
 * @license GNU GPL
 */

namespace akmnahid\simpleORM;


abstract class DatabaseObject implements DbInterface
{
    /* @var $dbInstance \PDO */
    protected static $dbInstance;

    /**
     * @return string
     */
    public static function getPrimaryKey(){

        return "id";
    }


    /**
     * @param null $query
     * @param array $bindParams
     * @return static[]
     */
    public static function findAll($query = null,$bindParams = []){
        if($query  instanceof \PDOStatement){
            $sth = $query;
        }
        elseif(is_int($query)){
            $sth = static::getDbInstance()->prepare("SELECT * from " . static::getTableName()." WHERE `".static::getPrimaryKey()."`= ".$query );
        }
        elseif($query==null) {
            $sth = static::getDbInstance()->prepare("SELECT * from " . static::getTableName());
        }
        elseif(is_string($query)) {
            $sth = static::getDbInstance()->prepare($query);
            /* foreach ($bindParams as $key => &$val) {
                $sth->bindParam($key, $val);
            }*/
        }
        elseif(is_array($query)) {
            //$sth = static::getDbInstance()->prepare("SELECT * from " . static::getTableName());
            $sql = array();
            $l_it =0;;
            foreach($query as $key => $value){

                if(is_array($value)){
                    if(is_object($value[0]) && $value[0] instanceof Expression ){
                        //$sql[] = " " . $value[0] . " `" . $key . "` = " . $value[1] . " ";
                        $glue = "AND";
                        if(isset($value[1]))
                            $glue =$value[1];
                        if($l_it==0)
                            $glue="";

                        $operator = "=";
                        if(isset($value[2]))
                            $operator = $value[2];


                        if($value[0]->column != null){
                            //  $sql[] = " `" . $value[1]->column . "` " . $value[0] .  "'" . $value[1] . "' ";
                            $sql[] = " " . $glue . " " . $value[0]->column . " ".$operator." " . $value[0] . " ";


                        }
                        else {
                            $sql[] = " " . $glue . " `" . $key . "` ".$operator." " . $value[0] . " ";
                        }
                        //$sql[] = " `" . $key . "` " . $value[0] .  "'" . $value[1] . "' ";

                    }else {
                        $sql[] = " " . $value[0] . " `" . $key . "` = :" . $key . " ";
                        $bindParams[":" . $key] = $value[1];
                    }

                }
                else {
                    if($l_it==0) {
                        $sql[] =  " `".$key . "` = :" . $key . " ";
                        $bindParams[":" . $key] = $value;
                    }
                    else{
                        $sql[] = " AND `" . $key . "` = :" . $key . " ";
                        $bindParams[":" . $key] = $value;

                    }
                }
                $l_it++;
            }


            $sth = static::getDbInstance()->prepare("SELECT * from " . static::getTableName()." WHERE ".implode(' ',$sql));


            /*

            //$sth = static::getDbInstance()->prepare("SELECT * from " . static::getTableName());
            $sql = array();

            foreach($query as $key => $value){
                $sql[]=" `".$key."` = :".$key." ";
                $bindParams[":".$key] = $value;
            }


            $sth = static::getDbInstance()->prepare("SELECT * from " . static::getTableName()." WHERE ".implode(' AND ',$sql));

            */

        }
        //echo $sth->queryString."\n<br>";
        $sth->execute($bindParams);
        return $sth->fetchAll(\PDO::FETCH_CLASS , get_called_class());

    }

    /**
     * @param mixed $query
     * @param array $bindParams
     * @return static
     */
    public static function findOne($query,$bindParams = []){

        if($query  instanceof \PDOStatement){
            $sth = $query;
        }
        elseif(is_int($query)){
            $sth = static::getDbInstance()->prepare("SELECT * from " . static::getTableName()." WHERE `".static::getPrimaryKey()."`= ".$query );
        }
        elseif(is_array($query)) {
            //$sth = static::getDbInstance()->prepare("SELECT * from " . static::getTableName());
            $sql = array();
            $l_it =0;;
            foreach($query as $key => $value){

                if(is_array($value)){
                    if(is_object($value[0]) && $value[0] instanceof Expression ){
                        //$sql[] = " " . $value[0] . " `" . $key . "` = " . $value[1] . " ";
                        $glue = "AND";
                        if(isset($value[1]))
                            $glue =$value[1];
                        if($l_it==0)
                            $glue="";

                        $operator = "=";
                        if(isset($value[2]))
                            $operator = $value[2];


                        if($value[0]->column != null){
                            //  $sql[] = " `" . $value[1]->column . "` " . $value[0] .  "'" . $value[1] . "' ";
                            $sql[] = " " . $glue . " " . $value[0]->column . " ".$operator." " . $value[0] . " ";


                        }
                        else {
                            $sql[] = " " . $glue . " `" . $key . "` ".$operator." " . $value[0] . " ";
                        }
                        //$sql[] = " `" . $key . "` " . $value[0] .  "'" . $value[1] . "' ";

                    }else {
                        $sql[] = " " . $value[0] . " `" . $key . "` = :" . $key . " ";
                        $bindParams[":" . $key] = $value[1];
                    }

                }
                else {
                    if($l_it==0) {
                        $sql[] =  " `".$key . "` = :" . $key . " ";
                        $bindParams[":" . $key] = $value;
                    }
                    else{
                        $sql[] = " AND `" . $key . "` = :" . $key . " ";
                        $bindParams[":" . $key] = $value;

                    }
                }
                $l_it++;
            }


            $sth = static::getDbInstance()->prepare("SELECT * from " . static::getTableName()." WHERE ".implode(' ',$sql));

        }
        elseif(is_string($query)) {
            $sth = static::getDbInstance()->prepare($query);
            /* foreach ($bindParams as $key => &$val) {
                 $sth->bindParam($key, $val);
             } */
        }

        // print_r($sth->debugDumpParams());
        //echo $sth->queryString;
        $sth->execute($bindParams);

        return $sth->fetchObject(get_called_class());

    }

    /**
     * Save current object as the row of table
     * @return bool
     * @throws \Exception
     */
    public function save(){

        $this->beforeSave();

        /*
                if($this->isNewRecord()){
                    //db insert operation

                    $cols = implode("`,`",$this->getColumns());
                    $values = implode(", :",$this->getColumns());
                    $sql = "INSERT INTO `".static::getTableName()."` ( `".$cols."` ) VALUES ( :".$values." )";
                }
                else{
        */
        $sql="";

        foreach(static::getColumns() as $column){
            if(is_object($this->{$column}) && $this->{$column} instanceof Expression){
                $sql[]= " `".$column."`=".$this->{$column};
            }
            else
                $sql[]= " `".$column."`=:".$column;
        }
        if($this->isNewRecord()){
            $sql = "INSERT INTO `" . static::getTableName() . "` SET " . implode(',', $sql);


        }
        else {
            $sql = "Update `" . static::getTableName() . "` SET " . implode(',', $sql) . " WHERE `" . static::getPrimaryKey() . "`=" . (int)$this->{static::getPrimaryKey()};
        }
        try{
            $stmt = static::getDbInstance()->prepare($sql);

            foreach(static::getColumns() as $column){
                if(is_object($this->{$column}) && $this->{$column} instanceof Expression){
                    continue;
                }
                $stmt->bindParam(":".$column,$this->{$column});
            }

            $stmt->execute();


            if($this->isNewRecord()){
                $this->{static::getPrimaryKey()} = static::getDbInstance()->lastInsertId();
            }

        }
        catch (\PDOException $ex){
            throw $ex;
        }
        catch(\Exception $ex){
            throw $ex;
        }


        return true;

    }

    public function delete(){
        $sql= "Delete From `".static::getTableName()."` WHERE `".static::getPrimaryKey()."`=".$this->{static::getPrimaryKey()};
        echo $sql;
        //static::getDbInstance()->query()
    }

    /**
     * @return bool
     */
    public function isNewRecord(){
        if($this->{self::getPrimaryKey()}==null)
            return true;
        else return false;
    }

    /**
     * @param \PDO $dbInstance
     */
    public static function setDbInstance(\PDO &$dbInstance){
        static::$dbInstance = $dbInstance;
        static::getDbInstance()->exec("SET CHARACTER SET utf8");

        // Set error reporting:
        static::getDbInstance()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
    }

    public static function createDbInstance($dsn,$username,$pass){
        try{
            static::$dbInstance = new \PDO($dsn, $username, $pass);
        }
        catch(\Exception $ex){
            echo die($ex->getMessage());
            // throw new \Exception("Cannot Create database instance ");
        }
    }

    public static function getDbInstance(){
        if(static::$dbInstance==null){
           throw new \Exception("Database Instance not Set");
        }
        return static::$dbInstance;
    }


    /**
     * @param $data []
     */
    public function populate($data){
        foreach($this->getColumns() as $key => $value){

            if(array_key_exists($value,$data)){
                $this->$value = $data[$value];
            }

        }

    }

    /**
     *Execute before save
     */
    public function beforeSave(){

    }


}