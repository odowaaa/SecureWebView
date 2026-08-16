<?php
/**
 * Renders the "AI Writer" admin page: a topic/language/length form, a
 * generated-article review panel, and an explicit "Create Draft" action.
 *
 * The PHP side here only renders static markup and a handful of
 * translated strings/localized data; all interaction (calling the
 * ability, showing errors, letting the admin edit the result, creating
 * the draft) happens in admin/assets/admin.js against WordPress's own
 * REST API (the Abilities REST endpoint to generate, and the standard
 * /wp/v2/posts endpoint — always with status=draft — to save). No PHP
 * code in this plugin ever calls wp_insert_post() or otherwise writes a
 * post to the database; the only way a draft gets created is the admin
 * clicking "Create Draft", which uses their own authenticated REST
 * session and their own edit_posts capability, exactly like using the
 * block editor itself.
 *
 * @package SomaliFocusAiWriter
 */

declare( strict_types=1 );

namespace SomaliFocus\AiWriter;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin page renderer.
 *
 * @since 1.0.0
 */
class Admin_Page {

	/**
	 * Renders the admin page markup.
	 *
	 * @since 1.0.0
	 */
	public static function render(): void {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$seo = SEO_Integration::get_meta_keys();
		?>
		<div class="wrap sf-ai-writer">
			<h1><?php esc_html_e( 'SomaliFocus AI Writer', 'somalifocus-ai-writer' ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Generates a complete draft article from a topic using the AI provider configured under Settings → Connectors. Nothing is published automatically — review and edit the result below, then create a draft, and publish it yourself from the normal post editor.', 'somalifocus-ai-writer' ); ?>
			</p>

			<?php if ( $seo['plugin'] ) : ?>
				<p class="description">
					<?php
					printf(
						/* translators: %s: detected SEO plugin slug. */
						esc_html__( 'SEO fields will be sent using the meta keys for your active SEO plugin (%s). If they do not appear automatically in that plugin\'s panel, copy them from the review screen manually.', 'somalifocus-ai-writer' ),
						esc_html( $seo['plugin'] )
					);
					?>
				</p>
			<?php else : ?>
				<p class="description">
					<?php esc_html_e( 'No supported SEO plugin was detected. SEO fields will still be generated and shown below for you to copy manually, or stored as plain post meta.', 'somalifocus-ai-writer' ); ?>
				</p>
			<?php endif; ?>

			<div id="sf-ai-writer-notice" class="sf-ai-writer-notice" hidden></div>

			<form id="sf-ai-writer-form" class="sf-ai-writer-form">
				<table class="form-table" role="presentation">
					<tr>
						<th><label for="sf-topic"><?php esc_html_e( 'Topic', 'somalifocus-ai-writer' ); ?></label></th>
						<td>
							<input type="text" id="sf-topic" name="topic" class="large-text" required
								placeholder="<?php esc_attr_e( 'e.g. Guidelines for Selecting the Right Consultancy for Your NGO', 'somalifocus-ai-writer' ); ?>">
						</td>
					</tr>
					<tr>
						<th><label for="sf-language"><?php esc_html_e( 'Language', 'somalifocus-ai-writer' ); ?></label></th>
						<td>
							<select id="sf-language" name="language">
								<option value="en"><?php esc_html_e( 'English', 'somalifocus-ai-writer' ); ?></option>
								<option value="so"><?php esc_html_e( 'Somali', 'somalifocus-ai-writer' ); ?></option>
								<option value="mixed"><?php esc_html_e( 'Mixed (Somali with English technical terms)', 'somalifocus-ai-writer' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="sf-length"><?php esc_html_e( 'Length', 'somalifocus-ai-writer' ); ?></label></th>
						<td>
							<select id="sf-length" name="length">
								<option value="short"><?php esc_html_e( 'Short (~400–600 words)', 'somalifocus-ai-writer' ); ?></option>
								<option value="standard" selected><?php esc_html_e( 'Standard (~800–1200 words)', 'somalifocus-ai-writer' ); ?></option>
								<option value="detailed"><?php esc_html_e( 'Detailed (~1500–2200 words)', 'somalifocus-ai-writer' ); ?></option>
								<option value="comprehensive"><?php esc_html_e( 'Comprehensive (~2500–3500 words)', 'somalifocus-ai-writer' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="sf-keywords"><?php esc_html_e( 'Target keywords (optional)', 'somalifocus-ai-writer' ); ?></label></th>
						<td><input type="text" id="sf-keywords" name="keywords" class="large-text" placeholder="<?php esc_attr_e( 'comma-separated, optional', 'somalifocus-ai-writer' ); ?>"></td>
					</tr>
					<tr>
						<th><label for="sf-audience"><?php esc_html_e( 'Audience override (optional)', 'somalifocus-ai-writer' ); ?></label></th>
						<td><input type="text" id="sf-audience" name="audience" class="large-text"></td>
					</tr>
					<tr>
						<th><label for="sf-context"><?php esc_html_e( 'Additional context (optional)', 'somalifocus-ai-writer' ); ?></label></th>
						<td><textarea id="sf-context" name="additional_context" class="large-text" rows="3"></textarea></td>
					</tr>
				</table>
				<p>
					<button type="submit" class="button button-primary" id="sf-generate-btn">
						<?php esc_html_e( 'Generate Article', 'somalifocus-ai-writer' ); ?>
					</button>
					<span id="sf-generate-spinner" class="spinner" style="float:none;"></span>
				</p>
			</form>

			<div id="sf-ai-writer-result" class="sf-ai-writer-result" hidden></div>
		</div>
		<?php
	}
}
