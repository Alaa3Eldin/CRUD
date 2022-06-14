<?php
require 'db.php';

function clean($input)
{

    $input = trim($input);
    $input = stripslashes($input);
    $input = strip_tags($input);
    return $input;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $Title      = clean($_POST['Title']);
    $Content    = clean($_POST['Content']);
    $errors = [];


    if (empty($Title)) {
        $errors['Title'] = 'Field  ';
    } elseif (!ctype_alpha($Title)) {

        $errors['Title'] = 'Field  ';
    }
    if (empty($Content)) {
        $errors['Content'] = 'Field  ';
    } elseif (!ctype_alpha(strlen($Content) > 50)) {
        $errors['Content'] = 'Field ';
    }

    if (!empty($_FILES['image']['name'])) {

        $tempName  = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageType = $_FILES['image']['type'];

        $extensionArray = explode('/', $imageType);
        $extension =  strtolower(end($extensionArray));

        $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];

        if (in_array($extension, $allowedExtensions)) {

            $finalName = uniqid() . time() . '.' . $extension;

            $disPath = 'uploads/' . $finalName;

            if (move_uploaded_file($tempName, $disPath)) {
                echo 'File Uploaded Successfully';
            } else {
                echo 'File Uploaded Failed';
            }
        } else {
            echo 'File Type Not Allowed';
        }
    } else {
        echo 'Please Select File';
    }


    if (count($errors) > 0) {

        foreach ($errors as $key => $value) {
            echo $key . ' : ' . $value . '<br>';
        }
    } else {

        $sql = "insert into post (Title,Content,Image) values ('$Title','$Content' ,'$imageName')";
        $op =  mysqli_query($con, $sql);
        if ($op) {
            echo "Success , Your Account Created";
        } else {
            echo "Failed , " . mysqli_error($con);
        }
    }
}


?>


<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<div class="container">
    <h2>Blog</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="comment">Titel:</label>
            <input class="form-control" id="comment" name="Title"></input>
        </div>
        <div class="form-group">
            <label for="comment">Content:</label>
            <textarea class="form-control" rows="5" id="comment" name="Content"></textarea>
        </div>

        <div class="custom-file mb-3">
            <input type="file" class="custom-file-input" id="customFile" name="image">
            <label class="custom-file-label" for="customFile">Choose image</label>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>