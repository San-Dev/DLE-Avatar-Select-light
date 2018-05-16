<?php
/**
 * Класс работы с аватарками
 * 
 * @package Avatar-Select (light)
 * @link https://sandev.pro/
 * @author Oleg Odoevskyi (Sander) <oleg.sandev@gmail.com>
 */


class AvatarSelect
{
	protected $allow_type = ['jpg','jpeg','png','gif'];

	protected $local_path = '/uploads/fotos/bank/';

	protected $lang = [];

	public function __construct($lang = [])
	{
		$this->lang = $lang;
	}

	public function returnError($title = '')
	{
		$error = json_encode(['error' => $title], JSON_UNESCAPED_UNICODE);
		echo $error;
		die();
	}

	public function getFoto($member_foto = '')
	{
		global $config;
		if (!$member_foto) {
			return $config['http_home_url'] . 'templates/' . $_POST['skin'] . '/dleimages/noavatar.png';
		}

		if (count(explode("@", $member_foto)) == 2) {
			return 'https://www.gravatar.com/avatar/' . md5(trim($member_foto)) . '?s=50';
		}

		if (strpos($member_foto, "//") === 0) {
			$avatar = "http:" . $member_foto;
		} else {
			$avatar = $member_foto;
		}
		$avatar = @parse_url ( $avatar );
		if ($avatar['host']) {
			return $member_foto;
		}
		return $config['http_home_url'] . "uploads/fotos/" . $member_foto;
	}

	private function parseFotoPath($src)
	{
		if (!$src) {
			$this->returnError($this->lang['err_nopath']);
		} elseif (preg_match('#' . $this->local_path . '([^\?]+)#i', $src, $data)) {
			$foto_name = totranslit($data[1], false);
			if (!file_exists(ROOT_DIR . $this->local_path . $foto_name)) {
				$this->returnError($this->lang['err_nofolder']);
			}

			$type = explode('.', $foto_name);
			$type = end($type);
			$type = strtolower($type);
			if (!in_array($type, $this->allow_type)) {
				$this->returnError($this->lang['err_type']);
			}
			return $foto_name;
		} else {
			$this->returnError($this->lang['err_wronpath']);
		}
	}

	public function updateFoto($foto_src)
	{
		global $db, $member_id;
		if ($data = $this->parseFotoPath($foto_src)) {
			$foto = $db->safesql('bank/' . $data);
			$db->query("UPDATE ".USERPREFIX."_users SET foto = '$foto' WHERE user_id = {$member_id['user_id']}");
			echo json_encode([
				'info' => $this->lang['info_add'],
				'src'  => $_POST['foto'],
			], JSON_UNESCAPED_UNICODE);
			die();
		}
	}

	public function getImgList()
	{
		$dh = scandir(ROOT_DIR . $this->local_path);
		$list = [];
		foreach ($dh as $v) {
			$type = explode('.', $v);
			$type = end($type);
			$type = strtolower($type);
			if (in_array($type, $this->allow_type)) {
				$list[] = [
					'current' => $v == $this->getFotoInfo(),
					'path'    => $this->local_path . $v,
					'type'    => $type,
				];
			}
		}
		return $list;
	}

	public function getFotoInfo()
	{
		global $member_id;
		if ($member_id['foto'] && 
			preg_match('#bank/(.*)#i', $member_id['foto'], $info) && 
			file_exists(ROOT_DIR . '/uploads/fotos/' . $member_id['foto'])
		) {
			return $info[1];
		} else {
			return '';
		}
	}

}
