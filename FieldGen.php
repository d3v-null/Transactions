<?php
require_once 'includes/transaction_setup.php';

class FieldRule{
    public $emsg = "Field must be filled out correctly";
    public $rule;
    
    public function __construct($emsg, $rule){
        if($emsg){
            $this->emsg = $emsg;
        }
        if($rule){
            $this->rule = $rule;
        } else {
            die("rule not specified correctly");//!
        }
    }
}  

class FieldGen{
    public $errs = array(); //Errors for each parameter
    public $pars = array();
    public $ruls = array();
    public $lbls = array();

    public static function InputFormat($t){
        return function ($f, $p, $e) use ($t){
            return 
                "<div class='inputformat' id='".$f."'>".
                    "<ul>".
                        "<li id='namefield'>".$f."</li>".
                        "<li id='input'><input name='".$f."' type='".$t."' value='".$p."'></li>".
                        "<li id='error'>".$e."</li>".
                    "</ul>".
                "</div>";
        };
    }
    
    public static function fetch($table, $id) {
        $qry = "SELECT * FROM ".$table." WHERE ID = ".$id;
        $result = mysql_query($qry) or die(mysql_error());
        if(!$result) die("No rows in database match given ID: ".$id);
        return mysql_fetch_array($result);
    }

    public function __construct(){
    }
    
    public function get_lbl($col){
        if(isset($lbls[$col])) return $lbls[$col] else return $col;
    }
    
    public function parse_metadata($db, $table){
        $meta = array();
        $qry = 
            "SELECT TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH ".
            "FROM information_schema.COLUMNS ".
            "WHERE table_schema =  '".$db."' ".
            "AND table_name =  '".$table."' ";
        $result = mysql_query($qry) or die ("error: ".$qry."<br/>".mysql_error());
        if(!$result) die("No fields in table: ".$id);
        while($row = mysql_fetch_array($result)){
            if($row['IS_NULLABLE']=='NO'){
                add_rule(
                    $row['COLUMN_NAME'],
                    new FieldRule(
                        get_lbl($row['COLUMN_NAME'])." must not be empty",
                        function($v){ return !is_null($v); }
                    )
                )
            }
            //If int check if numeric
            //If text check character length
            //if FK test relation
        }
        return $meta;
    }
    
    public function add_rule($rule){
        if(isset($this->ruls[$k]){
            array_push($this->ruls[$k], $rule);
        } else {
            $this->ruls[$k] = array($rule);
        }
    }
    
    public function add_rules($rules){
        foreach($rules as $k){
            $this->add_rule($k[0],$k[1]);
        }
    
    public function parse($post){ //post, vlds, meta | pars, errs
        //parse each item in $post //post, meta | pars
        foreach($post as $k => $v){
            if(isset($this->meta[$k]) && !isset($this->pars[$k])){
                $this->pars[$k]=$v;
            }
        }
        
        //complete $pars with default values //meta | pars
        foreach($this->meta as $k => $v){
            if(!isset($this->pars[$k])){
                $this->pars[$k] = $v['def'];
            }
        }

        //validate each item in $pars //pars, meta | errs
        foreach($this->pars as $k => $v){
            if(is_null($v) && isset($this->meta[$k]) && $this->meta[$k]['nul']=='NO'){
                $errs[$k] = "Cannot be null";
            } 
            if(isset($vlds[$k])){
                if($vlds[$k]($v)){                        
                    $errs[$k] = $vlds[$k]($v);
                }
            }
        }
    } 
    
    public function display($disp){ //disp, hand, pars, errs
        foreach($disp as $k => $v){
            echo $v($k, $this->pars[$k], (isset($this->errs[$k]))?$this->errs[$k]:"");
        }
    }   
}
?>
        