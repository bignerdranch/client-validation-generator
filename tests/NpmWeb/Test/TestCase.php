<?php

namespace NpmWeb\Test;

class TestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * @see http://stackoverflow.com/questions/249664/best-practices-to-test-protected-methods-with-phpunit
	 */
	protected static function getMethod($className, $methodName) {
		$class = new \ReflectionClass($className);
		$method = $class->getMethod($methodName);
		$method->setAccessible(true);
		return $method;
	}

}
