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
            die("rule not specified correctly: ".$this->emsg);//!
        }
    }
    
    public function __toString(){
        return $this->emsg;
    }
}  

class FieldGen{
    
    
    //for each field...
    public $lbls = array(); //Labels
    public $meta = array(); //Metadata
    public $vals = array(); //values
    public $errs = array(); //Errors
    public $ruls = array(); //Evaluation rules
    
    public static function fieldList($id, $lbl, $fld, $err){
        return 
            "<div class='fieldgenlist' id='".$id."'>".
                "<ul>".
                    "<li>".$lbl."</li>".
                    "<li>".$fld."</li>".
                    (($err != "")?"<li class='form-error'>".$err."</li>":"").
                "</ul>".
            "</div>";        
    }

    public static function fieldRow($id, $lbl, $fld, $err){
        return
            "<tr id='".$id."'>".
                "<td>".$lbl."</td>".
                "<td>".$fld."</td>".
            "</tr>".(
                ($err != "")?
                    "<tr><td class='form-error' classcolspan='2'>".$err."</td></tr>":
                    ""
            );
    }
    
    public static function inputFormat($typ, $arr){
        return function ($id, $lbl, $val, $rqd, $err) use($typ, $arr){  
            if($rqd) echo $id;
            $fld = "<input name='".$id."' type='".$typ."' value='".$val."' ".
                (($rqd)?"required":"").">";
            $lbc = "<label for ='".$id."'>".$lbl.(($rqd)?"*":"")."</label>";
            return $arr($id, $lbc, $fld, $err);
        };
    }
      

    public static function optionFormat($opts, $arr){
        return function ($id, $lbl, $val, $rqd, $err) use($opts, $arr){
            $fld = "<select name='".$id."'>";
            foreach($opts as $k => $v){
                $sel = ($k == $val)?"selected":"";
                $fld .= "<option value='".$k."' ".$sel.">".$v."</option>";
            }
            $fld .= "</select>";
            $lbc = "<label for ='".$id."'>".$lbl.(($rqd)?"*":"")."</label>";
            return $arr($id, $lbc, $fld, $err);
        };  
    }
    
    public static function textFormat($arr){
        return function ($id, $lbl, $val, $rqd, $err) use ($arr){
            $fld = "<textarea name='".$id."' ".(($rqd)?" required ":"").">".$val."</textarea>";
            $lbc = "<label for ='".$id."'>".$lbl.(($rqd)?"*":"")."</label>";
            return $arr($id, $lbc, $fld, $err);
        };
    }
    
    public static function sqlFormat($s){
        return "\"".mysql_real_escape_string($s)."\"";
    }
    
    public static function fetch($table, $id) {
        $qry = "SELECT * FROM ".$table." WHERE ID = ".$id;
        $result = mysql_query($qry) or die(mysql_error());
        if(!$result) {
            echo "<html><body><script>alert('No rows in database match given ID: ".$id."')</script>";
        }
        return mysql_fetch_array($result);
    }
    
    public static function exit_gracefully($msg){
        echo "<script>alert('".$msg."')</script>";
        exit;
    }
        

    public function __construct(){
    }
    
    public function get_lbl($col){
        if(isset($this->lbls[$col])) {
            return $this->lbls[$col]; 
        } else {
            return $col;
        }
    }
    
    public function parse_metadata($db, $table){ //gets fields and rules from table
        $qry = 
            "SELECT TABLE_SCHEMA, TABLE_NAME, ".
            "COLUMN_NAME, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH ".
            "FROM information_schema.COLUMNS ".
            "WHERE table_schema =  '".$db."' ".
            "AND table_name =  '".$table."' ";
        $result = mysql_query($qry) or die ("error: ".$qry."<br/>".mysql_error());
        if(!$result) die("No fields in table: ".$id);
        while($row = mysql_fetch_assoc($result)){
            $this->meta[$row['COLUMN_NAME']] = array(
                'type'     => $row['DATA_TYPE'],
                'required' => $row['IS_NULLABLE']=="NO"
            );
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
    }
    
    public function add_rule($col, $rule){
        if(isset($this->ruls[$col])){
            array_push($this->ruls[$col], $rule);
        } else {
            $this->ruls[$col] = array($rule);
        }
    }
    
    public function parse($post){ //post, vlds, | pars, errs
        //parse each item in $post //post, meta | pars
        foreach($post as $k => $v){
            if(array_key_exists($k,$this->meta)){//ignore anything not in table
                $this->vals[$k]=$v;
            }
        }
    }
        //validate each item in $vals //pars, $ruls | errs
    public function validate(){
        $valid = true;
        foreach($this->vals as $k => $v){
            if(isset($this->ruls[$k])){
                foreach($this->ruls[$k] as $frul){
                    $r = $frul->rule;
                    if(!$r($v)){
                        $this->errs[$k] = $frul->emsg;
                        $valid = false;
                    }
                }
            }
        }
        return $valid;
    } 
    
    public function display($disp){ //disp, hand, pars, errs
        $out = "";
        foreach($disp as $k => $v){
            $out .= $v(
                $k, 
                $this->get_lbl($k), 
                (isset($this->vals[$k]))?$this->vals[$k]:"",
                (isset($this->meta[$k]))?$this->meta[$k]['required']:0,
                (isset($this->errs[$k]))?$this->errs[$k]:""
            );
        }
        return $out;
    }   
    
    // public function auto_table($disp){
        // $out = "";
        // $out .= "<table class='auto_table'>";
        // foreach($disp as $k => $v){
            // $fmt = (!is_null($v))?$v:['this','fieldRow'];
            // $out .= $v(
                // $k, 
                // $this->get_lbl($k), 
                // (isset($this->vals[$k]))?$this->vals[$k]:"",
                // (isset($this->meta[$k]['required'])?$this->meta[$k]['required']:False,
                // (isset($this->errs[$k]))?$this->errs[$k]:""
            // ); 
            // } else {
                
        // $out .= "</table>";
    
    public function mysql_insert(){
        $sql = "
            INSERT INTO history (".
                    implode(", ", array_keys($this->vals)).
                ") VALUES (".
                    implode(", ", array_map(['self', 'sqlFormat'], array_values($this->vals))).
                ") ";
        //echo "<script>alert(".$sql.")</script>";
        mysql_query( $sql ) or $this::exit_gracefully("Could not insert row: ".mysql_error());
        
    }
    
    public function __toString(){
        $out = "";
        $out .= "rules: \n";
        foreach($this->ruls as $k => $v){
            $out .= "->".$k."\n";
            foreach($v as $r){
                $out .= "-->".$r."\n";
            }
        } 
        return $out;
    }
    
//    public function 
}
?>
        
