<?php
class WhoCallsStackTrace extends StackTraceForSymbol {
  public function format_internal_function($depth, $function_name, $elapsed_time, $memory_consumption) {
    return $function_name . "\t[INTERNAL]";
  }
  
  public function format_user_function($depth, $function_name, $filename, $line, $elapsed_time, $memory_consumption) {
    return sprintf("%s\t[%s:%d]", $function_name, $filename, $line);
  }
  
  public function enterFunction($depth, $function_id, $elapsed_time, $memory_consumption, $function_name, $is_internal, $filename, $line, $included_from = NULL) {
    
    $msg = $is_internal
      ? $this->format_internal_function( 
        $depth, $function_name, $elapsed_time, $memory_consumption)
      : $this->format_user_function( 
        $depth, $function_name, $filename, $line, $elapsed_time, $memory_consumption);
    
    $this->stack[$depth] = array(
      'name' => $function_name,
      'msg' => $msg,
    );
    
    if ($function_name == $this->watch_for_symbol) {
      
      $last = $this->stack[$depth -1];
      $last_func = $last['name'];
      
      if (empty($this->traces[$last_func])) {
        $this->traces[$last_func]['trace'] = $last['msg'];
        $this->traces[$last_func]['count'] = 1;
      }
      else {
        $this->traces[$last_func]['count'] += 1;
      }
    }
  }
}
?>