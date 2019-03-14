<?php
/**
 *
 * @package php-config-generator
 * @author Sergei Miami <miami@blackcrystal.net>
 */

require_once dirname(__FILE__) . '/vendor/autoload.php';


// initial vars
$vars = [];

// load vars from default.ini, if exists
if ( file_exists( $filename = __DIR__ . '/defaults.json'))
{
  $vars = json_decode(file_get_contents( $filename ), true);
}

// add vars from directory/default.ini, if exists
if ( file_exists( $filename = "template/" . dirname($argv[1]) . '/defaults.json') )
{
  $vars = array_merge( $vars, json_decode(file_get_contents( $filename ), true ));
}

// add vars from ini file, if exists
if ( file_exists( $filename = "template/$argv[1].json") )
{
  $vars = array_merge( $vars, json_decode( file_get_contents( $filename ), true ));
}

// add vars from command line
if (isset($argv[2]))
{
  $vars = array_merge( $vars, json_decode( $argv[2], true ));
}

// var_dump($vars);

echo("# Made with <3 using http://github.com/miamibc/php-config-generator\n");
echo("# ".implode(" ", $argv)."\n\n");

$blade = new eftec\bladeone\BladeOne(
  __DIR__ . '/template',
  __DIR__ . '/cache',
  eftec\bladeone\BladeOne::MODE_AUTO
);
try {
  echo $blade->run($argv[1], $vars );
}
catch (Exception $e) {
  echo "Template not found";
  exit(1);
}