<?php
require_once 'includes/transaction_setup.php';

class MetaForm{
    public $errs = array();
    public $pars = array();
    public $meta = array(); 
    public $vlds = array();
    public $disp = array();

    public static function InputFormat($t){
        return function ($f, $p, $e){
            return 
                "<div class='inputformat' id='".$f."'>".
                    "<ul>".
                        "<li id='namefield'>".$f."</li>".
                        "<li id='input'><input name='".$f."' type='".$t."' value='".$p."'></li>".
                        "<li id='error'>".$e."</li>".
                    "<ul>".
                "</div>";
        };
    }

    public static function metaTable($db, $table){
        $meta = Array();
        $qry = 
            "SELECT TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE ".//, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
            "FROM information_schema.COLUMNS ".
            "WHERE table_schema =  '".$db."' ".
            "AND table_name =  '".$table."' ";
        $result = mysql_query($qry) or die ("error: ".$qry."<br/>".mysql_error());
        if(!$result) die("No fields in table".$id);
        while($row = mysql_fetch_array($result)){
            $meta[$row['COLUMN_NAME']]=Array($row['COLUMN_DEFAULT'], $row['IS_NULLABLE']);
        }
        return $meta;
    }
    
    public static function fetch($table, $id) {
        //get row from table
        $qry = "SELECT * FROM ".$table." WHERE ID = ".$id;
        $result = mysql_query($qry) or die(mysql_error());
        if(!$result) die("No rows in database match given ID: ".$id);
        return mysql_fetch_array($result);
    }

    public function __construct(){
    }
    
    public function parse($post){
        //parse each item in $post
        foreach($post as $k => $v){
            if(isset($this->meta[$k]) && !isset($this->pars[$k])){
                $this->pars[$k]=$v;
            }
        }
        
        //complete $pars with default values
        foreach($this->meta as $k => $v){
            if(!isset($this->pars[$k])){
                $this->pars[$k] = $v[0];
            }
        }
        $this->validate();
    }
    
    public function validate(){        
        //validate each item in $pars
        foreach($this->pars as $k => $v){
            if(is_null($v) && isset($this->meta[$k]) && $this->meta[$k][1]=='NO'){
                $errs[$k] = "Cannot be null";
            } 
            if(isset($vlds[$k])){
                if($vlds[$k]($v)){                        
                    $errs[$k] = $vlds[$k]($v);
                }
            }
        }
        return $this->valid;
    } 
         
    
    public function display($disp){
        foreach($display as $k => $v){
            echo $disp[$k]($k, $v, $errs[$k]);
        }
    }   
}
        