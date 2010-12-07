<?php
class StackTraceForSymbol implements TraceParserObserver {
  
  /**
   * The call stack.
   */
  protected $stack;
  /**
   * The symbol we are watching for.
   */
  protected $watch_for_symbol;
  /**
   * The list of function ids that match the symbol we're watching.
   */
  protected $symbol_ids;
  
  protected $traces;
  
  protected $user_format = '%d. %s [ %s:%d ] Time: %0.4f, Size: %d';
  protected $internal_format = '%d. %s [ BUILT-IN ] Time: %0.4f, Size: %d';
  
  public function __construct($symbol) {
    $this->watch_for_symbol = $symbol;
  }
  
  public function startEntry($timestamp) {
    $this->stack = array();
    $this->symbol_ids = array();
    $this->traces = array();
  }
  public function endEntry($timestamp) {
    unset($this->stack);
  }
  
  public function enterFunction($depth, $function_id, $elapsed_time, $memory_consumption, $function_name, $is_internal, $filename, $line, $included_from = NULL) {
    
    if ($is_internal) {
      $this->stack[$depth] = $this->format_internal_function( 
        $depth, $function_name, $elapsed_time, $memory_consumption);
    }
    else {
      $this->stack[$depth] = $this->format_user_function( 
        $depth, $function_name, $filename, $line, $elapsed_time, $memory_consumption);
    }
    
    
    if ($function_name == $this->watch_for_symbol) {
      $trace = array_slice($this->stack, 0, $depth);
      $this->traces[] = array_reverse($trace);
    }
    
  }
  
  public function format_internal_function($depth, $function_name, $elapsed_time, $memory_consumption) {
    return sprintf($this->internal_format, 
      $depth, $function_name, $elapsed_time, $memory_consumption);
  }
  
  public function format_user_function($depth, $function_name, $filename, $line, $elapsed_time, $memory_consumption) {
    return sprintf($this->user_format, 
      $depth, $function_name, $filename, $line, $elapsed_time, $memory_consumption);
  }
  
  public function exitFunction($depth, $function_id, $elapsed_time, $memory_consumption) {
    // Waste of resources.
    //unset($this->stack[$depth])
  }
  
  public function getReport() {
    return $this->traces;
  }
  
}