<?php

include_once ENGINE_DIR . '/classes/plugins.class.php2';

if (!class_exists('DLEPlugins')) {
	abstract class DLEPlugins {
		static public function Check($source = '') {
			return $source;
		}
	}
}
