<?php

declare(strict_types=1);

namespace MindaugasBudreika\HelloLocalLlm\ProviderImplementations\Docker;

use RuntimeException;
use WordPress\AiClient\Files\Enums\FileTypeEnum;
use WordPress\AiClient\Files\Enums\MediaOrientationEnum;
use WordPress\AiClient\Messages\Enums\ModalityEnum;
use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\DTO\Response;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleModelMetadataDirectory;

/**
 * Class for the Docker model metadata directory.
 */
class DockerModelMetadataDirectory extends AbstractOpenAiCompatibleModelMetadataDirectory {
	/**
	 * Creates a request object for the provider's API.
	 *
	 * @param HttpMethodEnum                     $method  The HTTP method.
	 * @param string                             $path    The API endpoint path, relative to the base URI.
	 * @param array<string, string|list<string>> $headers The request headers.
	 * @param string|array<string, mixed>|null   $data    The request data.
	 *
	 * @return Request The request object.
	 */
	protected function createRequest( HttpMethodEnum $method, string $path, array $headers = array(), $data = null ) : Request {
		return new Request(
			$method,
			DockerProvider::BASE_URI . '/' . ltrim( $path, '/' ),
			$headers,
			$data
		);
	}

	/**
	 * Parses the response from the API endpoint to list models into a list of model metadata objects.
	 *
	 * @param Response $response The response from the API endpoint to list models.
	 *
	 * @return list<ModelMetadata> List of model metadata objects.
	 */
	protected function parseResponseToModelMetadataList( Response $response ) : array {
		/** @var ModelsResponseData $responseData */
		$responseData = $response->getData();
		if ( ! isset( $responseData['data'] ) || ! $responseData['data'] ) {
			throw new RuntimeException(
				'Unexpected API response: Missing the data key.'
			);
		}

		$gptCapabilities = array(
			CapabilityEnum::textGeneration(),
			CapabilityEnum::chatHistory(),
		);

		$gptBaseOptions = array(
			new SupportedOption( OptionEnum::systemInstruction() ),
			new SupportedOption( OptionEnum::candidateCount() ),
			new SupportedOption( OptionEnum::maxTokens() ),
			new SupportedOption( OptionEnum::temperature() ),
			new SupportedOption( OptionEnum::topP() ),
			new SupportedOption( OptionEnum::stopSequences() ),
			new SupportedOption( OptionEnum::presencePenalty() ),
			new SupportedOption( OptionEnum::frequencyPenalty() ),
			new SupportedOption( OptionEnum::logprobs() ),
			new SupportedOption( OptionEnum::topLogprobs() ),
			new SupportedOption( OptionEnum::outputMimeType(), array( 'text/plain', 'application/json' ) ),
			new SupportedOption( OptionEnum::outputSchema() ),
			new SupportedOption( OptionEnum::functionDeclarations() ),
			new SupportedOption( OptionEnum::customOptions() ),
		);

		$gptOptions = array_merge(
			$gptBaseOptions,
			array(
				new SupportedOption( OptionEnum::inputModalities(), array( array( ModalityEnum::text() ) ) ),
				new SupportedOption( OptionEnum::outputModalities(), array( array( ModalityEnum::text() ) ) ),
			)
		);

		$modelsData = (array) $responseData['data'];

		return array_values(
			array_map(
				static function ( array $modelData ) use (
					$gptCapabilities,
					$gptOptions
				): ModelMetadata {
					$modelId = $modelData['id'];

					if ( ! str_contains( $modelId, 'embed' ) ) {
						$modelCaps    = $gptCapabilities;
						$modelOptions = $gptOptions;
					} else {
						$modelCaps    = array();
						$modelOptions = array();
					}

					return new ModelMetadata( $modelId, $modelId, $modelCaps, $modelOptions );
				},
				$modelsData
			)
		);
	}
}
