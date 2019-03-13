<?php
/**
 *
 * @package php-config-generator
 * @author Sergei Miami <miami@blackcrystal.net>
 */

require_once dirname(__FILE__) . '/vendor/autoload.php';



if (!file_exists($filename = "$argv[1].mustache") )
{
  echo "$filename not found";
  exit(1);
}

$mustache = new Mustache_Engine;
$template = $mustache->loadTemplate( file_get_contents( $filename ) );

// initial vars
$vars = [];

// load vars from default.ini, if exists
if ( file_exists( $filename = __DIR__ . '/defaults.ini'))
{
  $vars = parse_ini_file($filename);
}

// add vars from directory/default.ini, if exists
if ( file_exists( $filename = dirname($filename) . '/defaults.ini') )
{
  $vars = array_merge( $vars, parse_ini_file( $filename ));
}

// add vars from ini file, if exists
if ( file_exists( $filename = "$argv[1].ini") )
{
  $vars = array_merge( $vars, parse_ini_file( $filename ));
}

// add vars from command line
for ($i = 2; $i < $argc; $i++)
  if (preg_match('/^([^=]+)=(.*)$/', $argv[$i], $match))
    $vars[$match[1]] = $match[2];

// var_dump($vars);

echo("# Made with <3 using http://github.com/miamibc/php-config-generator\n");
echo("# ".implode(" ", $argv)."\n\n");
echo($template->render( $vars ));