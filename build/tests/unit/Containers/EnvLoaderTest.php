<?php

namespace Unit\Containers;

use \RuntimeException;
use \PHPUnit_Framework_TestCase as TestCase;
use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Exceptions\UnresolvableException;
use \Xemoe\ConfigurationContainer;

class EnvLoaderTest extends TestCase
{
    public function setUp()
    {
        ConfigurationContainer::clean();
    }

    public function testGetEnv_withLoadEnv_shouldReturnExpectedArray()
    {
        $expected = 'BAR';

        $envfile = sprintf('%s/files/%s', dirname(__FILE__), 'test_env_file');

        $i = ConfigurationContainer::getInstance();
        $i->loadEnv($envfile);
        $result = $i->getEnv('FOO');

        $this->assertEquals($expected, $result);
    }

    public function testLoadEnv_withMissingRequiredEnv_shouldThrowExpectedException()
    {
        $expectedException = RuntimeException::class;
        $this->setExpectedException($expectedException);

        $envfile = sprintf('%s/files/%s', dirname(__FILE__), 'test_env_file');
        $required = ['THIS_IS_REQUIRED_ENV'];

        $i = ConfigurationContainer::getInstance();
        $i->loadEnv($envfile, ['expect' => $required]);
    }
}
