<?php
/**
 *
 * @package php-config-generator
 * @author Sergei Miami <miami@blackcrystal.net>
 */

require_once dirname(__FILE__) . '/vendor/autoload.php';


// initial vars
$vars = [];

// add vars from command line
foreach ($argv as $i=>$item)
{
  // skip first arguments
  if ($i < 2) continue;
  // merge {json}
  elseif (substr($item, 0, 1) === '{' && substr($item,-1,1) === '}')
  {
    $vars = array_merge( $vars, json_decode( $item ,true));
  }
  // merge file
  elseif (file_exists( $item ))
  {
    $vars = array_merge( $vars, json_decode( file_get_contents( $item ), true ));
  }
}

var_dump($vars);

echo("# Made with <3 using http://github.com/miamibc/php-config-generator\n");
echo("# ".implode(" ", $argv)."\n\n");

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/template');
$twig = new \Twig\Environment($loader, [
  'cache' => __DIR__ . '/cache',
]);

echo $twig->render( $argv[1], $vars);