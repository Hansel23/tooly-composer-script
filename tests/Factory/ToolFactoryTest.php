<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Factory;

use Hansel23\Tooly\Factory\ToolFactory;
use Hansel23\Tooly\Model\Tool;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ToolFactoryTest extends TestCase
{
	public function testCanCreateATool(): void
	{
		vfsStream::setup();

		$tool = ToolFactory::createTool( 'test', 'vfs://root', [
			'url'           => 'my-url',
			'sign-url'      => 'my-sign-url',
			'force-replace' => true,
			'only-dev'      => false,
			'rename'        => true,
			'fallback-url'  => 'fallback-url',
		] );

		$this->assertInstanceOf( Tool::class, $tool );
		$this->assertEquals( 'my-url', $tool->getUrl() );
		$this->assertEquals( 'my-sign-url', $tool->getSignUrl() );
		$this->assertTrue( $tool->forceReplace() );
		$this->assertFalse( $tool->isOnlyDev() );
		$this->assertTrue( $tool->renameToConfigKey() );
		$this->assertEquals( 'fallback-url', $tool->getFallbackUrl() );
	}

	public function testCanCreateMultipleTools(): void
	{
		vfsStream::setup();

		$data = [
			'tool-1' => [
				'url' => 'my-tool-1-url',
			],
			'tool-2' => [
				'url' => 'my-tool-2-url',
			],
		];

		$tools = ToolFactory::createTools( 'vfs://root', $data );
		$this->assertCount( 2, $tools );
	}
}
