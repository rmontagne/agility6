<?php

Class Import {
    
    public function ParseCsv($filename) {
        $content    = file_get_contents($filename);
        
        $headers    = false;
        $rows       = [];
        
        $tmp        = explode("\n", $content);
        $firstLoop  = false;
        
        foreach ($tmp as $row) {
            $cols = explode(";", $row);
            
            if (!$firstLoop) {
                $headers = $cols;
                $firstLoop = true;
            } else {
                
                if (count($cols) != count($headers)) {
                    continue;
                }
                
                $rows[] = array_combine($headers, $cols);
            }
        }
        
        return $rows;
    }
}