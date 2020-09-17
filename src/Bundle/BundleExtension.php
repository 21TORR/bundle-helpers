<?php declare(strict_types=1);

namespace Torr\BundleHelpers\Bundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Base bundle extension, that can be used without needing a custom class.
 */
class BundleExtension extends Extension
{
	private BundleInterface $bundle;
	private ?string $alias;


	public function __construct (
		BundleInterface $bundle,
		?string $alias = null
	)
	{
		$this->bundle = $bundle;
		$this->alias = $alias;
	}


	/**
	 * @inheritDoc
	 */
	public function load (array $configs, ContainerBuilder $container) : void
	{
		$configDir = "{$this->bundle->getPath()}/config";

		if (\is_file("{$configDir}/services.yaml"))
		{
			$loader = new YamlFileLoader($container, new FileLocator($configDir));
			$loader->load("services.yaml");
		}
	}


	/**
	 * @inheritDoc
	 */
	public function getAlias () : string
	{
		return $this->alias ?? parent::getAlias();
	}
}
