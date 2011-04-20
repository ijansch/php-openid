<?php
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(__FILE__)));

error_reporting(E_ALL | E_STRICT);

/**
 * Utilites for test functions
 */

function Tests_datafile($name, $reader) {
	$caller = debug_backtrace();
	$filename = implode( DIRECTORY_SEPARATOR, array(
	dirname(realpath($caller[1]['file'])), 'data', $name ));
	$data = $reader($filename);
	if ($data === false) {
		$msg = "Failed to open data file: $name";
		trigger_error($msg, E_USER_ERROR);
	}
	return $data;
}

function Tests_readdata($name) {
	return Tests_datafile($name, 'file_get_contents');
}

function Tests_readlines($name) {
	return Tests_datafile($name, 'file');
}

function __autoload($class_name) {
	switch($class_name){
		case 'OpenIDTestMixin':
			class OpenIDTestMixin extends PHPUnit_Framework_TestCase {
				function failUnlessOpenIDValueEquals($msg, $key, $expected, $ns=null) {
					if ($ns === null) {
						$ns = Auth_OpenID_OPENID_NS;
					}

					$actual = $msg->getArg($ns, $key);
					$error_format = 'Wrong value for openid.%s: expected=%s, actual=%s';
					$error_message = sprintf($error_format,
					$key, $expected, $actual);

					$this->assertEquals($expected, $actual, $error_message);
				}

				function failIfOpenIDKeyExists($msg, $key, $ns=null) {
					if ($ns === null) {
						$ns = Auth_OpenID_OPENID_NS;
					}

					$actual = $msg->getArg($ns, $key);
					$error_message = sprintf('openid.%s unexpectedly present: %s',
					$key, $actual);

					$this->assertFalse($msg->hasKey($ns, $key),
					$error_message);
				}

				function failUnlessHasIdentifiers($msg, $op_specific_id, $claimed_id) {
					$this->failUnlessOpenIDValueEquals($msg, 'identity', $op_specific_id);
					$this->failUnlessOpenIDValueEquals($msg, 'claimed_id', $claimed_id);
				}

			}
			break;
		default:
			trigger_error('undefined '.$class_name,E_USER_ERROR);
	}

}

