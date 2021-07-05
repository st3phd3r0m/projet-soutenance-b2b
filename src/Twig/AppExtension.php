<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;

use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('fileGetContents', [$this, 'fileGetContents']),
        ];
    }

    public function fileGetContents($file)
    {
      return file_get_contents($file);
    }
  
    public function getName()
    {
      return 'app_extension';
    }
}