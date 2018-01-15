<?php
class GD_resize
{
	public $newheight = 0;

	public function __construct()  
	{
	
	}

	public function resizeImage($filename, $percent)
	{
		//$percent = 1.0;
		$percent = $percent / 100;
		
		// получение нового размера
		list($width, $height) = getimagesize($filename);
		$newwidth = $width * $percent;
		$this->newheight = $height * $percent;
		
		
		// Create image instances
		$src = imagecreatefrompng($filename);
		$dest = imagecreatetruecolor($newwidth, $this->newheight);
		$white = imagecolorallocate($dest, 255, 255, 255);
		
		imagealphablending($dest, false);
    imagesavealpha($dest, true);
		// Сделаем фон прозрачным
		imagecolortransparent($dest, $white);
		
		imagefill($dest, 0, 0, $white);
		
		// изменение размера
		Imagecopyresized($dest, $src, 0, 0, 0, 0, $newwidth, $this->newheight, $width, $height);
		//imagesavealpha($dest,TRUE);
		


		// Output and free from memory
		//header('Content-Type: image/png');
		//imagepng($src);
		imagepng($dest);
		imagedestroy($dest);
		imagedestroy($src);
	}
}