<?php

class Compiler {

    private $processingType;

    public function __construct(array &$data, string $processingType = 'html') {

        $this->processingType = $processingType;
    }
    
    public function isActive(array &$data) {
        
    }

    public function set(array &$data) {

        switch ($this->processingType) {
            case 'html':
                $obj = new GenerateWebsiteHtmlFile($data);
                $obj->set($data);
                break;
        }
    }
    
    public function delete(array &$data) {

        switch ($this->processingType) {
            case 'html':
                $obj = new GenerateWebsiteHtmlFile($data);
                $obj->delete($data);
                break;
        }
    }    

}
