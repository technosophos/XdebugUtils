# XdebugUtils

This provides a library and various  command-line utilities for working with Xdebug output.

## Installation

Instructions on how to install

## Usage

The following commands are provided:

 * `trace4func`: Analyze a trace file looking for all occurrences of some particular function, method, or other recognized symbol. Then print a report of stack traces for each instance of that function. This can be a useful tool for finding out under what conditions a function is being called.

## More information

The library is module (based on an Observer pattern). You can easily build your own utilities that can attach to the parser and run custom reports.

----
XdebugUtils by mbutcher (2010)