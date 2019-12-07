<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

/**
 * Custom Helper Functions
 * created on 3 Dec 2014
*/
// static entity array
if (!function_exists('base64_url_encode')) {
	function base64_url_encode($input) {
 		return strtr(base64_encode($input), '+/=', '._-');
	}
}

if (!function_exists('base64_url_decode')) {
	function base64_url_decode($input) {
 		return strtr(base64_decode($input), '+/=', '._-');
	}
}
// static entity array
if (!function_exists('arrayTrim')) {
function arrayTrim ( $array, $index ) {
   if ( is_array ( $array ) ) {
     unset ( $array[$index] );
     array_unshift ( $array, array_shift ( $array ) );
     return $array;
     }
   else {
     return false;
     }
   }
}
// static bank name array
if (!function_exists('getBanks')) {
	function getBanks() {
		$lines = file(ABSSTATICPATH."/arrays/banks.txt");
		$banks = array();
		foreach($lines as $line)
		{
			$banks[] = $line;
		}
		return $banks;
	}
}

// static Departments array
if (!function_exists('getDepartments')) {
	function getDepartments() {
		$lines = file(ABSSTATICPATH."/arrays/departments.txt");
		$departments = array();
		foreach($lines as $line)
		{
			$departments[] = $line;
		}
		return $departments;
	}
}

// static entity array
if (!function_exists('getEntities')) {
	function getEntities() {
		$lines = file(ABSSTATICPATH."/arrays/entities.txt");
		$entities = array();
		foreach($lines as $line)
		{
			$entities[] = $line;
		}
		return $entities;
	}
}

// static industry array
if (!function_exists('getIndustries')) {
	function getIndustries() {
		$lines = file(ABSSTATICPATH."/arrays/industries.txt");
		$industries = array();
		foreach($lines as $line)	
		{
			$industries[] = $line;
		}
		return $industries;
	}
}

// static industry array
if (!function_exists('getFunctions')) {
	function getFunctions() {
		$lines = file(ABSSTATICPATH."/arrays/function.txt");
		$function = array();
		foreach($lines as $line)	
		{
			$functions[] = $line;
		}
		return $functions;
	}
}

if (!function_exists('createDir')) {
	function createDir($dirPath = "") {
		if(!is_dir($dirPath)){
			mkdir($dirPath, 0777);
			chmod($dirPath, 0777);
		}
		return true;
	}
}

if (!function_exists('trimObject')) {
 function trimObject($object) {
  $ci = &get_instance();
  if(is_object($object) or is_array($object)){
   foreach ($object as &$prop) {
    if(is_string($prop)) {
     $prop = trim($prop);
    }
   }
  }else {
   $object = trim($object);
  }
  $current_method = "post";//$ci->_detect_method();
  if ("post" === $current_method) {
   foreach((array)$object as $k=>$v){
    $_POST[$k]=$v;
   }
  } else if("get" === $current_method){
   foreach((array)$object as $k=>$v){
    $_GET[$k]=$v;
   }
  }
  return $object;
 }
}

if (!function_exists('base64ToJPEG')) {
function base64ToJPEG($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb"); 

    $data = explode(',', $base64_string);

    fwrite($ifp, base64_decode($data[1])); 
    fclose($ifp); 
	if(file_exists($output_file))
		return true;
	else
		return false; 
}
}
	
if (!function_exists('base64ImgTo_FILES')) {
	//&& !check_base64_image($base64img)
    function base64ImgTo_FILES($base64img) {

        if('' !== $base64img && !check_base64_image($base64img)) {

            $response = array(

                              'status' => 200,

                              'error' => true,

                              'message' => 'Error! Invalid image code.'

                              );

            echo json_encode($response);

            exit;

        }

        if('' === $base64img) {

            $_FILES['attachedImage']['name']     = '';

            $_FILES['attachedImage']['type']     = '';

            $_FILES['attachedImage']['tmp_name'] = '';

            $_FILES['attachedImage']['error']    =  4;

            $_FILES['attachedImage']['size']     =  0;

        } else {

            $type_data = explode(";", $base64img);

            $type = str_replace('data:', '', $type_data[0]);
			
            $base64 = explode(',', $base64img);
			//print_r($base64);
            $base64 = base64_decode($base64[1]);

            $tmpfname = tempnam("./system/static/uploads/eventpics", "tmp");

            $handle = fopen($tmpfname, "w");

            fwrite($handle, $base64);

            fclose($handle);

            $imginfo = getimagesize($tmpfname);

            $img_size = filesize($tmpfname);

            switch ($imginfo['mime']) {

                case 'image/jpeg':

                    $source = imagecreatefromjpeg($tmpfname);

                    $ext = '.jpg';

                    break;

                case 'image/png':

                    $source = imagecreatefrompng($tmpfname);

                    $transparent_color = imagecolorallocate($source, 0, 0, 0);

                    imagecolortransparent($source, $transparent_color);

                    $ext = '.png';

                    break;

                case 'image/gif':

                    $source = imagecreatefromgif($tmpfname);

                    $transparent_color = imagecolorallocate($source, 0, 0, 0);

                    imagecolortransparent($source, $transparent_color);

                    $ext = '.gif';

                    break;

            }

            $new_name = md5(uniqid()) . $ext;

            $_FILES['attachedImage']['name'] = $new_name;

            $_FILES['attachedImage']['type'] = $type;

            $_FILES['attachedImage']['tmp_name'] = $tmpfname;

            $_FILES['attachedImage']['error'] = 0;

            $_FILES['attachedImage']['size'] = $img_size;
			//return $_FILES;
        }

    }

}

if (!function_exists('check_base64_image')) {
    function check_base64_image($base64img) {
        $base64 = explode(',', $base64img);
        $img = @imagecreatefromstring(base64_decode($base64[1]));
        if (!$img) {
            return false;
        }
        imagepng($img, 'tmp.png');
        $info = getimagesize('tmp.png');
        unlink('tmp.png');
        if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
            return true;
        }
        return false;
    }
}

/* Make URL friendly urlname */
function sanitizeString($string, $force_lowercase = true, $anal = false) {
	$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "=", "+", "[", "{", "]",
								 "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
								 "—", "–", ",","-", "<", ">", "/", "?");
	$clean = trim(str_replace($strip, "", strip_tags($string)));
	$clean = preg_replace('/\s+/', "_", $clean);
	$clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
	return ($force_lowercase) ?
			(function_exists('mb_strtolower')) ?
					mb_strtolower($clean, 'UTF-8') :
					strtolower($clean) :
			$clean;
}
/* Check Interests Limitation */
if (!function_exists('checkCliLimitation')) {
	function checkCliLimitation($userData) {
		$cli_data = unserialize($userData['cli_data']);
		$sel_crops = isset($cli_data['crops']) ? explode(",", $cli_data['crops']) : null;
		$sel_livestock = isset($cli_data['livestock']) ? explode(",", $cli_data['livestock']) : null;
		$sel_interests = isset($cli_data['interests']) ? explode(",", $cli_data['interests']) : null;
		$cli_limitation = (count($sel_crops) > 0 || !empty($userData["other_crops"])) || (count($sel_livestock) > 0 || !empty($userData["other_livestock"])) || (count($sel_interests) > 0 || !empty($userData["other_interests"]));
		return !$cli_limitation;
	}
}

/* Handle Feed Image Upload */
if (!function_exists('feedImageUpload')) {
	function feedImageUpload($files) {
		if (isset($files["attachedImage"]) && $files["attachedImage"]["name"] != "") {
			// JPEG quality 0 for lowest, 100 for highest
			$jpegQuality = 75;
			// Get the file posted
			$image = $files['attachedImage'];
			// Get file name
			$filename = $image['name'];
			// Get extension
			$ext = array_pop(explode('.', basename($filename)));
			// Set the new filename and folder location
			$newFilename = md5(uniqid()) . "." . $ext;
			$target = ABSUPLOADPATH . "/feed_image/" . $newFilename;
      // Generate universal image to convert later to JPG
			$generatedImage = imagecreatefromstring(file_get_contents($image['tmp_name']));
			$exif = @exif_read_data($image['tmp_name']);
			if (!empty($exif['Orientation'])) {
				switch ($exif['Orientation']) {
					case 8:
						$generatedImage = imagerotate($generatedImage, 90, 0);
						break;
					case 3:
						$generatedImage = imagerotate($generatedImage, 180, 0);
						break;
					case 6:
						$generatedImage = imagerotate($generatedImage, -90, 0);
						break;
				}
			}
			if (imagejpeg($generatedImage, $target, $jpegQuality)) {
				return $newFilename;
			}
		}
		return "";
	}
}

/* Handle Feed Image Upload */
if (!function_exists('backgroundImageUpload')) {
	function backgroundImageUpload($files) {
		if (isset($files["background_image"]) && $files["background_image"]["name"] != "") {
			// JPEG quality 0 for lowest, 100 for highest
			$jpegQuality = 75;
			// Get the file posted
			$image = $files['background_image'];
			// Get file name
			$filename = $image['name'];
			// Get extension
			$ext = array_pop(explode('.', basename($filename)));
			// Set the new filename and folder location
			$newFilename = md5(uniqid()) . "." . $ext;
			$target = ABSUPLOADPATH . "/background_image/" . $newFilename;
      // Generate universal image to convert later to JPG
			$generatedImage = imagecreatefromstring(file_get_contents($image['tmp_name']));
			$exif = @exif_read_data($image['tmp_name']);
			if (!empty($exif['Orientation'])) {
				switch ($exif['Orientation']) {
					case 8:
						$generatedImage = imagerotate($generatedImage, 90, 0);
						break;
					case 3:
						$generatedImage = imagerotate($generatedImage, 180, 0);
						break;
					case 6:
						$generatedImage = imagerotate($generatedImage, -90, 0);
						break;
				}
			}			
			$width  = imagesx($generatedImage);
			$height = imagesy($generatedImage);
			if($width >= BGWIDTH && $height >= BGHEIGHT) { //if less then required diamensions return false
				if (imagejpeg($generatedImage, $target, $jpegQuality)) {
					if($width == BGWIDTH && $height == BGHEIGHT) { //If diamensions are exactly same then do not crop
						return $newFilename;
						
					} else { //go for cropping
						$bg_path = ABSUPLOADPATH . "/background_image/";
						create_thumb($newFilename,$bg_path,$bg_path,BGWIDTH,BGHEIGHT);
						if (file_exists($bg_path . $bg_path)){
							unlink($bg_path . $bg_path);
						}
						return $newFilename;
					}
				}
			}
		}
		return "";
	}
}

/* get Twitter REST API object */
if (!function_exists('TwitterAPIObj')) {
	function TwitterAPIObj() {
		require_once ABSSTATICPATH . '/twitter/twitter.class.php';
		// ENTER HERE YOUR CREDENTIALS
		return new Twitter(TWCONSUMERKEY, TWCONSUMERSECRET, TWAUTHTOKEN, TWAUTHSECRET);
	}
}

/* Call back function for usort() */
if (!function_exists('stringCompare')) {
	function stringCompare($a, $b) {
		return strcmp($a["name"], $b["name"]);
	}
}

/* Get first name */
if (!function_exists('getFirstName')) {
	function getFirstName($string) {
		$stringSplit = explode(" ", $string);
		return $stringSplit[0];
	}
}

/* Call back function for usort() with dates */
if (!function_exists('feedDateTimeCompare')) {
	function feedDateTimeCompare($a, $b) {
		$a = (array) $a;
		$b = (array) $b;
		return strcmp($b["updated_on"], $a["updated_on"]);
	}
}

/* Read Connection Status */
if (!function_exists('readConnectionStatus')) {
	function readConnectionStatus($connectionStatus, $iRequested) {
		$connectStatus = 0;
		if ($connectionStatus == 1) {
			$connectStatus = 1; //Connected
		} else if ($connectionStatus == 3 || $connectionStatus == 4 || $connectionStatus == 5) {
			$connectStatus = 6; //Connection blocked after deny
			if ($connectionStatus == 4) {
				if ($iRequested == '1') {
					$connectStatus = 8; //If I blocked then show Unblock button
				} else {
					$connectStatus = 7; //If I get blocked then show Blocked
				}
			} else if ($connectionStatus == 5) {
				if ($iRequested == '1') {
					$connectStatus = 8; //If I blocked then show Unblock button
				} else {
					$connectStatus = 9; //If I get blocked then show Blocked
				}
			}
		} else {
			if ($iRequested == '1') {
				//I REQUESTED CONNECTION
				if ($connectionStatus == 0) {
					$connectStatus = 2; //Approval pending
				} else if ($connectionStatus == 2) {
					$connectStatus = 3; //Denied	& Can Resend OR Block
				}
			} else if ($iRequested == '0') {
				//I GOT REQUESTED
				if ($connectionStatus == 0) {
					$connectStatus = 4; //Allow connection
				} elseif ($connectionStatus == 2) {
					$connectStatus = 5; //Denied & Can block
				}
			}
		}
		return $connectStatus;
	}
}

/* Time lapsed */
if (!function_exists('checkAllowPostEdit')) {
	function checkAllowPostEdit($post, $author_id = 0, $author_type = 1) {
		$allowEdit = false;
		$allowedUser = false;
		// Allow if creation is less than one hour
		$time_difference = strtotime('now') - strtotime($post->created_on);
		if ($time_difference < 60 * 60) {
			$allowEdit = true;
		}
		// Allow if the user is allowed to edit
		if ($post->created_by == $author_id && $post->creation_type == $author_type) {
			$allowedUser = true;
		}
		return $allowEdit && $allowedUser;
	}
}

/* Time lapsed */
if (!function_exists('timeLapsed')) {
	function timeLapsed($time_stamp) {
		$time_difference = strtotime('now') - strtotime($time_stamp);

		if ($time_difference >= 60 * 60 * 24 * 365.242199) {
			/*
			 * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 365.242199 days/year
			 * This means that the time difference is 1 year or more
			 */
			return getTimeAgoString($time_stamp, 60 * 60 * 24 * 365.242199, 'year');
		} elseif ($time_difference >= 60 * 60 * 24 * 30.4368499) {
			/*
			 * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 30.4368499 days/month
			 * This means that the time difference is 1 month or more
			 */
			return getTimeAgoString($time_stamp, 60 * 60 * 24 * 30.4368499, 'month');
		} elseif ($time_difference >= 60 * 60 * 24 * 7) {
			/*
			 * 60 seconds/minute * 60 minutes/hour * 24 hours/day * 7 days/week
			 * This means that the time difference is 1 week or more
			 */
			return getTimeAgoString($time_stamp, 60 * 60 * 24 * 7, 'week');
		} elseif ($time_difference >= 60 * 60 * 24) {
			/*
			 * 60 seconds/minute * 60 minutes/hour * 24 hours/day
			 * This means that the time difference is 1 day or more
			 */
			return getTimeAgoString($time_stamp, 60 * 60 * 24, 'day');
		} elseif ($time_difference >= 60 * 60) {
			/*
			 * 60 seconds/minute * 60 minutes/hour
			 * This means that the time difference is 1 hour or more
			 */
			return getTimeAgoString($time_stamp, 60 * 60, 'hour');
		} elseif ($time_difference >= 60) {
			/*
			 * 60 seconds/minute
			 * This means that the time difference is a matter of minutes
			 */
			return getTimeAgoString($time_stamp, 60, 'minute');
		} else {
			/*
			 * Less than 60 seconds/minute
			 * This means that the time difference is a matter of seconds
			 */
			return getTimeAgoString($time_stamp, 1, 'second');
		}
	}
}
/* sub funtion of */
function getTimeAgoString($time_stamp, $divisor, $time_unit) {

	if ($divisor == 1) {
		return 'Few ' . $time_unit . 's';
	}

	$time_difference = strtotime("now") - strtotime($time_stamp);
	$time_units = floor($time_difference / $divisor);

	settype($time_units, 'string');

	if ($time_units === '0') {
		return '0 ' . $time_unit;
	} elseif ($time_units === '1') {
		return '1 ' . $time_unit;
	} else {
		/*
		 * More than "1" $time_unit. This is the "plural" message.
		 */
		// TODO: This pluralizes the time unit, which is done by adding "s" at the end; this will not work for i18n!
		return $time_units . ' ' . $time_unit . 's';
	}
}

/* validate mysql result set */
if (!function_exists('valResultSet')) {
	function valResultSet($resultSet) {
		if ($resultSet) {
			return true;
		} else {
			return false;
		}

	}
}
/* Get Oneall response */
if (!function_exists('callOneallAPI')) {
	function callOneallAPI($rest_url) {
		//Make Oneall API to get contacts
		include ABSSTATICPATH . 'oneall/init.php';
		if ($oneall_curly->get(SITE_DOMAIN . $rest_url)) {
			$result = $oneall_curly->get_result();

			if ($result->http_code == 200) {
				$response = json_decode($result->body);
				$response = $response->response;
				//v3print($response); exit;
				return $response;
			} else {
				return false;
			}

		} else {
			return false;
		}

	}
}

/* Create strong password */
if (!function_exists('generateStrongPassword')) {
	function generateStrongPassword($length = 8, $add_dashes = false, $available_sets = 'luds') {
		$sets = array();
		if (strpos($available_sets, 'l') !== false) {
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		}

		if (strpos($available_sets, 'u') !== false) {
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		}

		if (strpos($available_sets, 'd') !== false) {
			$sets[] = '23456789';
		}

		if (strpos($available_sets, 's') !== false) {
			$sets[] = '!@#$%&*?';
		}

		$all = '';
		$password = '';
		foreach ($sets as $set) {
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
		}

		$all = str_split($all);
		for ($i = 0; $i < $length - count($sets); $i++) {
			$password .= $all[array_rand($all)];
		}

		$password = str_shuffle($password);

		if (!$add_dashes) {
			return $password;
		}

		$dash_len = floor(sqrt($length));
		$dash_str = '';
		while (strlen($password) > $dash_len) {
			$dash_str .= substr($password, 0, $dash_len) . '-';
			$password = substr($password, $dash_len);
		}
		$dash_str .= $password;
		return $dash_str;
	}
}

/* Create thumbnail */
if (!function_exists('create_thumb')) {
	function create_thumb($file_name, $current_dir, $new_dir, $width, $height) {
		$file_path = $current_dir . $file_name; //$this->options['upload_dir']
		$new_file_path = $new_dir . $file_name; //$this->options['upload_dir']

		if (file_exists($file_path)) {
			$file_path = $current_dir . $file_name; //$this->options['upload_dir']
			$new_file_path = $new_dir . $file_name; //$this->options['upload_dir']
			require_once './system/static/imagelib/ThumbLib.inc.php';

			$thumb = PhpThumbFactory::create($file_path);
			$thumb->adaptiveResize($width, $height);
			$thumb->save($new_file_path);
		}
	}
}
/* Download image using URL */
function grabImage($url, $saveto) {
	$in = fopen($url, "rb");
	$out = fopen($saveto, "wb");
	while ($chunk = fread($in, 8192)) {
		fwrite($out, $chunk, 8192);
	}
	fclose($in);
	fclose($out);
	return true;
}

/* Check if image file is exists and unlink */
if (!function_exists('unlinkImage')) {
	function unlinkImage($image_name, $folder_name) {
		$image_path = ABSUPLOADPATH . "/" . $folder_name . "/" . $image_name;
		if ($image_name != "" && is_file($image_path)) {
			unlink($image_path);
			return true;
		} else {
			return false;
		}

	}
}

/* Check if image file is exists and return full path */
if (!function_exists('getImagePath')) {
	function getImagePath($image_name, $folder_name) {
		$image_url = "";
		$image_path = ABSUPLOADPATH . "/" . $folder_name . "/" . $image_name;
		if ($image_name != "" && is_file($image_path)) {
			$image_url = UPLOADPATH . "/" . $folder_name . "/" . $image_name;
		} else {
			if ($folder_name == "avatar") {
				$image_url = FRONTIMG . "/" . DEFAULTAVATAR;
			} else if ($folder_name == "logo") {
				$image_url = FRONTIMG . "/" . DEFAULTLOGO;
			}
		}
		return $image_url;
	}
}

/* Random string generator */
if (!function_exists('StringGenerator')) {
	function StringGenerator($no) {
		$characters = array(
			"1", "2", "3", "4", "5", "6", "7", "8", "9", "0",
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
			"k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
			"u", "v", "w", "x", "y", "z");
		$keys = array();
		while (count($keys) < $no) {
			$x = mt_rand(0, count($characters) - 1);
			if (!in_array($x, $keys)) {
				$keys[] = $x;
			}
		}
		$random_chars = "";
		foreach ($keys as $key) {
			$random_chars .= $characters[$key];
		}
		return $random_chars;
	}
}

/* Function to check result array exists */
if (!function_exists('isResult')) {
	function isResult($temparr) {
		if ($temparr && count($temparr) > 0) {
			return true;
		} else {
			return false;
		}

	}
}

/* Short cut to print array */
if (!function_exists('v3print')) {
	function v3print($temparr) {
		echo "<pre>";
		print_r($temparr);
	}
}

/* Date formate convert to June 29th 2015 */
if (!function_exists('dateFormateDOB')) {
	function dateFormateDOB($date = "") {
		if ($date != "") {
			return date('F jS Y', strtotime($date));
		} else {
			return "";
		}

	}
}

/* Date formate convert to MDY */
if (!function_exists('dateFormateMDY')) {
	function dateFormateMDY($date = "") {
		if ($date != "") {
			return date("m/d/Y", strtotime($date));
		} else {
			return "";
		}

	}
}

/* New file name for uploaded files */
if (!function_exists('newFileName')) {
	function newFileName($originalname = "") {
		$dotposition = strripos($originalname, '.'); // last occurace of '.'
		$imagetype = substr($originalname, $dotposition);
		$imgname = time() . $imagetype;
		return $imgname;
	}
}