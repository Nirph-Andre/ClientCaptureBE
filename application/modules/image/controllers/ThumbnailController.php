<?php

class Image_ThumbnailController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_LibPhoto';
	
	
	
	public function init()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$id = $this->getRequest()->getParam('id', 'null');
		if (!$id)
		{
			header('Location: /images/vehicle-no-image.jpg');
			exit(0);
		}
		$photo = $this->getObject()
			->view($id)
			->data;
		if (empty($photo))
		{
			header('Location: /images/vehicle-no-image.jpg');
			exit(0);
		}
		else
		{
			header("content-type: " . $photo['mime_type']);
			if (empty($photo['thumbnail']))
			{
				$imgString = $this->resizeImage($photo['photo'], $photo['mime_type']);
				if (!$imgString)
				{
					$imgString = '.';
				}
				$this->getObject()
					->save($photo['id'], array(), array('thumbnail' => $imgString));
				$photo['thumbnail'] = $imgString;
			}
			echo strlen($photo['thumbnail']) > 1
				? $photo['thumbnail']
				: $photo['photo'];
		}
	}
	
	private function resizeImage($imgString, $mimeType)
	{
		switch ($mimeType)
		{
			case 'image/jpeg':
			case 'image/pjpeg':
				$fileExt = '.jpg';
				break;
			case 'image/gif':
				$fileExt = '.gif';
				break;
			case 'image/png':
				$fileExt = '.png';
				break;
			case 'image/bmp':
			case 'image/x-windows-bmp':
				$fileExt = '.bmp';
				break;
			default:
				return false;
		}
		$tmpFile = APPLICATION_PATH . '/../public/files/'
						 . mt_rand(10000000, 99999999) . $fileExt;
		$bytesWritten = file_put_contents($tmpFile, $imgString);
		list($img_width, $img_height) = getimagesize($tmpFile);
		unlink($tmpFile);
		if (!$img_width || !$img_height)
		{
			return false;
		}
		$src_img = imagecreatefromstring($imgString);
		$scale = min(
				300 / $img_width,
				200 / $img_height
		);
		if ($scale >= 1)
		{
			return $imgString;
		}
		$new_width = $img_width * $scale;
		$new_height = $img_height * $scale;
		$new_img = @imagecreatetruecolor($new_width, $new_height);
		switch ($mimeType)
		{
			case 'image/jpeg':
			case 'image/pjpeg':
				$write_image = 'imagejpeg';
				$image_quality = 95;
				break;
			case 'image/gif':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
        $write_image = 'imagegif';
        $image_quality = null;
        break;
			case 'image/png':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				@imagealphablending($new_img, false);
				@imagesavealpha($new_img, true);
				$write_image = 'imagepng';
				$image_quality = 9;
				break;
			case 'image/bmp':
			case 'image/x-windows-bmp':
				$write_image = 'imagebmp';
				$image_quality = null;
				break;
			default:
				return false;
		}
		$success = @imagecopyresampled(
				$new_img,
				$src_img,
				0, 0, 0, 0,
				$new_width,
				$new_height,
				$img_width,
				$img_height
		);
		if ($success)
		{
			if ('imagebmp' == $write_image)
			{
				BMP::imagebmp($new_img, $tmpFile);
			}
			else
			{
				$write_image($new_img, $tmpFile, $image_quality);
			}
			@imagedestroy($src_img);
			@imagedestroy($new_img);
			$imgString = file_get_contents($tmpFile);
			unlink($tmpFile);
			return $imgString;
		}
		@imagedestroy($src_img);
		@imagedestroy($new_img);
		return false;
	}
		

}

