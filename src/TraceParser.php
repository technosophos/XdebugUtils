<?php
/**
 * @file
 * The main trace parser.
 */

/**
 * This class parses trace files and notifies observers of events.
 */
class TraceParser {
  
  protected $filename;
  protected $observers;
  
  /**
   * Construct a new TraceParser.
   *
   * A trace parser parses an Xdebug function trace (xt) file and notifies
   * observers of events as it parses.
   *
   * @param string $filename
   *  The name of the XT file to parse.
   *
   * @see http://xdebug.org/docs/execution_trace
   */
  public function __construct($filename) {
    $this->filename = $filename;
    $this->observers = array();
  }
  
  /**
   * Register an observer.
   */
  public function register(TraceParserObserver $observer) {
    $this->observers[] = $observer;
  }
  
  /**
   * Parse the file and notify the observers of events.
   */
  public function parse() {
    $handle = fopen($this->filename, 'r');
    
    while ($line = fgets($handle)) {
      $fields = explode("\t", $line);
      
      // Get start/end messages
      $count = count($fields);
      if ($count == 2) {
        $timestamp = $fields[1];
        if (strpos('TRACE END', $fields[0]) === 0) {
          foreach ($this->observers as $o) $o->endEntry($timestamp);
        }
        else {
          foreach ($this->observers as $o) $o->startEntry($timestamp);
        }
      }
      // Otherwise we want enter/exit function traces.
      elseif ($count >= 5) {
        // ENTER function
        if ($fields[2] == "0") {
          foreach ($this->observers as $o) $o->enterFunction(
            (int)$fields[0],
            (int)$fields[1],
            (float)$fields[3],
            (int)$fields[4],
            $fields[5],
            $fields[6] == "0",
            $fields[8],
            (int)$fields[9],
            $fields[7]
          );
        }
        // EXIT function
        else {
          foreach ($this->observers as $o) $o->exitFunction(
            (int)$fields[0],
            (int)$fields[1],
            (float)$fields[3],
            (int)$fields[4]
          );
        }
      }
      
    }
    
    fclose($handle);
  }
}

/**
 * The trace parser observer.
 *
 * Any trace parser observers should implement this interface.
 */
interface TraceParserObserver {
  
  public function startEntry($timestamp);
  public function endEntry($timestamp);
  public function enterFunction($depth, $function_id, $elapsed_time, $memory_consumption, $function_name, $is_internal, $filename, $line, $included_from = NULL);
  public function exitFunction($depth, $function_id, $elapsed_time, $memory_consumption);
  
}

?>