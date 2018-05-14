<?php

    class CsvReader {
        
        protected $fileName         = '';
        protected $rows;
        
        
        public function readCsv() {
            $content    = file_get_contents($this->fileName);
            $headers    = ['reference', 'name', 'description', 'price', 'image'];
            $finalArray = [];
            $tmp        = explode("\n", $content);
            
            foreach ($tmp as $row) {
                $cols = explode(";", $row);
                $i = 0;
                foreach ($cols as $col) {
                    $header             = $headers[$i];
                    $tmpRow[$header]    = $col;
                    $i                  +=1;
                }
                $finalArray[] = $tmpRow;
            }
            $this->rows = $finalArray;
            
            return $this->rows;
        }
        
        public function getFileName() {
            return $this->fileName;
        }
        
        public function getRows() {
            return $this->rows;
        }
        
        public function setFileName($newFileName) {
            if (is_string($newFileName)) {
                $this->fileName = $newFileName;
                return $this;
            }
        }
    }