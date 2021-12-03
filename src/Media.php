<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

new Ipup_Media();

class Ipup_Media {

	function __construct() {
		add_filter( 'manage_media_columns', array( $this, 'setIPFSColumn' ) );
		add_action( 'manage_media_custom_column', array( $this, 'setIPFSData' ) );
		add_filter( 'attachment_fields_to_edit', array( $this, 'setIPFSLinks' ), null, 2 );
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'ipup/v1',
					'/ipfs-upload',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'nftIPFSStore' ),
						'permission_callback' => '__return_true',
					)
				);
			}
		);
	}

	public function setIPFSColumn( $columns ) {
		$columns['ipup'] = 'IPFS';
		return $columns;
	}

	public function setIPFSData( $column ) {
		global $post;
		if ( $column === 'ipup' ) {
			$url     = $post->ipup_hash;
			$ipfs_id = substr( str_replace( 'https://ipfs.io/ipfs/', '', $url ), 0, 24 ) . '...';
			echo self::showIPFS( $post->ID, $post->ipup_hash );
		}
	}

	public function setIPFSDetail( $form_fields, $post ) {
		if ( substr( $post->post_mime_type, 0, 5 ) == 'image' ) {
			$user                         = new WP_User( $post->post_author );
			$form_fields['IPFS']['input'] = 'html';
			$form_fields['IPFS']['html']  = self::showIPFS( $post->ID, $post->ipup_hash );
		}
		return $form_fields;
	}

	public function setIPFSLinks( $form_fields, $post ) {
		$form_fields['ipfs']['label'] = __( 'IPFS URL', 'ipup' );
		$form_fields['ipfs']['input'] = 'html';
		$form_fields['ipfs']['html']  = self::showIPFS( $post->ID, $post->ipup_hash );
		return $form_fields;
	}

	private static function showIPFS( $id, $url ) {

		if ( $url ) {
			$ipfs_id = substr( str_replace( 'https://ipfs.io/ipfs/', '', $url ), 0, 24 ) . '...';
			return '
			<input type="text" class="attachment-details-copy-link" id="attachment-details-copy-ipfs-link" value="' . $url . '" readonly="">
			<span class="copy-to-clipboard-container" style="margin: 0;">
				<button type="button" class="button button-small copy-attachment-url" data-clipboard-target="#attachment-details-copy-ipfs-link">' . __( 'Copy URL to clipboard' ) . '</button>
				<span class="success hidden" aria-hidden="true">' . __( 'Copied!' ) . '</span>
			</span>
			';
		} else {
			return '
			<a id="ipup-' . $id . '" data-post-id="' . $id . '" class="button button-primary ipup-upload" style="margin-bottom: 10px;">
				' . __( 'Upload to IPFS', 'ipup' ) . '
			</a>
			<div id="ipup-upload-result-' . $id . '"></div>
			';
		}
	}

	public function nftIPFSStore( $param ) {

		// get attachment ID
		if ( ! is_array( $param['attachment_id'] ) ) {
			$attachment_id = (int) $param['attachment_id'];
			if ( ! $attachment_id or get_post_type( $attachment_id ) != 'attachment' ) {
				return new WP_REST_Response( 'Invalid attachment', 200 );
			}
		}

		// verify if the attachment was deployed already
		$ipup_hash = get_post_meta( $attachment_id, 'ipup_hash', true );
		if ( $ipup_hash ) {
			return new WP_REST_Response( $ipup_hash, 200 );
		}

		// get nft storage api key
		$nft_storage_key = get_option( 'ipup_nft_storage_api_key' );

		if ( ! $nft_storage_key ) {
			return new WP_REST_Response( __( 'Invalid NFT storage key. Are you the system admin? Please setup the nft.storage keys in your IPFS Upload settings.', 'ipup' ), 200 );
		}

		// get file content
		if ( is_array( $param['attachment_id'] ) ) {
			$file = array();
			foreach ( $param['attachment_id'] as $attachment_id ) {
				$file[] = file_get_contents( get_attached_file( $attachment_id ) );
			}
		} else {
			$file = file_get_contents( get_attached_file( $attachment_id ) );
			if ( ! $file ) {
				return new WP_REST_Response( __( 'Invalid attachment', 'ipup' ), 200 );
			}
		}

		// call the NFT Storage endpoint
		$options = array(
			'method'  => 'POST',
			'timeout' => 60,
			'headers' => array(
				'Authorization' => 'Bearer ' . $nft_storage_key,
			),
			'body'    => $file,
		);

		$result = wp_remote_request( 'https://api.nft.storage/upload', $options );

		if ( is_wp_error( $result ) ) {
			return new WP_REST_Response( $result->get_error_message(), 200 );
		} else {
			$resp_body = json_decode( wp_remote_retrieve_body( $result ) );
			if ( $resp_body->ok ) {
				$ipup_hash = 'https://ipfs.io/ipfs/' . $resp_body->value->cid;
				update_post_meta( $attachment_id, 'ipup_hash', $ipup_hash );
				$resp = array(
					'result'  => 'SUCCESS',
					'hash'    => $ipup_hash,
					'post_id' => $attachment_id,
				);
				return new WP_REST_Response( $resp, 200 );
			} else {
				$resp = array(
					'result'  => 'ERROR',
					'message' => $resp_body->error->message,
				);
				return new WP_REST_Response( $resp, 200 );
			}
		}
	}
}
