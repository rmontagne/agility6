<?php 
abstract class Parser
{
    protected $content;
    protected $data;
    protected $headers;
    protected $colNumber;
    protected $lineNumber; // sans les headers
    
    abstract function parse();
    
    public function __construct($content,$headers) {
        $this->content = $content;
        $this->headers = $headers;
        $this->colNumber = count($headers);
    }
    public function __toString() {
        return  'linumber : '.$this->lineNumber.'<br/>'.
                'colNumber : '.$this->colNumber.'<br/>';
    }
    //getters
    public function content() {
        return $this->content;
    }
    public function data() {
        return $this->data;
    }
    public function headers() {
        return $this->headers;
    }
    public function colNumber() {
        return $this->colNumber;
    }
    public function lineNumber() {
        return $this->lineNumber;
    }    
}
?>