<?php declare(strict_types=1);

namespace Tests\Torr\BundleHelpers\Bundle;

use PHPUnit\Framework\TestCase;
use Tests\Torr\BundleHelpers\Fixtures\ExampleBundle;
use Tests\Torr\BundleHelpers\Fixtures\OtherExample;
use Torr\BundleHelpers\Bundle\BundleExtension;
use Torr\BundleHelpers\Exception\BundleHelpersException;

final class BundleExtensionTest extends TestCase
{
	/**
	 *
	 */
	public function testAliasGeneration () : void
	{
		$extension = new BundleExtension(new ExampleBundle());
		$extensionOverwritten = new BundleExtension(new ExampleBundle(), "overwritten");

		self::assertSame("example", $extension->getAlias());
		self::assertSame("overwritten", $extensionOverwritten->getAlias());
	}

	/**
	 *
	 */
	public function testInvalidAliasGeneration () : void
	{
		$this->expectException(BundleHelpersException::class);
		$this->expectExceptionMessage("The bundle does not follow the naming convention; you must pass an explicit alias.");

		$extension = new BundleExtension(new OtherExample());
		$extension->getAlias();
	}
}
