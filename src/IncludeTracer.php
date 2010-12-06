<?php
class IncludeTracer implements TraceParserObserver {
  protected $format = "%d\t%s\t[%s in %s:%d] Time: %0.4f, Size: %d\n";
  protected $counter = 0;
  protected $silent = FALSE;
  
  public function __construct($silent) {
    $this->silent = $silent;
  }
  
  public function enterFunction($depth, $function_id, $elapsed_time, $memory_consumption, $function_name, $is_internal, $filename, $line, $included_from = NULL) {
    
    if (!empty($included_from) && $function_name != 'eval') {
      //print implode("\t", func_get_args()) . PHP_EOL;
      if ($this->silent) {
        print $included_from . PHP_EOL;
      }
      else {
        printf($this->format, ++$this->counter, $included_from, $function_name, $filename, $line, $elapsed_time, $memory_consumption);
      }
      
    }
    
  }
  
  public function startEntry($timestamp){}
  public function endEntry($timestamp){}
  public function exitFunction($depth, $function_id, $elapsed_time, $memory_consumption){}
}
?>