<?php
class GetImage {

var $source;
var $save_to;
var $set_extension;
var $quality;
var $name;

function download($method = 'curl',$pp,$quali=100) // default method: cURL
{
$this->name = $pp;
$info = @GetImageSize($this->source);
$mime = $info['mime'];

// What sort of image?
$type = substr(strrchr($mime, '/'), 1);

switch ($type) 
{
case 'jpeg':
    $image_create_func = 'ImageCreateFromJPEG';
    $image_save_func = 'ImageJPEG';
	$new_image_ext = 'jpg';
	
	// Best Quality: 100
	$quality = isSet($this->quality) ? $this->quality : $quali; 
    break;
default: 
	$image_create_func = 'ImageCreateFromJPEG';
    $image_save_func = 'ImageJPEG';
	$new_image_ext = 'jpg';
	$quality = isSet($this->quality) ? $this->quality : $quali; 
}

if(isSet($this->set_extension))
{
$ext = strrchr($this->source, ".");
$strlen = strlen($ext);
$new_name = $this->name.'.'.$new_image_ext;
}
else
{
$new_name = $this->name.'.'.$new_image_ext;
}

$save_to = $this->save_to.$new_name;

    if($method == 'curl')
	{
    $save_image = $this->LoadImageCURL($save_to);
	}
	elseif($method == 'gd')
	{
	$img = $image_create_func($this->source);

	    if(isSet($quality))
	    {
		   $save_image = $image_save_func($img, $save_to, $quality);
		}
		else
		{
		   $save_image = $image_save_func($img, $save_to);
		}
	}
	
	return $save_image;
}

function LoadImageCURL($save_to)
{
$ch = curl_init($this->source);
$fp = fopen($save_to, "wb");

// set URL and other appropriate options
$options = array(CURLOPT_FILE => $fp,
                 CURLOPT_HEADER => 0,
                 CURLOPT_FOLLOWLOCATION => 1,
	             CURLOPT_TIMEOUT => 60); // 1 minute timeout (should be enough)

curl_setopt_array($ch, $options);

curl_exec($ch);
curl_close($ch);
fclose($fp);
}
}
?>