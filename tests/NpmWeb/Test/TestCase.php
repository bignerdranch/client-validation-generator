<?php

namespace NpmWeb\Test;

class TestCase extends \PHPUnit_Framework_TestCase {

	protected static function getMethod($className, $methodName) {
		$class = new \ReflectionClass($className);
		$method = $class->getMethod($methodName);
		$method->setAccessible(true);
		return $method;
	}

}
