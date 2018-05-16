<?php
/**
 * Для совместимости с версиями DLE до 13.0
 */
include_once ENGINE_DIR . '/classes/plugins.class.php';

if (!class_exists('DLEPlugins')) {
	class DLEPlugins
	{
		static public function Check($source = '')
		{
			return $source;
		}
	}
}
