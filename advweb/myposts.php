<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location:index.php');
}

$u = $_SESSION['user'];

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>My Posts</title>

    <style>
        img {
            height: 100%;
            width: 100%;
            object-fit: contain;
            padding: 1rem 0;
        }

        .imgBlock,
        #previewImg {
            width: 30vw;
        }
    </style>
</head>

<body>
    <?php
    $homelink = "";
    $mypostslink = "active";
    include('navigation.php');
    ?>
    <!-- User Posts Starts Here -->
    

    <div class="card mt-4">
        <div class="card-body">
            <form action="dbh/mynewpost.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Unmask Your Thoughts</label>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Select Image:</label>
                        <input type="file" name="file" id="choose-file">
                    </div>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="desc" minlength="10" maxlength="200" required></textarea>
                    <img id="previewImg" src="#" alt="" style="display: none;" />
                </div>
                <button type="submit" class="btn btn-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mastodon" viewBox="0 0 16 16">
                        <path d="M11.19 12.195c2.016-.24 3.77-1.475 3.99-2.603.348-1.778.32-4.339.32-4.339 0-3.47-2.286-4.488-2.286-4.488C12.062.238 10.083.017 8.027 0h-.05C5.92.017 3.942.238 2.79.765c0 0-2.285 1.017-2.285 4.488l-.002.662c-.004.64-.007 1.35.011 2.091.083 3.394.626 6.74 3.78 7.57 1.454.383 2.703.463 3.709.408 1.823-.1 2.847-.647 2.847-.647l-.06-1.317s-1.303.41-2.767.36c-1.45-.05-2.98-.156-3.215-1.928a3.614 3.614 0 0 1-.033-.496s1.424.346 3.228.428c1.103.05 2.137-.064 3.188-.189zm1.613-2.47H11.13v-4.08c0-.859-.364-1.295-1.091-1.295-.804 0-1.207.517-1.207 1.541v2.233H7.168V5.89c0-1.024-.403-1.541-1.207-1.541-.727 0-1.091.436-1.091 1.296v4.079H3.197V5.522c0-.859.22-1.541.66-2.046.456-.505 1.052-.764 1.793-.764.856 0 1.504.328 1.933.983L8 4.39l.417-.695c.429-.655 1.077-.983 1.934-.983.74 0 1.336.259 1.791.764.442.505.661 1.187.661 2.046v4.203z" />
                    </svg>
                    Post
                </button>
            </form>
        </div>
    </div>

    <script>
        const chooseFile = document.getElementById("choose-file");
        const previewImg = document.getElementById("previewImg");

        chooseFile.addEventListener("change", function() {
            const files = chooseFile.files[0];
            if (files) {
                console.log("lol")
                const fileReader = new FileReader();
                fileReader.readAsDataURL(files);
                fileReader.addEventListener("load", function() {
                    previewImg.style.display = "block";
                    previewImg.src = this.result;
                });
            }
        });
    </script>

    <?php
    include('dbh/dbdata.php');
    $con = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    $sql = "SELECT id,description,date,email,image FROM masks WHERE email='$u' ORDER BY date DESC";
    $result = $con->query($sql);
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
    ?>


        <div class="card mt-4">
            <div class="card-header">
                <?php echo ($row['date']); ?>
                <form action="dbh/deletepost.php" method="POST" onsubmit="return ask()">
                    <button type="submit" value="<?php echo ($id); ?>" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">Delete</button>
                </form>
            </div>
            <div class="card-body">
                <div class="imgBlock">
                    <img src="./assets/img/userUploads/<?php echo ($row['image']); ?>" alt="">
                </div>
                <blockquote class="blockquote mb-0">
                    <p><?php echo ($row['description']); ?></p>
                    <footer class="blockquote-footer">Someone with the email
                        <cite title="Source Title"><?php echo ($row['email']); ?></cite>
                    </footer>
                </blockquote>
            </div>
        </div>




        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete..
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <div class="card-header">
                            <form action="dbh/deletepost.php" method="POST">
                                <button type="submit" name="id" value="<?php echo ($id); ?>" class="btn btn-primary">Delete</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </div>

    <?php
    }
    $con->close();
    ?>


    <script>
        function ask() {

            var del = document.getElementById('delete')
            if (del == true) {
                return true;
            } else {
                return false;
            }
        }
    </script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>