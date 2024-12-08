<?php  
require_once 'dbConfig.php';
require_once 'models.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
			$_SESSION['message'] = $insertQuery['message'];

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../login.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = checkIfUserExists($pdo, $username);
		$userIDFromDB = $loginQuery['userInfoArray']['user_id'];
		$usernameFromDB = $loginQuery['userInfoArray']['username'];
		$passwordFromDB = $loginQuery['userInfoArray']['password'];

		if (password_verify($password, $passwordFromDB)) {
			$_SESSION['user_id'] = $userIDFromDB;
			$_SESSION['username'] = $usernameFromDB;
			header("Location: ../index.php");
		}

		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['user_id']);
	unset($_SESSION['username']);
	header("Location: ../login.php");
}


if (isset($_POST['insertPhotoBtn'])) {

    // Get Description and Album ID
    $description = $_POST['photoDescription'];
    $album_id = $_POST['album_id'];

    // Get file name
    $fileName = $_FILES['image']['name'];
    // Get temporary file name
    $tempFileName = $_FILES['image']['tmp_name'];
    // Get file extension
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Generate random characters for image name
    $uniqueID = sha1(md5(rand(1,9999999)));
    // Combine image name and file extension
    $imageName = $uniqueID.".".$fileExtension;

    // Save image 'record' to database
    $saveImgToDb = insertPhotoWithAlbum($pdo, $imageName, $_SESSION['username'], $description, $album_id);

    // Store actual 'image file' to images folder
    if ($saveImgToDb) {
        // Specify path
        $folder = "../images/".$imageName;

        // Move file to the specified path 
        if (move_uploaded_file($tempFileName, $folder)) {
            // Redirect to index.php after successful upload
            header("Location: ../index.php");
            exit();
        } else {
            // Handle upload failure
            $_SESSION['message'] = "There was an error uploading the file.";
            header("Location: ../index.php");
            exit();
        }
    }
}




if (isset($_POST['deletePhotoBtn'])) {
	$photo_name = $_POST['photo_name'];
	$photo_id = $_POST['photo_id'];
	$deletePhoto = deletePhoto($pdo, $photo_id);

	if ($deletePhoto) {
		unlink("../images/".$photo_name);
		header("Location: ../index.php");
	}

}

// new code 
// Handle Create Album
if (isset($_POST['createAlbumBtn'])) {
    $albumName = $_POST['albumName'];
    $username = $_SESSION['username'];

    // Insert album into the database
    $createAlbum = createAlbum($pdo, $albumName, $username);

    if ($createAlbum) {
        header("Location: ../index.php"); // Redirect to index.php to see the changes
    }
}


if (isset($_POST['updateAlbumNameBtn'])) {
    $album_id = $_POST['album_id'];
    $new_name = trim($_POST['new_album_name']);
    
    if (!empty($new_name)) {
        $updateAlbum = updateAlbumName($pdo, $album_id, $new_name);
        if ($updateAlbum) {
            header("Location: ../index.php");
        }
    }
}

// Handle Delete Album
if (isset($_GET['deleteAlbum'])) {
    $album_id = $_GET['deleteAlbum'];

    // Delete album from the database
    $deleteAlbum = deleteAlbum($pdo, $album_id);

    if ($deleteAlbum) {
        header("Location: ../index.php"); // Redirect to index.php after deleting
    }
}


// Handle Edit Album
if (isset($_POST['editAlbumBtn'])) {
    $albumName = $_POST['albumName'];
    $album_id = $_POST['album_id'];

    // Update album name in the database
    $updateAlbum = updateAlbum($pdo, $albumName, $album_id);

    if ($updateAlbum) {
        header("Location: ../index.php"); // Redirect to index.php to see the changes
    }
}

