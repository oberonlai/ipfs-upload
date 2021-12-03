<?php

use ODS\Asset;

add_action(
	'init',
	function() {
		Asset::addScript(
			array(
				'name'    => 'ipup',
				'url'     => IPUP_PLUGIN_URL . 'assets/js/admin.js',
				'deps'    => array( 'jquery' ),
				'version' => '1.0.0',
				'footer'  => true,
				'ajax'    => false,
				'admin'   => true,
				'params'  => array(
					'endpoint'    => get_rest_url(),
					'nonce'       => wp_create_nonce( 'ipup' ),
					'textWait'    => __( 'Uploading...', 'ipup' ),
					'textPrepare' => __( 'Upload to IPFS', 'ipup' ),
					'textFinish'  => __( 'Upload finished', 'ipup' ),
					'textError'   => __( 'Upload failed, please check your <a href="/wp-admin/options-general.php?page=ipup-setting">API Key</a>.', 'ipup' ),
				),
			)
		);

		Asset::addStyle(
			array(
				'name'    => 'ipup',
				'url'     => IPUP_PLUGIN_URL . 'assets/css/admin.css',
				'version' => '1.0.0',
				'deps'    => array(),
				'admin'   => true,
			)
		);
	}
);
