<?php

use Laravel\Routing\Router;

class URLTest extends PHPUnit_Framework_TestCase {

	/**
	 * Setup the test enviornment.
	 */
	public function setUp()
	{
		Config::set('application.url', 'http://localhost');
	}

	/**
	 * Destroy the test enviornment.
	 */
	public function tearDown()
	{
		$_SERVER = array();
		Router::$names = array();
		Router::$routes = array();
		Config::set('application.ssl', true);
		Config::set('application.url', '');
		Config::set('application.index', 'index.php');
	}

	/**
	 * Test the URL::to method.
	 *
	 * @group laravel
	 */
	public function testToMethodGeneratesURL()
	{
		$this->assertEquals('http://localhost/index.php/user/profile', URL::to('user/profile'));
		$this->assertEquals('https://localhost/index.php/user/profile', URL::to('user/profile', true));

		Config::set('application.index', '');

		$this->assertEquals('http://localhost/user/profile', URL::to('user/profile'));
		$this->assertEquals('https://localhost/user/profile', URL::to('user/profile', true));

		Config::set('application.ssl', false);

		$this->assertEquals('http://localhost/user/profile', URL::to('user/profile', true));
	}

	/**
	 * Test the URL::to_action method.
	 *
	 * @group laravel
	 */
	public function testToActionMethodGeneratesURLToControllerAction()
	{
		$this->assertEquals('http://localhost/index.php/home/index/', URL::to_action('home@index'));
		$this->assertEquals('http://localhost/index.php/home/index/Taylor', URL::to_action('home@index', array('Taylor')));
	}

	/**
	 * Test the URL::to_asset method.
	 *
	 * @group laravel
	 */
	public function testToAssetGeneratesURLWithoutFrontControllerInURL()
	{
		$this->assertEquals('http://localhost/image.jpg', URL::to_asset('image.jpg'));
		$this->assertEquals('https://localhost/image.jpg', URL::to_asset('image.jpg', true));

		Config::set('application.index', '');

		$this->assertEquals('http://localhost/image.jpg', URL::to_asset('image.jpg'));
		$this->assertEquals('https://localhost/image.jpg', URL::to_asset('image.jpg', true));

		$_SERVER['HTTPS'] = 'on';

		$this->assertEquals('https://localhost/image.jpg', URL::to_asset('image.jpg'));
	}

	/**
	 * Test the URL::to_route method.
	 *
	 * @group laravel
	 */
	public function testToRouteMethodGeneratesURLsToRoutes()
	{
		Router::register('GET /url/test', array('name' => 'url-test'));
		Router::register('GET /url/test/(:any)/(:any?)', array('name' => 'url-test-2'));

		$this->assertEquals('http://localhost/index.php/url/test', URL::to_route('url-test'));
		$this->assertEquals('https://localhost/index.php/url/test', URL::to_route('url-test', array(), true));
		$this->assertEquals('http://localhost/index.php/url/test/taylor', URL::to_route('url-test-2', array('taylor')));
		$this->assertEquals('http://localhost/index.php/url/test/taylor/otwell', URL::to_route('url-test-2', array('taylor', 'otwell')));
	}

}