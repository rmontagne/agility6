<?php 

Class CsvReader {
    
    public function ParseCsv($filename) {
        $content    = file_get_contents($filename);
        
        $headers    = false;
        $rows       = [];
        
        $tmp        = explode("\n", $content);
        $firstLoop  = false;
        
        foreach ($tmp as $row) {
            $cols = explode(";", $row);
            
            $rows[] = $cols;
        }
        
        return $rows;
    }
}