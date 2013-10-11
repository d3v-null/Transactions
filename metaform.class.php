<?php
require_once 'includes/transaction_setup.php';

class MetaForm{
    public static string InputFormat($t){
        return function ($f, $p, $e){
            return 
                "<div class='inputformat' id='".$f."'>".
                    "<ul>".
                        "<li id='namefield'>".$f."</li>".
                        "<li id='input'><input name='".$f."' type='".$t."' value='".$p."'></li>".
                        "<li id='error'>".$e."</li>".
                    "<ul>".
                "</div>";
        }
    }

    public static array metaTable($db, $table){
        $meta = Array();
        $qry = 
            "SELECT TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE".//, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
            "FROM information_schema.COLUMNS".
            "WHERE table_schema =  '".$db."'".
            "AND table_name =  '".$table."'";
        $result = mysql_query($sql) or die mysql_error();
        if(!$result) die("No fields in table".$id);
        while($row = mysql_fetch_array($result){
            $meta[$row['COLUMN_NAME']]=Array($row['COLUMN_DEFAULT'], $row['IS_NULLABLE']);
        }
        return $meta;
    }
    
    public static array fetch($table, $id) {
        //get row from table
        $qry = "SELECT * FROM ".$table." WHERE ID = ".$id;
        $result = mysql_query($sql) or die mysql_error();
        if(!$result) die("No rows in database match given ID: ".$id);
        $this->fetch = mysql_fetch_array($result);
    }

    public function __construct($meta=array(), $pars = array(), $vlds = array(), $errs = array()){
        public $this->errs = $errs;
        public $this->pars = $pars;
        public $this->meta = $meta; 
        public $this->vlds = $vlds;
        private $this->valid = True;
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
        $this->validate()
    }
    
    public function validate(){        
        //validate each item in $pars
        foreach($this->pars as $k => $v){
            if(is_null($v) && isset($this->meta[$k]) && if($this->meta[$k][1]=='NO'){
                $errs[$k] = "Cannot be null";
                $this->valid = False;
                break;
            } 
            if(isset($vlds[$k])){
                if($vlds[$k]($v)){                        
                    $errs[$k] = $vlds[$k]($v);
                    $this->valid = False;
                    break;
                }
            }
        }
        return $this->valid;
    } 
    // public function validate(){        
        // //validate each item in $pars
        // foreach($this->pars as $k => $v){
            // if(is_null($v) && isset($this->meta[$k]) && if($this->meta[$k][1]=='NO'){
                // $errs[$k] = "Cannot be null";
                // $this->valid = False;
                // break;
            // } 
            // if(isset($this->vlds[$k]) && !$this->vlds[$k][1]($v)){
                // $errs[$k] = $vlds[$k][0];
                // $this->valid = False;
                // break;
            // }
        // }
        // return $this->valid;
    // }
         
    
    public string getHTML($field, $disp){
        return $disp(
            $field, 
            (isset($pars[$field])?$pars[$field]:$meta[$field][0]),
            (isset($errs[$field])?$errs[$field]:Null)
        );
    }   
}
        