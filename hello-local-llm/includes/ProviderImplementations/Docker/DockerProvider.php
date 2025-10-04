<?php

namespace MindaugasBudreika\HelloLocalLlm\ProviderImplementations\Docker;

use RuntimeException;
use WordPress\AiClient\Providers\AbstractProvider;
use WordPress\AiClient\Providers\ApiBasedImplementation\ListModelsApiBasedProviderAvailability;
use WordPress\AiClient\Providers\Contracts\ModelMetadataDirectoryInterface;
use WordPress\AiClient\Providers\Contracts\ProviderAvailabilityInterface;
use WordPress\AiClient\Providers\DTO\ProviderMetadata;
use WordPress\AiClient\Providers\Enums\ProviderTypeEnum;
use WordPress\AiClient\Providers\Models\Contracts\ModelInterface;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;

/**
 * Class for the Docker provider.
 */
class DockerProvider extends AbstractProvider {
	/**
	 * Base URL for DMR when accessing models from containers.
	 *
	 * @see https://docs.docker.com/ai/model-runner/api-reference/
	 */
	public const BASE_URI = 'http://model-runner.docker.internal/engines/llama.cpp/v1';

	/**
	 * Creates a model instance based on the given model metadata and provider metadata.
	 *
	 * @param ModelMetadata    $modelMetadata    The model metadata.
	 * @param ProviderMetadata $providerMetadata The provider metadata.
	 *
	 * @return ModelInterface The new model instance.
	 */
	protected static function createModel(
		ModelMetadata $modelMetadata,
		ProviderMetadata $providerMetadata
	) : ModelInterface {
		$capabilities = $modelMetadata->getSupportedCapabilities();

		foreach ( $capabilities as $capability ) {
			if ( $capability->isTextGeneration() ) {
				return new DockerTextGenerationModel( $modelMetadata, $providerMetadata );
			}
		}

		throw new RuntimeException(
			'Unsupported model capabilities: ' . implode( ', ', $capabilities )
		);
	}

	/**
	 * Creates the provider metadata instance.
	 *
	 * @return ProviderMetadata The provider metadata.
	 */
	protected static function createProviderMetadata() : ProviderMetadata {
		return new ProviderMetadata(
			'docker',
			'Docker Model Runner (DMR)',
			ProviderTypeEnum::cloud()
		);
	}

	/**
	 * Creates the provider availability instance.
	 *
	 * @return ProviderAvailabilityInterface The provider availability.
	 */
	protected static function createProviderAvailability() : ProviderAvailabilityInterface {
		return new ListModelsApiBasedProviderAvailability(
			static::modelMetadataDirectory()
		);
	}

	/**
	 * Creates the model metadata directory instance.
	 *
	 * @return ModelMetadataDirectoryInterface The model metadata directory.
	 */
	protected static function createModelMetadataDirectory() : ModelMetadataDirectoryInterface {
		return new DockerModelMetadataDirectory();
	}
}
