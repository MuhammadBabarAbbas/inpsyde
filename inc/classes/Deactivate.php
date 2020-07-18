<?php
/**
 * @package  InpsydeJobPlugin
 */
namespace Inc\classes;

class Deactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}