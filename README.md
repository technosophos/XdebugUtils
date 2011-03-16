# XdebugUtils

This provides a library and various  command-line utilities for working with Xdebug output.

Scripts included:

 - trace4func: Given a function name, give stack traces for all cases where the function was called.
 - whatisincluded: Find all of the files that were included on a given run
 - whocalls: Given a function, print info about the calling function. For example, find all functions that call `file_exists(_))`.

## Installation

To collect data, you must have Xdebug installed, and you must be able to configure it to generate traces.

To use these tools, you will need PHP 5.2 or later.

To get started:

1. Clone the Git repository
2. Use any of the included command line tools.

## Usage

1. You will need trace file to work with. Consult the Xdebug documentation. Make sure you set the trace output to generate machine-readable output.
2. Run any of the provided scripts to analyze trace output.

## Collecting Data with Xdebug

Example `php.ini` config for Xdebug:

    xdebug.auto_trace = 1
    xdebug.trace_format = 1
    xdebug.trace_output_name = php-trace.%t

The second one is the most important: Set the tracing format to 1.

## More information

You can easily extend this suite. See `API` (included here) to get started, or simply take a look at
some of the tools in this package. Nothing is really that sophisticated.

## Related Projects

  * [ValaXdebugTools](https://github.com/technosophos/ValaXdebugTools) is a very fast trace analyzer.
  * [xdebugtoolkit](http://code.google.com/p/xdebugtoolkit/) produces DOT files to make pretty diagrams of Xdebug trace files.

----
XdebugUtils by mbutcher (2010)

Thanks to Derick Rethans, who [posted a simple PHP tool](http://derickrethans.nl/xdebug-and-tracing-memory-usage.html) for parsing Xdebug trace files.
