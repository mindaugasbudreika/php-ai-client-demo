<?php
use WordPress\AiClient\AiClient;
use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;
use MindaugasBudreika\HelloLocalLlm\ProviderImplementations\Docker\DockerProvider;
?>
<p <?php echo get_block_wrapper_attributes() ?>>
	<?php
	$registry = AiClient::defaultRegistry();

	// Register a new provider.
	$registry->registerProvider( DockerProvider::class );

	// Docker does not require a token, so anything will do.
	$registry->setProviderRequestAuthentication(
		'docker',
		new ApiKeyRequestAuthentication( 'SAVE FERRIS' )
	);

	// Prompt the local LLM!
	$response = AiClient::prompt( 'Provide fact about the ocean.' )
		// ->usingModel( DockerProvider::model( 'ai/llama3.2:latest' ) )
		->generateText();

	echo esc_html( $response );
	?>
</p>