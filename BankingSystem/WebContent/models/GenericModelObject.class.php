<?php
abstract class GenericModelObject {
    protected $errors;
    protected $errorCount;
    
    public function extractForm($arguments, $index) {
        $value = "";
        if (isset($arguments[$index])) {
            $value = trim($arguments[$index]);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
        }
        return $value;
    }
    
    public function getError($errorName) {
        if (isset($this->errors[$errorName]))
            return $this->errors[$errorName];
    
        return "";
    }
    
    public function setError($errorName, $errorValue) {
        $this->errors[$errorName] =  Messages::getError($errorValue);
        $this->errorCount++;
    }
    
    public function getErrorCount() {
        return $this->errorCount;
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    abstract public function getParameters();
    
    abstract public function __toString();
    
    abstract protected function initialize();
}
?>