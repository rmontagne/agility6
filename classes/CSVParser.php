<?php
require_once('Parser.php');

class CSVParser extends Parser
{
    public function parse() {
        $tmp = explode("\n",$this->content);
        $this->lineNumber = count($tmp)-1;
        $firstLoop = true;
        
        foreach($tmp as $row) {
            
            $cols = explode(";",$row);
            if($firstLoop)
            {
                $this->headers = $cols;
                $this->colNumber = count($cols);
                $firstLoop=false;
            } else {
                if(count($cols)!=$this->colNumber){
                    continue;
                }
                $this->data[] = array_combine($this->headers,$cols);
            }
        }
        return $this->data;
    }
}