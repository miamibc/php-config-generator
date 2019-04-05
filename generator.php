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

  elseif (substr($item, 0, 1) === '{' && substr($item,-1,1) === '}')
  {
    $vars = array_merge( $vars, json_decode( $item ,true));
  }
  elseif (preg_match('/^([^=]+)=(.*)$/', $item, $matches))
  {
    $vars[$matches[1]] = $matches[2];
  }
  elseif (file_exists( $item ))
  {
    $vars = array_merge( $vars, json_decode( file_get_contents( $item ), true ));
  }
}

// var_dump($vars);

echo("# Made with http://github.com/miamibc/php-config-generator\n");
echo("# ".implode(" ", $argv)."\n");
echo("\n");


// render template
$filename = $argv[1];
if  (stripos($filename, '.twig') !== false )
{
  $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/template');
  $twig = new \Twig\Environment($loader,[
    'cache' => __DIR__.'/cache',
  ]);
  // need to remove 'template/' from the beginning
  $filename = substr( $filename, 9);
  echo $twig->render( $filename , $vars );
}
elseif  ( ($pos = stripos($argv[1], '.blade')) !== false )
{
  $blade = new eftec\bladeone\BladeOne(
    __DIR__ . '/template',
    __DIR__ . '/cache',
    eftec\bladeone\BladeOne::MODE_DEBUG
  );
  $filename = substr($filename, 0, $pos);
  $filename = substr($filename, 9);
  echo $blade->run( $filename , $vars );
}
elseif  (stripos($argv[1], '.mustache') !== false )
{
  $mustache = new Mustache_Engine([
    'cache'=>__DIR__ . '/cache',
  ]);
  $template = $mustache->loadTemplate( file_get_contents( $filename ) );
  echo $template->render( $vars );
}
elseif  (preg_match('|.php$|', $filename) && file_exists($filename) )
{
  extract($vars);
  @include($filename);
}
else {
  echo "# Template not found or unsupported (can be twig, blade, mustache or php).";
  exit(1);
}

