<?php
class WhoCallsStackTrace extends StackTraceForSymbol {
  public function format_internal_function($depth, $function_name, $elapsed_time, $memory_consumption) {
    return $function_name . ' [INTERNAL]';
  }
  
  public function format_user_function($depth, $function_name, $filename, $line, $elapsed_time, $memory_consumption) {
    return sprintf("%s [%s:%d]", $function_name, $filename, $line);
  }
}
?>