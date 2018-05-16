<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
ini_set('display_errors', true);
ini_set('html_errors', false);
setlocale(LC_NUMERIC, "C");

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', __DIR__ . "/../../.." );
define( 'ENGINE_DIR', ROOT_DIR . '/engine' );
define( 'MOD_DIR', __DIR__ );

include MOD_DIR . "/pluginLoader.php";

include (DLEPlugins::Check(ENGINE_DIR . '/data/config.php'));;
date_default_timezone_set($config['date_adjust']);

require_once (DLEPlugins::Check(ENGINE_DIR . '/classes/mysql.php'));;
require_once (DLEPlugins::Check(ENGINE_DIR . '/data/dbconfig.php'));;
require_once (DLEPlugins::Check(ENGINE_DIR . '/modules/functions.php'));;
dle_session();

$_POST['skin'] = totranslit($_POST['skin'], false, false);
if ($_POST['skin'] == "" || !@is_dir(ROOT_DIR . '/templates/' . $_POST['skin'])) $_POST['skin'] = $config['skin'];
if ($config["lang_" . $_POST['skin']]) {
	if ( file_exists( ROOT_DIR . '/language/' . $config["lang_" . $_POST['skin']] . '/website.lng' ) ) {
		include_once (DLEPlugins::Check(ROOT_DIR . '/language/' . $config["lang_" . $_POST['skin']] . '/website.lng'));;
	} else {
		die("Language file not found");
	}
} else {
	include_once (DLEPlugins::Check(ROOT_DIR . '/language/' . $config['langs'] . '/website.lng'));;
}
@header( "Content-type: text/html; charset=" . $config['charset'] );

$action = totranslit($_POST['action']);

$mod_lang = include_once MOD_DIR . '/lang.php';

require_once (DLEPlugins::Check(MOD_DIR . '/AvatarSelect.php'));;
$avasel = new AvatarSelect($mod_lang);

require_once (DLEPlugins::Check(ENGINE_DIR . '/modules/sitelogin.php'));;
if (!$is_logged) {
	$avasel->returnError($mod_lang['err_noauth']);
}

if ($action == 'save') {
	$avasel->updateFoto($_POST['foto']);
}

$foto_list = '';
foreach ($avasel->getImgList() as $img) {
	$c = $img['current'] ? ' class="active current"' : '';
	$foto_list .= "<li{$c}><div><img src=\"{$img['path']}\" alt=\"\" /></div></li>";
}

$html = <<<HTML
<div class="ava-modal-window">
	<div class="ava-modal-title">
		<div class="ava-modal-title-img" style="background-image: url({$avasel->getFoto($member_id['foto'])})" data-foto="background" title="{$mod_lang['curr_foto']}"></div>
		{$mod_lang['ava_sel']}
		<a href="#" class="ava-modal-close" title="{$mod_lang['close_window']}"></a>
	</div>
	<div class="ava-modal-content">
		<ul class="ava-modal-content-list">
			$foto_list
		</ul>
	</div>
	<div class="ava-modal-footer">
		<button class="ava-modal-close">{$mod_lang['button_close']}</button>
		<button class="ava-button-save">{$mod_lang['button_save']}</button>
	</div>
</div>
HTML;

echo json_encode(['html' => $html], JSON_UNESCAPED_UNICODE);
