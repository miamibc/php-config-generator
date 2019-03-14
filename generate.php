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

echo("# Made with turn http://github.com/miamibc/php-config-generator\n");
echo("# ".implode(" ", $argv)."\n");

// render template
if  (stripos($argv[1], '.twig') !== false )
{
  $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/template');
  $twig = new \Twig\Environment($loader,[
    'cache' => __DIR__.'/cache',
  ]);
  echo $twig->render( $argv[1], $vars );
}
if  ( ($pos = stripos($argv[1], '.blade')) !== false )
{
  $blade = new eftec\bladeone\BladeOne(
    __DIR__ . '/template',
    __DIR__ . '/cache',
    eftec\bladeone\BladeOne::MODE_DEBUG
  );
  echo $blade->run( substr($argv[1], 0, $pos), $vars );
}
