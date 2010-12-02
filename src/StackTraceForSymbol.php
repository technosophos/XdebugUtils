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
  
  protected $trace_msg_format = '%s in %s (%d) Time: %0.4f, Size: %d';
  
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
  
  public function enterFunction($function_id, $depth, $elapsed_time, $memory_consumption, $function_name, $is_internal, $filename, $line, $included_from = NULL) {
    
    $this->stack[$depth] = sprintf($this->trace_msg_format, 
      $function_name, $filename, $line, $elapsed_time, $memory_consumtion);
    
    if ($function_name == $this->watch_for_symbol) {
      $trace = array_slice($this->stack, 0, $depth);
      $this->traces[] = array_reverse($trace);
    }
    
  }
  
  public function exitFunction($function_id, $depth, $elapsed_time, $memory_consumption) {
    // Waste of resources.
    //unset($this->stack[$depth])
  }
  
  public function getReport() {
    return $this->traces;
  }
  
}