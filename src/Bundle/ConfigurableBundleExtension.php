<?php declare(strict_types=1);

namespace Torr\BundleHelpers\Bundle;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Base bundle extension, that receives the parsed config + container builder and can modify
 * the container accordingly.
 */
class ConfigurableBundleExtension extends BundleExtension
{
	private ConfigurationInterface $config;

	/** @var callable(array, ContainerBuilder): void */
	private $callback;

	/**
	 * @param callable(array, ContainerBuilder): void $callback
	 */
	public function __construct (
		BundleInterface $bundle,
		ConfigurationInterface $config,
		callable $callback,
		?string $alias = null,
	)
	{
		parent::__construct($bundle, $alias);
		$this->config = $config;
		$this->callback = $callback;
	}

	/**
	 * @inheritDoc
	 */
	public function load (array $configs, ContainerBuilder $container) : void
	{
		parent::load($configs, $container);

		$config = $this->processConfiguration($this->config, $configs);
		($this->callback)($config, $container);
	}
}
