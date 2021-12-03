<?php

use ODS\Option;

$config = new Option( 'ipup_' );
$config->addMenu(
	array(
		'page_title' => __( 'IPFS Upload Setting', 'ipup' ),
		'menu_title' => __( 'IPFS Upload', 'ipup' ),
		'capability' => 'manage_options',
		'slug'       => 'ipup-setting',
		'option'     => true,
	)
);
$config->addTab(
	array(
		array(
			'id'    => 'general_section',
			'title' => __( 'IPFS Upload Settings', 'ipup' ),
		),
	)
);
$config->addTextarea(
	'general_section',
	array(
		'id'           => 'nft_storage_api_key',
		'label'        => __( 'API Key', 'ipup' ),
		'desc'         => __( 'IPFS Upload uses <a href="https://nft.storage" target="_blank" rel="noopener noreferrer">NFT.Storage</a> to store your file in the IPFS. You can get your API Key <a href="https://nft.storage/#getting-started" target="_blank" rel="noopener noreferrer">here</a>.', 'ipup' ),
		'placeholder'  => __( 'Enter your API Key', 'ipup' ),
		'show_in_rest' => true,
		'size'         => 'regular',
	),
);
$config->register();
