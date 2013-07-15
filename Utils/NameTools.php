<?php

namespace Xsolve\UnitSkelgenBundle\Utils;

use SplFileInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NameTools
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function getRealPath($filename)
    {
        $fileInfo = new SplFileInfo($filename);
        return $fileInfo->getRealPath();
    }
    
    public function createQualifiedClassName($filename)
    {
        $filenameWithoutExt = preg_replace('/\.php$/', '', $filename);
        $filenameWithoutSrc = str_replace($this->getSourceDir(), '', $filenameWithoutExt);
        $taintedClassName = str_replace('/', '\\', $filenameWithoutSrc);
        return preg_replace('/\\+/', '\\', $taintedClassName);
    }
    
    public function createTestFilename($filename)
    {
        $matches = array();
        preg_match('/[^\/]Bundle\//', $filename, $matches);
        $lastMatch = end($matches);
        $filenameWithDir = str_replace($lastMatch, $lastMatch . 'Tests/', $filename);
        return preg_replace('/\.php$/i', 'Test.php', $filenameWithDir);
    }
    
    public function getSourceDir()
    {
        $rootDir = $this->container->getParameter('kernel.root_dir');
        return $this->getRealPath($rootDir . '/../src');
    }
}