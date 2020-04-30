<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css"/>
    <title>Index</title>
</head>
<body>
<div class="container col-lg-6 col-md-8 col-sm-12 text text-primary text-center" style="padding:2em">
    <div class="border border-dark">
        <h1>Hello, Professor Sanders!</h1>
        <p>This is the index of some files on Robby Moseley's computer</p>
        <p>Due to the sensitive nature of some files, not all files are being displayed</p>
    </div>
</div>


<div class="container col-lg-6 col-md-8 col-sm-10 text-center">
    <h1>Directory</h1>
    <?php
    echo("<h5>" . $_POST['newDir'] . "</h5>");

    if (isset($_POST['submit'])) {
        $path = "files/" . $_POST['newDir'];
    } else {
        $path = "files";
    }

    if (isset($_POST['reset'])) {
        $path = "files";
    }
    ?>
    <div class="container col-4">
        <form action="index.php" method="post">
            <div class="form-group">
                <input type="submit" class="btn btn-outline-primary" name="reset" value="Back"/>
            </div>
        </form>
    </div>
    <table class="table table-hover">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Type</th>
            <th scope="col">Name</th>
            <th scope="col">Size</th>
            <th scope="col">Date Modified</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                ?><tr> <?php
                if ('.' === $file) continue;
                if ('..' === $file) continue;

                // do something with the file
                $lastModifiedDatetime = date("d M Y H:i:s", filemtime("files/" . $file));
                ?>
                <tr>
                    <?php if (is_dir("files/" . $file)) { ?>
                        <td><svg class="bi bi-collection-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <rect width="16" height="10" rx="1.5" transform="matrix(1 0 0 -1 0 14.5)"/>
                                <path fill-rule="evenodd" d="M2 3a.5.5 0 00.5.5h11a.5.5 0 000-1h-11A.5.5 0 002 3zm2-2a.5.5 0 00.5.5h7a.5.5 0 000-1h-7A.5.5 0 004 1z" clip-rule="evenodd"/>
                            </svg>
                        </td>
                        <td>
                            <form action="index.php" method="post">
                                <input type="hidden" name="newDir" value="<?=$file?>">
                                <input type="submit" class="btn btn-outline-success" name="submit" value="<?=$file?>"/>
                            </form>
                        </td>
                        <?php
                    } else { ?>

                        <td>
                            <svg class="bi bi-download" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M.5 8a.5.5 0 01.5.5V12a1 1 0 001 1h12a1 1 0 001-1V8.5a.5.5 0 011 0V12a2 2 0 01-2 2H2a2 2 0 01-2-2V8.5A.5.5 0 01.5 8z" clip-rule="evenodd"/>
                                <path fill-rule="evenodd" d="M5 7.5a.5.5 0 01.707 0L8 9.793 10.293 7.5a.5.5 0 11.707.707l-2.646 2.647a.5.5 0 01-.708 0L5 8.207A.5.5 0 015 7.5z" clip-rule="evenodd"/>
                                <path fill-rule="evenodd" d="M8 1a.5.5 0 01.5.5v8a.5.5 0 01-1 0v-8A.5.5 0 018 1z" clip-rule="evenodd"/>
                            </svg>
                        </td>
                        <td><a href="files/<?=$file?>" download="<?=$file?>"><?=$file?></a></td>

                        <?php
                    }
                    ?>
                    <td><?=round(filesize("files/" . $file) / 1000, 1)?> KB</td>
                    <td><?=$lastModifiedDatetime?></td>
                </tr>
                <?php
            }
            closedir($handle);
        }
        ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>