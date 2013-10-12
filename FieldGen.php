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
    //for each field...
    public $lbls = array(); //Labels
    public $vals = array(); //values
    public $errs = array(); //Errors
    public $ruls = array(); //Evaluation rules
    
    //ID's used in generated HTML
    public static $labelid = 'label';
    public static $inputid = 'input';
    public static $errorid = 'error';

    public static function fieldGenList($id, $lbl, $fld, $err){
        return 
            "<div class='fieldgenlist' id='".$id."'>".
                "<ul>".
                    "<li id='".self::$labelid."'>".$lbl."</li>".
                    "<li id='".self::$inputid."'>".$fld."</li>".
                    "<li id='".self::$errorid."'>".$err."</li>".
                "</ul>".
            "</div>";        
    }
    
    public static function InputFormat($typ){
        return function ($id, $lbl, $val, $err) use($typ){
            $fld = "<input name='".$id."' type='".$typ."' value='".$val."'>";
            return self::fieldGenList($id, $lbl, $fld, $err);
        };
    }
    
    public static function OptionFormat($opts){
        return function ($id, $lbl, $val, $err) use($opts){
            $fld = "<select name=".$id.">";
            foreach($opts as $k => $v){
                $sel = ($k = $val)?"selected":"";
                $fld .= "<option value='".$k."' ".$sel.">".$v."</option>";
            }
            $fld .= "</select>";
            return self::fieldGenList($id, $lbl, $fld, $err);
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
        if(isset($lbls[$col])) {
            return $lbls[$col]; 
        } else {
            return $col;
        }
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
                $this->add_rule(
                    $row['COLUMN_NAME'],
                    new FieldRule(
                        $this->get_lbl($row['COLUMN_NAME'])." must not be empty",
                        function($v){ return !is_null($v); }
                    )
                );
            }
            //If int check if numeric
            //If text check character length
            //if FK test relation
        }
        return $meta;
    }
    
    public function add_rule($col, $rule){
        if(isset($this->ruls[$col])){
            array_push($this->ruls[$col], $rule);
        } else {
            $this->ruls[$col] = array($rule);
        }
    }
    
    // public function add_rules($rules){
        // foreach($rules as $k){
            // $this->add_rule($k[0],$k[1]);
        // }
    // }
    
    public function parse($post){ //post, vlds, meta | pars, errs
        //parse each item in $post //post, meta | pars
        foreach($post as $k => $v){
            if(!isset($this->vals[$k])){//don't over-write.
                $this->vals[$k]=$v;
            }
        }
        
        // //complete $vals with default values //meta | pars
        // foreach($this->meta as $k => $v){
            // if(!isset($this->vals[$k])){
                // $this->vals[$k] = $v['def'];
            // }
        // }

        //validate each item in $vals //pars, meta | errs
        foreach($this->vals as $k => $v){
            if(isset($ruls[$k])){
                foreach($ruls[$k] as $frul){
                    if(!$frul->rule($v)){
                        $errs[$k] = $frul->emsg;
                        break;
                    }
                }
            }
        }
    } 
    
    public function display($disp){ //disp, hand, pars, errs
        foreach($disp as $k => $v){
            echo $v(
                $k, 
                $this->get_lbl($k), 
                (isset($this->vals[$k]))?$this->vals[$k]:"",
                (isset($this->errs[$k]))?$this->errs[$k]:""
            );
        }
    }   
}
?>
        