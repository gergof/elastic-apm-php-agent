<?php
namespace PhilKra\Tests;

use \PhilKra\Apm;
use \PHPUnit\Framework\TestCase;

/**
 * Test Case for @see \PhilKra\Apm
 */
final class ApmTest extends TestCase {

  /**
   * @covers \PhilKra\Apm::__construct
   * @covers \PhilKra\Apm::getConfig
   * @covers \PhilKra\Apm::getDefaultConfig
   */
  public function testControlDefaultConfig() {
    $appName = sprintf( 'app_name_%d', rand( 10, 99 ) );
    $agent = new Apm( [ 'appName' => $appName ] );

    // Control Default Config
    $config = $agent->getConfig();

    $this->assertArrayHasKey( 'appName', $config );
    $this->assertArrayHasKey( 'secretToken', $config );
    $this->assertArrayHasKey( 'serverUrl', $config );
    $this->assertArrayHasKey( 'hostname', $config );
    $this->assertArrayHasKey( 'timeout', $config );

    $this->assertEquals( $config['appName'], $appName );
    $this->assertNull( $config['secretToken'] );
    $this->assertEquals( $config['serverUrl'], 'http://127.0.0.1:8200' );
    $this->assertEquals( $config['hostname'], gethostname() );
    $this->assertEquals( $config['timeout'], 5 );
  }

  /**
   * @depends testControlDefaultConfig
   *
   * @covers \PhilKra\Apm::__construct
   * @covers \PhilKra\Apm::getConfig
   * @covers \PhilKra\Apm::getDefaultConfig
   */
  public function testControlInjectedConfig() {
    $init = [
      'appName'       => sprintf( 'app_name_%d', rand( 10, 99 ) ),
      'secretToken'   => hash( 'tiger128,3', time() ),
      'serverUrl'     => sprintf( 'https://node%d.domain.tld:%d', rand( 10, 99 ), rand( 1000, 9999 ) ),
      'appVersion'    => sprintf( '%d.%d.42', rand( 0, 3 ), rand( 0, 10 ) ),
      'frameworkName' => uniqid(),
      'timeout'       => rand( 10, 20 ),
      'hostname'      => sprintf( 'host_%d', rand( 0, 9 ) ),
    ];

    $agent = new Apm( $init );

    // Control Default Config
    $config = $agent->getConfig();
    foreach( $init as $key => $value ) {
        $this->assertEquals( $config[$key], $init[$key], 'key: ' . $key );
    }
  }

}
