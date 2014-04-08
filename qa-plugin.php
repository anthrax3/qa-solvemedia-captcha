<?php


/*
	Plugin Name: SolveMediaCAPTCHA
	Plugin URI: https://github.com/haivietduong/qa-solvemedia-captcha
	Plugin Description: Provides support for Solve Media CAPTCHAs
	Plugin Version: 1.0
	Plugin Date: 2014-04-07
	Plugin Author: Hai Duong
	Plugin Author URI: http://haiduong.me/
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI:
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}


	qa_register_plugin_module('captcha', 'qa-solvemedia-captcha.php', 'qa_solvemedia_captcha', 'Solve Media CAPTCHA');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/
