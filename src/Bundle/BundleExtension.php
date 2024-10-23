<?php declare(strict_types=1);

namespace Torr\BundleHelpers\Bundle;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Torr\BundleHelpers\Exception\BundleHelpersException;

/**
 * Base bundle extension, that can be used without needing a custom class.
 */
class BundleExtension extends Extension
{
	private BundleInterface $bundle;
	private ?string $alias;

	public function __construct (
		BundleInterface $bundle,
		?string $alias = null,
	)
	{
		$this->bundle = $bundle;
		$this->alias = $alias;
	}

	/**
	 *
	 */
	#[\Override]
	public function load (array $configs, ContainerBuilder $container) : void
	{
		$configDir = "{$this->bundle->getPath()}/config";

		if (is_file("{$configDir}/services.yaml"))
		{
			$loader = new YamlFileLoader($container, new FileLocator($configDir));
			$loader->load("services.yaml");
		}
	}

	/**
	 *
	 */
	#[\Override]
	public function getAlias () : string
	{
		if (null !== $this->alias)
		{
			return $this->alias;
		}

		$className = \get_class($this->bundle);

		if (!str_ends_with($className, 'Bundle'))
		{
			throw new BundleHelpersException(sprintf(
				"The bundle does not follow the naming convention; you must pass an explicit alias. Its name should end on 'Bundle', but it is '%s'.",
				$className,
			));
		}

		$classBaseNameForNamespacedClass = strrchr($className, '\\');
		$classBaseName = false !== $classBaseNameForNamespacedClass
			? substr($classBaseNameForNamespacedClass, 1, -6)
			: $className;

		return Container::underscore($classBaseName);
	}

	/**
	 *
	 */
	#[\Override]
	public function getConfiguration (array $config, ContainerBuilder $container) : ?ConfigurationInterface
	{
		return null;
	}
}
