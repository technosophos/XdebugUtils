<?php
class WhoCallsStackTrace extends StackTraceForSymbol {
  
  public $longest = 0;

  public function enterFunction($depth, $function_id, $elapsed_time, $memory_consumption, $function_name, $is_internal, $filename, $line, $included_from = NULL) {
    
    /*
    $msg = $is_internal
      ? $this->format_internal_function( 
        $depth, $function_name, $elapsed_time, $memory_consumption)
      : $this->format_user_function( 
        $depth, $function_name, $filename, $line, $elapsed_time, $memory_consumption);
    */
    
    $this->stack[$depth] = array(
      'name' => $function_name,
      'file' => $is_internal ? '[INTERNAL]' : sprintf('[%s:%d]', $filename, $line),
    );
    
    if ($function_name == $this->watch_for_symbol) {
      
      $last = $this->stack[$depth - 1];
      $last_func = $last['name'];
      
      if (strlen($last['name']) > $this->longest) $this->longest = strlen($last['name']);
      
      if (empty($this->traces[$last_func])) {
        $this->traces[$last_func]['name'] = $last['name'];
        $this->traces[$last_func]['file'] = $last['file'];
        $this->traces[$last_func]['count'] = 1;
      }
      else {
        $this->traces[$last_func]['count'] += 1;
      }
    }
  }
  
  public function countComparator($a, $b) {
    return $a['count'] > $b['count'] ? 1 : ($a['count'] > $b['count'] ? -1 : 0);
  }
}
?>