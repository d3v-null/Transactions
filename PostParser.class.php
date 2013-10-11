<?php
require_once 'includes/transaction_setup.php';

class PostParser{
    public function __construct($db, $table, $map, $pars = Array(), $valid = Array();){
        $this->errs = Array();
        $this->pars = $pars;
        $this->valid = $valid
        
        //get metadata from table
        $this->meta = Array();
        $qry = 
            "SELECT TABLE_NAME, COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE".//, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
            "FROM information_schema.COLUMNS".
            "WHERE table_schema =  '".$db."'".
            "AND table_name =  '".$table."'";
        $result = mysql_query($sql) or die mysql_error();
        if(!$result) die("No fields in table".$id);
        while($row = mysql_fetch_array($result){
            $this->meta[$row['COLUMN_NAME']]=Array($row['COLUMN_DEFAULT'], $row['IS_NULLABLE']);
        }
        
        //$this->map = $map;
        
        //get data from table
        // $qry = "SELECT * FROM ".$table." WHERE ID = ".$id;
        // $result = mysql_query($sql) or die mysql_error();
        // if(!$result) die("No categories in database match given ID: ".$id);
        // $this->fetch = mysql_fetch_array($result);
    }
    
    public bool parse($post) {
        foreach($post as $k => $v){
            if(isset($this->meta[$k]) && !isset($this->pars[$k])){
                $this->pars[$k]=$v;
            }
        }
        
        foreach($this->meta as $k => $v){
            if(
    }
    
    public bool 
                
                
                
                
                if(is_null($this->meta[$k]){
                    
                
    
        foreach($this->map as $k => $v){
            if(!isset[$this->pars[$k]){
                if(isset($post[$k]){
                    $pars[$k] = $post[$k];
                } else if(isset($this->defaults[$v])){
                    if(!is_null($this->defaults[$v])){
                        $pars[$k] = $this->defaults[$v];
                    } else die('unable to parse ('.$k.', '.$v.')');
                }
            }
        }
    }
    
    public getInput($col){
    }   
}
        