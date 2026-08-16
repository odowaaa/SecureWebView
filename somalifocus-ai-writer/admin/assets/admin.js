/**
 * SomaliFocus AI Writer — admin page behavior.
 *
 * Vanilla JS, no build step: uses wp.apiFetch (core script handle
 * "wp-api-fetch", already nonce-authenticated automatically by WordPress
 * core in wp-admin) to call the sf/generate-article Ability, then — only
 * when the admin explicitly clicks "Create Draft Post" — calls the
 * standard /wp/v2/posts REST endpoint with status "draft". There is no
 * path in this file that publishes a post or creates anything without an
 * explicit click from the logged-in admin.
 *
 * @package SomaliFocusAiWriter
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.apiFetch ) {
		return;
	}

	var apiFetch = wp.apiFetch;
	var config = window.SFAIWriterData || {};

	var form = document.getElementById( 'sf-ai-writer-form' );
	var noticeEl = document.getElementById( 'sf-ai-writer-notice' );
	var resultEl = document.getElementById( 'sf-ai-writer-result' );
	var generateBtn = document.getElementById( 'sf-generate-btn' );
	var spinner = document.getElementById( 'sf-generate-spinner' );

	if ( ! form || ! resultEl ) {
		return;
	}

	function showNotice( message, type ) {
		noticeEl.className = 'sf-ai-writer-notice notice notice-' + ( type || 'error' ) + ' is-dismissible';
		noticeEl.textContent = message;
		noticeEl.hidden = false;
	}

	function clearNotice() {
		noticeEl.hidden = true;
		noticeEl.textContent = '';
	}

	function setLoading( isLoading ) {
		generateBtn.disabled = isLoading;
		if ( spinner ) {
			spinner.classList.toggle( 'is-active', isLoading );
		}
	}

	function errorMessageFromResponse( err ) {
		// wp.apiFetch rejects with the parsed WP_Error-shaped JSON body:
		// { code, message, data }. Fall back to a generic message for
		// anything unexpected (e.g. a raw network failure).
		if ( err && typeof err.message === 'string' && err.message ) {
			return err.message;
		}
		return 'Something went wrong talking to WordPress. Please try again.';
	}

	function el( tag, attrs, children ) {
		var node = document.createElement( tag );
		attrs = attrs || {};
		Object.keys( attrs ).forEach( function ( key ) {
			if ( 'class' === key ) {
				node.className = attrs[ key ];
			} else if ( 'text' === key ) {
				node.textContent = attrs[ key ];
			} else if ( 'html' === key ) {
				// Only ever used with plugin-authored static strings below,
				// never with AI-generated or user-entered text.
				node.innerHTML = attrs[ key ]; // phpcs:ignore
			} else {
				node.setAttribute( key, attrs[ key ] );
			}
		} );
		( children || [] ).forEach( function ( child ) {
			if ( child ) {
				node.appendChild( child );
			}
		} );
		return node;
	}

	function labeledField( labelText, id, value ) {
		var wrap = el( 'div', { class: 'sf-ai-field' } );
		wrap.appendChild( el( 'label', { for: id, text: labelText } ) );
		var input = el( 'input', { type: 'text', id: id, class: 'large-text' } );
		input.value = value || '';
		wrap.appendChild( input );
		return wrap;
	}

	function labeledTextarea( labelText, id, value, rows ) {
		var wrap = el( 'div', { class: 'sf-ai-field' } );
		wrap.appendChild( el( 'label', { for: id, text: labelText } ) );
		var textarea = el( 'textarea', { id: id, class: 'large-text', rows: String( rows || 4 ) } );
		textarea.value = value || '';
		wrap.appendChild( textarea );
		return wrap;
	}

	function fieldValue( id ) {
		var node = document.getElementById( id );
		return node ? node.value.trim() : '';
	}

	form.addEventListener( 'submit', function ( event ) {
		event.preventDefault();
		clearNotice();
		resultEl.hidden = true;
		resultEl.innerHTML = '';

		var input = {
			topic: document.getElementById( 'sf-topic' ).value.trim(),
			language: document.getElementById( 'sf-language' ).value,
			length: document.getElementById( 'sf-length' ).value,
			keywords: document.getElementById( 'sf-keywords' ).value.trim(),
			audience: document.getElementById( 'sf-audience' ).value.trim(),
			additional_context: document.getElementById( 'sf-context' ).value.trim(),
		};

		if ( ! input.topic ) {
			showNotice( 'Please enter a topic.', 'error' );
			return;
		}

		setLoading( true );

		apiFetch( {
			path: config.abilityRestPath,
			method: 'POST',
			data: { input: input },
		} )
			.then( function ( response ) {
				setLoading( false );
				renderResult( response );
			} )
			.catch( function ( err ) {
				setLoading( false );
				showNotice( errorMessageFromResponse( err ), 'error' );
			} );
	} );

	function renderResult( data ) {
		resultEl.innerHTML = '';
		resultEl.hidden = false;

		if ( data.quality_warnings && data.quality_warnings.length ) {
			var warnBox = el( 'div', { class: 'notice notice-warning sf-ai-warnings' } );
			warnBox.appendChild( el( 'p', { text: 'Automated quality check flagged the following — review before publishing:' } ) );
			var list = el( 'ul', {} );
			data.quality_warnings.forEach( function ( w ) {
				list.appendChild( el( 'li', { text: w } ) );
			} );
			warnBox.appendChild( list );
			resultEl.appendChild( warnBox );
		}

		if ( data.content_notice ) {
			resultEl.appendChild(
				el( 'div', { class: 'notice notice-info sf-ai-content-notice' }, [
					el( 'p', { text: 'Note from the AI for the human editor: ' + data.content_notice } ),
				] )
			);
		}

		var reviewBox = el( 'div', { class: 'sf-ai-writer-review' } );
		reviewBox.appendChild( labeledField( 'Title', 'sf-out-title', data.title ) );
		reviewBox.appendChild( labeledTextarea( 'Content (WordPress block markup — inserted as-is into the post)', 'sf-out-content', data.content, 18 ) );
		reviewBox.appendChild( labeledTextarea( 'Excerpt', 'sf-out-excerpt', data.excerpt, 3 ) );
		reviewBox.appendChild( labeledField( 'SEO Title', 'sf-out-seo-title', data.seo_title ) );
		reviewBox.appendChild( labeledTextarea( 'Meta Description', 'sf-out-meta-description', data.meta_description, 2 ) );
		reviewBox.appendChild( labeledField( 'Focus Keyword', 'sf-out-focus-keyword', data.focus_keyword ) );
		reviewBox.appendChild( labeledField( 'Related Keywords', 'sf-out-related-keywords', ( data.related_keywords || [] ).join( ', ' ) ) );
		reviewBox.appendChild( labeledField( 'Language Used', 'sf-out-language', data.language_used ) );

		if ( data.image_alt_text_suggestions && data.image_alt_text_suggestions.length ) {
			var altBox = el( 'div', { class: 'sf-ai-field' } );
			altBox.appendChild( el( 'label', { text: 'Suggested image alt text (for images you add yourself)' } ) );
			var altList = el( 'ul', {} );
			data.image_alt_text_suggestions.forEach( function ( t ) {
				altList.appendChild( el( 'li', { text: t } ) );
			} );
			altBox.appendChild( altList );
			reviewBox.appendChild( altBox );
		}

		resultEl.appendChild( reviewBox );

		var termsBox = el( 'div', { class: 'sf-ai-terms' } );
		termsBox.appendChild( el( 'h3', { text: 'Suggested categories' } ) );
		var catList = el( 'div', { id: 'sf-cat-list', class: 'sf-ai-term-list' } );
		termsBox.appendChild( catList );
		termsBox.appendChild( el( 'h3', { text: 'Suggested tags' } ) );
		var tagList = el( 'div', { id: 'sf-tag-list', class: 'sf-ai-term-list' } );
		termsBox.appendChild( tagList );
		resultEl.appendChild( termsBox );

		resolveTerms( data.categories || [], config.categoriesPath, catList );
		resolveTerms( data.tags || [], config.tagsPath, tagList );

		var draftNotice = el( 'div', { class: 'sf-ai-writer-notice', hidden: 'hidden' } );
		resultEl.appendChild( draftNotice );

		var actions = el( 'p', {} );
		var draftBtn = el( 'button', { class: 'button button-primary', type: 'button', text: 'Create Draft Post' } );
		var draftSpinner = el( 'span', { class: 'spinner', style: 'float:none;' } );
		draftBtn.addEventListener( 'click', function () {
			createDraft( draftBtn, draftSpinner, draftNotice, catList, tagList );
		} );
		actions.appendChild( draftBtn );
		actions.appendChild( draftSpinner );
		resultEl.appendChild( actions );
	}

	/**
	 * For each suggested term name, looks up matching existing terms so
	 * the admin can pick an existing category/tag instead of always
	 * creating a duplicate. A suggestion with no exact match is shown as
	 * an unchecked "create new" option — nothing is created until the
	 * admin both checks it and clicks Create Draft.
	 */
	function resolveTerms( names, restPath, containerEl ) {
		if ( ! names.length ) {
			containerEl.appendChild( el( 'p', { class: 'description', text: 'None suggested.' } ) );
			return;
		}

		names.forEach( function ( name ) {
			var row = el( 'label', { class: 'sf-ai-term-row' } );
			var checkbox = el( 'input', { type: 'checkbox' } );
			checkbox.checked = true;
			checkbox.dataset.termName = name;
			checkbox.dataset.termId = '';
			row.appendChild( checkbox );
			row.appendChild( document.createTextNode( ' ' + name + ' ' ) );
			var status = el( 'span', { class: 'description', text: '(checking…)' } );
			row.appendChild( status );
			containerEl.appendChild( row );

			apiFetch( { path: restPath + '?search=' + encodeURIComponent( name ) + '&per_page=5' } )
				.then( function ( results ) {
					var exact = ( results || [] ).find( function ( term ) {
						return term.name && term.name.toLowerCase() === name.toLowerCase();
					} );
					if ( exact ) {
						checkbox.dataset.termId = String( exact.id );
						status.textContent = '(existing)';
					} else {
						status.textContent = '(new — will be created)';
					}
				} )
				.catch( function () {
					status.textContent = '(could not check — will attempt to create if checked)';
				} );
		} );
	}

	/**
	 * Resolves the checked term checkboxes in a container into an array
	 * of term IDs, creating new terms via REST for any checked
	 * "new" suggestion. A term that fails to create (e.g. missing
	 * manage_categories capability) is skipped with a console warning
	 * rather than blocking draft creation.
	 */
	function collectTermIds( containerEl, restPath ) {
		var checkboxes = Array.prototype.slice.call( containerEl.querySelectorAll( 'input[type=checkbox]:checked' ) );

		var promises = checkboxes.map( function ( checkbox ) {
			if ( checkbox.dataset.termId ) {
				return Promise.resolve( parseInt( checkbox.dataset.termId, 10 ) );
			}
			return apiFetch( { path: restPath, method: 'POST', data: { name: checkbox.dataset.termName } } )
				.then( function ( term ) {
					return term && term.id ? term.id : null;
				} )
				.catch( function ( err ) {
					if ( window.console && window.console.warn ) {
						window.console.warn( 'SomaliFocus AI Writer: could not create term "' + checkbox.dataset.termName + '":', err );
					}
					return null;
				} );
		} );

		return Promise.all( promises ).then( function ( ids ) {
			return ids.filter( function ( id ) {
				return null !== id && undefined !== id;
			} );
		} );
	}

	function createDraft( draftBtn, draftSpinner, draftNotice, catList, tagList ) {
		draftBtn.disabled = true;
		draftSpinner.classList.add( 'is-active' );
		draftNotice.hidden = true;

		Promise.all( [
			collectTermIds( catList, config.categoriesPath ),
			collectTermIds( tagList, config.tagsPath ),
		] )
			.then( function ( results ) {
				var categoryIds = results[ 0 ];
				var tagIds = results[ 1 ];

				var meta = {};
				if ( config.seoMetaKeys ) {
					if ( config.seoMetaKeys.title ) {
						meta[ config.seoMetaKeys.title ] = fieldValue( 'sf-out-seo-title' );
					}
					if ( config.seoMetaKeys.description ) {
						meta[ config.seoMetaKeys.description ] = fieldValue( 'sf-out-meta-description' );
					}
					if ( config.seoMetaKeys.focusKeyword ) {
						meta[ config.seoMetaKeys.focusKeyword ] = fieldValue( 'sf-out-focus-keyword' );
					}
				}

				var payload = {
					title: fieldValue( 'sf-out-title' ),
					content: fieldValue( 'sf-out-content' ),
					excerpt: fieldValue( 'sf-out-excerpt' ),
					status: 'draft',
					categories: categoryIds,
					tags: tagIds,
					meta: meta,
				};

				return apiFetch( { path: config.postsRestPath, method: 'POST', data: payload } );
			} )
			.then( function ( post ) {
				draftBtn.disabled = false;
				draftSpinner.classList.remove( 'is-active' );
				draftNotice.className = 'sf-ai-writer-notice notice notice-success';
				draftNotice.hidden = false;
				draftNotice.innerHTML = '';
				draftNotice.appendChild( document.createTextNode( 'Draft created. ' ) );
				var link = el( 'a', {
					href: config.editPostUrlBase + post.id,
					text: 'Open it in the editor to review and publish →',
				} );
				draftNotice.appendChild( link );
			} )
			.catch( function ( err ) {
				draftBtn.disabled = false;
				draftSpinner.classList.remove( 'is-active' );
				draftNotice.className = 'sf-ai-writer-notice notice notice-error';
				draftNotice.hidden = false;
				draftNotice.textContent = errorMessageFromResponse( err );
			} );
	}
} )( window.wp );
