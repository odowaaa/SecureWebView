<?php
/**
 * The `sf/generate-article` WordPress Ability: generates a complete,
 * publication-ready draft article (title, full body content, excerpt, and
 * SEO metadata) from a topic. Never writes to the database — it only
 * returns a structured result for a human to review before anything is
 * saved as a draft.
 *
 * Built on the same Abstract_Ability base class, wp_ai_client_prompt()
 * helper, and Connector-routed model preference used by the official
 * "AI" plugin's own Title Generation / Content Resizing abilities, so it
 * reuses the already-configured Anthropic (or other) connector rather than
 * implementing a second credential system.
 *
 * @package SomaliFocusAiWriter
 */

declare( strict_types=1 );

namespace SomaliFocus\AiWriter;

use WP_Error;
use WordPress\AI\Abstracts\Abstract_Ability;
use WordPress\AiClient\Providers\Http\Exception\ClientException;
use WordPress\AiClient\Providers\Http\Exception\NetworkException;
use WordPress\AiClient\Providers\Http\Exception\ResponseException;
use WordPress\AiClient\Providers\Http\Exception\ServerException;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Article generation WordPress Ability.
 *
 * @since 1.0.0
 */
class Article_Generation_Ability extends Abstract_Ability {

	/**
	 * Word-count target and output token budget per length tier.
	 *
	 * Token budgets are set well above the target word count to leave
	 * headroom for JSON structure, WordPress block markup, and the lower
	 * tokens-per-word efficiency of Somali compared to English.
	 *
	 * @since 1.0.0
	 *
	 * @var array<string, array{min: int, max: int, tokens: int}>
	 */
	private const LENGTH_TIERS = array(
		'short'         => array(
			'min'    => 400,
			'max'    => 600,
			'tokens' => 2200,
		),
		'standard'      => array(
			'min'    => 800,
			'max'    => 1200,
			'tokens' => 3800,
		),
		'detailed'      => array(
			'min'    => 1500,
			'max'    => 2200,
			'tokens' => 6000,
		),
		'comprehensive' => array(
			'min'    => 2500,
			'max'    => 3500,
			'tokens' => 8500,
		),
	);

	/**
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 */
	protected function guideline_categories(): array {
		return array( 'site', 'copy' );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 */
	protected function input_schema(): array {
		return array(
			'type'       => 'object',
			'required'   => array( 'topic' ),
			'properties' => array(
				'topic'               => array(
					'type'        => 'string',
					'minLength'   => 3,
					'description' => __( 'The subject of the article to write.', 'somalifocus-ai-writer' ),
				),
				'language'            => array(
					'type'        => 'string',
					'enum'        => array( 'en', 'so', 'mixed' ),
					'default'     => 'en',
					'description' => __( 'en = English, so = Somali, mixed = Somali with natural English technical terms.', 'somalifocus-ai-writer' ),
				),
				'length'              => array(
					'type'        => 'string',
					'enum'        => array( 'short', 'standard', 'detailed', 'comprehensive' ),
					'default'     => 'standard',
					'description' => __( 'Target article length tier.', 'somalifocus-ai-writer' ),
				),
				'audience'            => array(
					'type'        => 'string',
					'description' => __( 'Optional. Overrides the default SomaliFocus audience description.', 'somalifocus-ai-writer' ),
				),
				'keywords'            => array(
					'type'        => 'string',
					'description' => __( 'Optional. Comma-separated target keywords/phrases to consider (not to stuff).', 'somalifocus-ai-writer' ),
				),
				'additional_context'  => array(
					'type'        => 'string',
					'description' => __( 'Optional. Any extra facts, constraints, or instructions the writer should take into account.', 'somalifocus-ai-writer' ),
				),
			),
		);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 */
	protected function output_schema(): array {
		return array(
			'type'       => 'object',
			'properties' => array(
				'title'                      => array(
					'type'        => 'string',
					'description' => __( 'Generated article title.', 'somalifocus-ai-writer' ),
				),
				'content'                    => array(
					'type'        => 'string',
					'description' => __( 'Generated article body as WordPress block editor markup, suitable for direct use as post_content.', 'somalifocus-ai-writer' ),
				),
				'excerpt'                    => array(
					'type'        => 'string',
					'description' => __( 'Generated post excerpt.', 'somalifocus-ai-writer' ),
				),
				'seo_title'                  => array(
					'type'        => 'string',
					'description' => __( 'Suggested SEO title (<=60 characters).', 'somalifocus-ai-writer' ),
				),
				'meta_description'           => array(
					'type'        => 'string',
					'description' => __( 'Suggested meta description (<=155 characters).', 'somalifocus-ai-writer' ),
				),
				'focus_keyword'              => array(
					'type'        => 'string',
					'description' => __( 'Suggested primary focus keyword.', 'somalifocus-ai-writer' ),
				),
				'related_keywords'           => array(
					'type'        => 'array',
					'items'       => array( 'type' => 'string' ),
					'description' => __( 'Suggested related keywords.', 'somalifocus-ai-writer' ),
				),
				'categories'                 => array(
					'type'        => 'array',
					'items'       => array( 'type' => 'string' ),
					'description' => __( 'Suggested category names (not yet matched to existing terms).', 'somalifocus-ai-writer' ),
				),
				'tags'                       => array(
					'type'        => 'array',
					'items'       => array( 'type' => 'string' ),
					'description' => __( 'Suggested tag names (not yet matched to existing terms).', 'somalifocus-ai-writer' ),
				),
				'image_alt_text_suggestions' => array(
					'type'        => 'array',
					'items'       => array( 'type' => 'string' ),
					'description' => __( 'Suggested alt text for images that would suit this article.', 'somalifocus-ai-writer' ),
				),
				'language_used'              => array(
					'type'        => 'string',
					'description' => __( 'The language actually used (en, so, or mixed).', 'somalifocus-ai-writer' ),
				),
				'content_notice'             => array(
					'type'        => 'string',
					'description' => __( 'Notes for the human editor on anything to double-check before publishing.', 'somalifocus-ai-writer' ),
				),
				'quality_warnings'           => array(
					'type'        => 'array',
					'items'       => array( 'type' => 'string' ),
					'description' => __( 'Automated quality-check warnings (e.g. an under-developed section). Empty when no issues were detected.', 'somalifocus-ai-writer' ),
				),
			),
		);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 */
	protected function execute_callback( $input ) {
		$args = wp_parse_args(
			$input,
			array(
				'topic'              => '',
				'language'           => 'en',
				'length'             => 'standard',
				'audience'           => '',
				'keywords'           => '',
				'additional_context' => '',
			)
		);

		$topic = trim( (string) $args['topic'] );
		if ( '' === $topic ) {
			return new WP_Error(
				'topic_required',
				__( 'A topic is required to generate an article.', 'somalifocus-ai-writer' )
			);
		}

		$length = isset( self::LENGTH_TIERS[ $args['length'] ] ) ? $args['length'] : 'standard';
		$tier   = self::LENGTH_TIERS[ $length ];

		$language = in_array( $args['language'], array( 'en', 'so', 'mixed' ), true ) ? $args['language'] : 'en';

		$result = $this->generate_article(
			$topic,
			$language,
			$length,
			$tier,
			(string) $args['audience'],
			(string) $args['keywords'],
			(string) $args['additional_context']
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return $result;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 */
	protected function permission_callback( $args ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return new WP_Error(
				'insufficient_capabilities',
				__( 'You do not have permission to generate articles.', 'somalifocus-ai-writer' )
			);
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 */
	protected function meta(): array {
		return array(
			'show_in_rest' => true,
		);
	}

	/**
	 * Runs the actual generation request and turns the result (or any
	 * failure) into either a structured array or a specific, human-readable
	 * WP_Error — never a silently-saved partial article, since this method
	 * never writes to the database at all.
	 *
	 * @since 1.0.0
	 *
	 * @param string                          $topic               The topic.
	 * @param string                          $language            'en'|'so'|'mixed'.
	 * @param string                          $length              Length tier key.
	 * @param array{min:int,max:int,tokens:int} $tier              Resolved tier data.
	 * @param string                          $audience            Optional audience override.
	 * @param string                          $keywords            Optional target keywords.
	 * @param string                          $additional_context  Optional extra instructions.
	 * @return array<string, mixed>|WP_Error
	 */
	protected function generate_article( string $topic, string $language, string $length, array $tier, string $audience, string $keywords, string $additional_context ) {
		$prompt_builder = $this->get_prompt_builder( $topic, $language, $length, $tier, $audience, $keywords, $additional_context );

		if ( is_wp_error( $prompt_builder ) ) {
			return $prompt_builder;
		}

		try {
			$raw_text = $prompt_builder->generate_text();
		} catch ( ClientException $e ) {
			return $this->map_client_exception( $e );
		} catch ( ServerException $e ) {
			return new WP_Error(
				'provider_unavailable',
				__( 'The AI provider is temporarily unavailable (server error). Please try again shortly.', 'somalifocus-ai-writer' ),
				array( 'details' => $e->getMessage() )
			);
		} catch ( NetworkException $e ) {
			return new WP_Error(
				'network_error',
				__( 'Could not reach the AI provider. This may be a timeout or a network connectivity issue — please try again.', 'somalifocus-ai-writer' ),
				array( 'details' => $e->getMessage() )
			);
		} catch ( ResponseException $e ) {
			return new WP_Error(
				'invalid_provider_response',
				__( 'The AI provider returned a response WordPress could not understand.', 'somalifocus-ai-writer' ),
				array( 'details' => $e->getMessage() )
			);
		} catch ( \Throwable $e ) {
			if ( function_exists( 'error_log' ) ) {
				error_log( sprintf( 'SomaliFocus AI Writer: article generation failed: %s', $e->getMessage() ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
			return new WP_Error(
				'generation_failed',
				__( 'Article generation failed unexpectedly. Please try again; if this keeps happening, check the AI Request Logging screen for details.', 'somalifocus-ai-writer' )
			);
		}

		if ( empty( $raw_text ) || ! is_string( $raw_text ) ) {
			return new WP_Error(
				'empty_response',
				__( 'The AI provider returned an empty response. Please try again.', 'somalifocus-ai-writer' )
			);
		}

		$decoded = json_decode( trim( $raw_text ), true );

		if ( null === $decoded || ! is_array( $decoded ) ) {
			return new WP_Error(
				'invalid_response',
				__( 'The AI provider\'s response could not be parsed as a complete article. Please try again, or try a shorter length setting.', 'somalifocus-ai-writer' )
			);
		}

		$title   = isset( $decoded['title'] ) ? trim( (string) $decoded['title'] ) : '';
		$content = isset( $decoded['content'] ) ? trim( (string) $decoded['content'] ) : '';

		if ( '' === $title || '' === $content ) {
			return new WP_Error(
				'incomplete_article',
				__( 'The AI response was missing a title or body content. Please try again.', 'somalifocus-ai-writer' )
			);
		}

		$quality_warnings = $this->analyze_quality( $content, $tier );

		// A severely thin result (well under the requested tier, or every
		// section empty) is treated as a hard failure rather than a
		// silently-returned partial article — the admin must not be handed
		// something that only looks complete.
		$word_count = $this->count_words( $content );
		if ( $word_count < (int) ceil( $tier['min'] * 0.3 ) ) {
			return new WP_Error(
				'incomplete_article',
				sprintf(
					/* translators: 1: word count generated, 2: minimum expected word count */
					__( 'The generated article is too short to be usable (%1$d words generated, expected at least %2$d). This usually means the response was cut off. Try again, or choose a shorter length setting.', 'somalifocus-ai-writer' ),
					$word_count,
					$tier['min']
				)
			);
		}

		return array(
			'title'                      => sanitize_text_field( $title ),
			'content'                    => $content,
			'excerpt'                    => isset( $decoded['excerpt'] ) ? sanitize_textarea_field( (string) $decoded['excerpt'] ) : '',
			'seo_title'                  => isset( $decoded['seo_title'] ) ? sanitize_text_field( (string) $decoded['seo_title'] ) : '',
			'meta_description'           => isset( $decoded['meta_description'] ) ? sanitize_textarea_field( (string) $decoded['meta_description'] ) : '',
			'focus_keyword'              => isset( $decoded['focus_keyword'] ) ? sanitize_text_field( (string) $decoded['focus_keyword'] ) : '',
			'related_keywords'           => $this->sanitize_string_list( $decoded['related_keywords'] ?? array() ),
			'categories'                 => $this->sanitize_string_list( $decoded['categories'] ?? array() ),
			'tags'                       => $this->sanitize_string_list( $decoded['tags'] ?? array() ),
			'image_alt_text_suggestions' => $this->sanitize_string_list( $decoded['image_alt_text_suggestions'] ?? array() ),
			'language_used'              => isset( $decoded['language_used'] ) ? sanitize_text_field( (string) $decoded['language_used'] ) : $language,
			'content_notice'             => isset( $decoded['content_notice'] ) ? sanitize_textarea_field( (string) $decoded['content_notice'] ) : '',
			'quality_warnings'           => $quality_warnings,
		);
	}

	/**
	 * Maps a ClientException (4xx) to a specific, actionable WP_Error based
	 * on its HTTP status code.
	 *
	 * @since 1.0.0
	 *
	 * @param ClientException $e The exception.
	 * @return WP_Error
	 */
	private function map_client_exception( ClientException $e ): WP_Error {
		$status = $e->getCode();

		if ( in_array( $status, array( 401, 403 ), true ) ) {
			return new WP_Error(
				'authentication_failed',
				__( 'Could not authenticate with the AI provider. Check the API key configured for your AI Connector under Settings → Connectors.', 'somalifocus-ai-writer' ),
				array( 'status' => $status )
			);
		}

		if ( 429 === $status ) {
			return new WP_Error(
				'rate_limited',
				__( 'The AI provider rate-limited this request. Please wait a moment and try again.', 'somalifocus-ai-writer' ),
				array( 'status' => $status )
			);
		}

		return new WP_Error(
			'provider_rejected_request',
			sprintf(
				/* translators: %s: error detail from the provider. */
				__( 'The AI provider rejected the request: %s', 'somalifocus-ai-writer' ),
				$e->getMessage()
			),
			array( 'status' => $status )
		);
	}

	/**
	 * Runs lightweight heuristic quality checks that do not require a
	 * second AI call: detects headings with no body content beneath them,
	 * and flags a word count well under the requested tier. These are
	 * returned as warnings for the human reviewer, not hard failures,
	 * except where generate_article() above already treats a severely
	 * thin result as incomplete.
	 *
	 * @since 1.0.0
	 *
	 * @param string                            $content The generated block markup.
	 * @param array{min:int,max:int,tokens:int} $tier    The resolved length tier.
	 * @return list<string> Warning messages, empty when nothing was flagged.
	 */
	private function analyze_quality( string $content, array $tier ): array {
		$warnings = array();

		preg_match_all( '/<!--\s*wp:(paragraph|heading|list)\b/i', $content, $matches );
		$sequence = array_map( 'strtolower', $matches[1] ?? array() );

		$empty_headings = 0;
		$count          = count( $sequence );
		for ( $i = 0; $i < $count; $i++ ) {
			if ( 'heading' !== $sequence[ $i ] ) {
				continue;
			}
			$next_is_content = isset( $sequence[ $i + 1 ] ) && in_array( $sequence[ $i + 1 ], array( 'paragraph', 'list' ), true );
			if ( ! $next_is_content ) {
				++$empty_headings;
			}
		}

		if ( $empty_headings > 0 ) {
			$warnings[] = sprintf(
				/* translators: %d: number of headings with no content beneath them. */
				_n(
					'%d heading appears to have no paragraph content underneath it — review before publishing.',
					'%d headings appear to have no paragraph content underneath them — review before publishing.',
					$empty_headings,
					'somalifocus-ai-writer'
				),
				$empty_headings
			);
		}

		$word_count = $this->count_words( $content );
		if ( $word_count < $tier['min'] ) {
			$warnings[] = sprintf(
				/* translators: 1: word count generated, 2: minimum expected word count. */
				__( 'Generated body is shorter than requested (%1$d words; expected at least %2$d for this length setting).', 'somalifocus-ai-writer' ),
				$word_count,
				$tier['min']
			);
		}

		return $warnings;
	}

	/**
	 * Counts words in block markup content, after stripping block comments
	 * and HTML tags. Uses a language-agnostic whitespace split rather than
	 * str_word_count(), which assumes English word characters and would
	 * undercount Somali text.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content The block markup content.
	 * @return int Word count.
	 */
	private function count_words( string $content ): int {
		$stripped = preg_replace( '/<!--.*?-->/s', ' ', $content ) ?? '';
		$stripped = wp_strip_all_tags( $stripped );
		$pieces   = preg_split( '/\s+/u', trim( $stripped ) );

		if ( false === $pieces ) {
			return 0;
		}

		return count( array_filter( $pieces, static fn( $piece ) => '' !== $piece ) );
	}

	/**
	 * Sanitizes a value expected to be a list of strings from the model's
	 * JSON response, tolerating a malformed (non-array) value gracefully.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Raw value from the decoded JSON.
	 * @return list<string>
	 */
	private function sanitize_string_list( $value ): array {
		if ( ! is_array( $value ) ) {
			return array();
		}

		return array_values(
			array_filter(
				array_map(
					static fn( $item ) => is_scalar( $item ) ? sanitize_text_field( (string) $item ) : '',
					$value
				),
				static fn( $item ) => '' !== $item
			)
		);
	}

	/**
	 * Builds the configured prompt builder for an article generation request.
	 *
	 * @since 1.0.0
	 *
	 * @param string                            $topic              The topic.
	 * @param string                            $language           'en'|'so'|'mixed'.
	 * @param string                            $length             Length tier key.
	 * @param array{min:int,max:int,tokens:int} $tier               Resolved tier data.
	 * @param string                            $audience           Optional audience override.
	 * @param string                            $keywords           Optional target keywords.
	 * @param string                            $additional_context Optional extra instructions.
	 * @return \WP_AI_Client_Prompt_Builder|WP_Error
	 */
	private function get_prompt_builder( string $topic, string $language, string $length, array $tier, string $audience, string $keywords, string $additional_context ) {
		$user_prompt = sprintf( "Write the article now. Topic:\n\n%s", $topic );

		$system_instruction = $this->get_system_instruction(
			'system-instruction.php',
			array(
				'language'           => $language,
				'length_tier'        => $length,
				'word_min'           => $tier['min'],
				'word_max'           => $tier['max'],
				'audience'           => $audience,
				'keywords'           => $keywords,
				'additional_context' => $additional_context,
			)
		);

		$prompt_builder = wp_ai_client_prompt( $user_prompt )
			->using_system_instruction( $system_instruction )
			->using_temperature( 0.6 )
			->using_max_tokens( $tier['tokens'] )
			->as_json_response( $this->get_json_schema() );

		$prompt_builder = $this->set_provider_model_preference( $prompt_builder, Article_Generation_Feature::class );

		return $this->ensure_text_generation_supported(
			$prompt_builder,
			__( 'Article generation failed. Please ensure you have a connected AI provider (under Settings → Connectors) that supports text generation.', 'somalifocus-ai-writer' )
		);
	}

	/**
	 * The JSON schema the model is constrained to when generating the
	 * article, so the response is reliably parseable rather than relying
	 * on regex-scraping freeform text.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string, mixed>
	 */
	private function get_json_schema(): array {
		return array(
			'type'                 => 'object',
			'additionalProperties' => false,
			'required'             => array(
				'title',
				'content',
				'excerpt',
				'seo_title',
				'meta_description',
				'focus_keyword',
				'related_keywords',
				'categories',
				'tags',
				'image_alt_text_suggestions',
				'language_used',
				'content_notice',
			),
			'properties'           => array(
				'title'                      => array( 'type' => 'string' ),
				'content'                    => array( 'type' => 'string' ),
				'excerpt'                    => array( 'type' => 'string' ),
				'seo_title'                  => array( 'type' => 'string' ),
				'meta_description'           => array( 'type' => 'string' ),
				'focus_keyword'              => array( 'type' => 'string' ),
				'related_keywords'           => array(
					'type'  => 'array',
					'items' => array( 'type' => 'string' ),
				),
				'categories'                 => array(
					'type'  => 'array',
					'items' => array( 'type' => 'string' ),
				),
				'tags'                       => array(
					'type'  => 'array',
					'items' => array( 'type' => 'string' ),
				),
				'image_alt_text_suggestions' => array(
					'type'  => 'array',
					'items' => array( 'type' => 'string' ),
				),
				'language_used'              => array( 'type' => 'string' ),
				'content_notice'             => array( 'type' => 'string' ),
			),
		);
	}
}
