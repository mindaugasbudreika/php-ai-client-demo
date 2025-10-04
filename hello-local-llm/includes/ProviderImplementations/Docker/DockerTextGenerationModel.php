<?php

declare(strict_types=1);

namespace MindaugasBudreika\HelloLocalLlm\ProviderImplementations\Docker;

use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleTextGenerationModel;

/**
 * Class for an OpenAI text generation model.
 */
class DockerTextGenerationModel extends AbstractOpenAiCompatibleTextGenerationModel {
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
}
