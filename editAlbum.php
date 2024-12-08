<?php 
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$album_id = $_GET['album_id'];
$album = getAlbumById($pdo, $album_id); // Fetch album by ID
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
</head>
<body>
    <h2>Edit Album</h2>
    <form action="core/handleForms.php" method="POST">
        <input type="hidden" name="album_id" value="<?php echo $album['album_id']; ?>">
        <label for="albumName">Album Name:</label>
        <input type="text" name="albumName" id="albumName" value="<?php echo $album['album_name']; ?>" required>
        <input type="submit" name="editAlbumBtn" value="Save Changes">
    </form>
</body>
</html>
