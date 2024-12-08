<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$albums = getUserAlbums($pdo, $_SESSION['username']); // Get all albums for the current user
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCOS Snaps</title>
    <link rel="stylesheet" href="styles/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Create Album Form -->
    <div class="createAlbumForm" style="text-align: center; margin-top: 25px;">
        <form action="core/handleForms.php" method="POST">
            <label for="albumName">Create a New Album:</label>
            <input type="text" name="albumName" id="albumName" required>
            <input type="submit" name="createAlbumBtn" value="Create Album">
        </form>
    </div>

    <!-- Display User Albums -->
    <div class="userAlbums" style="margin-top: 30px;">
        <h3>Your Albums:</h3>
        <?php if ($albums): ?>
            <table style="width: 100%;">
                <tr>
                    <th>Album Name</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($albums as $album): ?>
                    <tr>
                        <td><?php echo $album['album_name']; ?></td>
                        <td>
                            <a href="editAlbum.php?album_id=<?php echo $album['album_id']; ?>">Edit</a> | 
                            <a href="core/handleForms.php?deleteAlbum=<?php echo $album['album_id']; ?>" onclick="return confirm('Are you sure you want to delete this album?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>You have no albums. Create one now!</p>
        <?php endif; ?>
    </div>

    <!-- Photo Upload Form -->
    <div class="insertPhotoForm" style="display: flex; justify-content: center; margin-top: 25px;">
        <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
            <p>
                <label for="photoDescription">Description</label>
                <input type="text" name="photoDescription" id="photoDescription" required>
            </p>
            <p>
                <label for="image">Photo Upload</label>
                <input type="file" name="image" id="image" required>
            </p>
            <p>
                <label for="album_id">Select Album</label>
                <select name="album_id" id="album_id" required>
                    <option value="">Select Album</option>
                    <?php foreach ($albums as $album): ?>
                        <option value="<?php echo $album['album_id']; ?>"><?php echo $album['album_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <input type="submit" name="insertPhotoBtn" value="Upload Photo" style="margin-top: 10px;">
            </p>
        </form>
    </div>

    <!-- Display Photos -->
    <div class="photosGallery" style="margin-top: 25px;">
        <h3>All Photos:</h3>
        <?php 
        $getAllPhotos = getAllPhotos($pdo); 
        foreach ($getAllPhotos as $row): 
        ?>
        <div class="images" style="display: flex; justify-content: center; margin-top: 25px;">
            <div class="photoContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%;">
                <img src="images/<?php echo $row['photo_name']; ?>" alt="Photo" style="width: 100%;">
                <div class="photoDescription" style="padding:25px;">
                    <a href="profile.php?username=<?php echo $row['username']; ?>"><h2><?php echo $row['username']; ?></h2></a>
                    <p><i><?php echo $row['date_added']; ?></i></p>
                    <h4><?php echo $row['description']; ?></h4>

                    <?php if ($_SESSION['username'] == $row['username']): ?>
                        <a href="editphoto.php?photo_id=<?php echo $row['photo_id']; ?>" style="float: right;"> Edit </a>
                        <br><br>
                        <a href="deletephoto.php?photo_id=<?php echo $row['photo_id']; ?>" style="float: right;"> Delete</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
