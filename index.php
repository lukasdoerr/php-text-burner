<?php

/*

Text Burner Tool by ldoerr.com
(c) 2022 Lukas Dörr - All rights reserved!

-----------------

PLEASE READ:

You can use/modify this tool if you keep the copyright notice in the header (comment).
Feel free to modify the variables below to let it look like your tool.

Create a database and create this table:

CREATE TABLE `redirects` (
  `id` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `creator_ip` varchar(64) DEFAULT NULL,
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `used` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `redirects`
  ADD PRIMARY KEY (`id`);
COMMIT;



Info:
$baseUrl: The URL the redirect will be generated to.
$deleteAfterRead: true = text will be deleted out of database, false = text will be kept but is not accessable for frontend (Not recommended!)
$logoUrl: If you want to use a logo, you can do that by providing absolute url to image (Best way is, to put it into resources folder!)
$title: The page title and alt tag for logo (if wanted)
$copyright: Copyright Text under the Burner tool. Don't forget to give credits to ldoerr.com!
$additionalHeaders: If you want to use more than normal headers, paste your code into it!
*/


$baseUrl = 'http://localhost/rdr/';
$deleteAfterRead = false;
$logoUrl = 'https://www.ldoerr.com/typo3conf/ext/ldoerr_template/Resources/Public/Images/ldoerr_logo.svg';
$title = 'Text Burner - ldoerr.com';
$copyright = '&copy; ' . date('Y') . ' ldoerr.com - All Rights reserved.<br><a style="font-size: 10px;" href="https://www.flaticon.com/free-icons/fire" title="fire icons">Fire icons created by Vitaly Gorbachev - Flaticon</a>';
$additionalHeaders = '
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
';


session_start();

include 'config.php';


@$con = mysqli_connect(host, user, pass, db);
if (!$con) {
    echo "DB ERROR";
} else {
    if (!isset($_GET['do'])) {
?>
        <!DOCTYPE html>
        <html lang="en">
        <!--
            DON'T REMOVE THIS COPYRIGHT NOTICE!
            This little tool was created by ldoerr.com
            Host your own for free on https://www.ldoerr.com/tools/text-burner
        -->

        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $title; ?></title>
            <?php echo $additionalHeaders; ?>
            
            <link rel="stylesheet" href="resources/css/bootstrap.min.css">
            <link rel="stylesheet" href="resources/css/style.css">
        </head>

        <body>
            <div class="spacerbig">

            </div>
            <div class="container">
                <div class="row justify-content-center mb-3">
                    <div class="col-10 col-md-6 text-center">
                        <a href="index.php" class="none">
                            <h1>
                                <?php
                                if (empty($logoUrl)) {
                                    echo $title;
                                } else {
                                    echo '<img src="' . $logoUrl . '" class="topLogo" title="' . $title . '" alt="' . $title . '"><br>
                                    <b style="font-size: 16px; font-weight: 400;">Text Burner Tool - Create one time pastes</b>';
                                }
                                ?>
                            </h1>
                        </a>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-10 col-md-6 text-center">
                        <form action="index.php?do=addpaste" method="post">
                            <?php
                            if (@isset($_GET['err'])) {
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php
                                    if ($_GET['err'] == 1) {
                                        echo "Paste must not be empty";
                                    }
                                    if ($_GET['err'] == 2) {
                                        echo "Insert failed. Try again.";
                                    }
                                    if ($_GET['err'] == 3) {
                                        echo "ID must not be empty";
                                    }
                                    if ($_GET['err'] == 4) {
                                        echo "Paste not found.";
                                    }
                                    ?>
                                </div>
                            <?php
                            }
                            ?>
                            <textarea required placeholder="Your paste..." name="paste" class="form-control"></textarea>
                            <div id="emailHelp" class="form-text">This text will only be available once. When the link is clicked, it`s deleted.</div>
                            <button class="btn btn-primary btn-lg px-4 gap-3 mt-5" type="submit">Generate Link</button>
                        </form>
                    </div>
                </div>
                <div class="row mt-5 justify-content-center">
                    <div class="col-10 col-md-6 text-center">
                        <span class="text-secondary">
                            <?php echo $copyright; ?><br>
                            <a href="https://www.ldoerr.com/tools/text-burner" target="_blank" rel="noopener noreferrer">Host your own TextBurner for free!</a>
                        </span>
                    </div>
                </div>
            </div>


            <script src="resources/js/bootstrap.bundle.min.js"></script>
        </body>

        </html>
        <?php
    } else {
        if ($_GET['do'] == 'addpaste') {
            if (!isset($_POST['paste'])) {
                header('location: index.php?err=1');
                exit;
            } else {
                $input = $_POST['paste'];
                $input = mysqli_real_escape_string($con, $input);

                $genId = idGenerator();
                $client_ip = $_SERVER['REMOTE_ADDR'];
                $current_dt = date('Y-m-d H:i:s');

                if (mysqli_query($con, "INSERT INTO `redirects`(`id`, `content`, `creator_ip`, `create_date`, `used`) VALUES ('$genId', '$input', '$client_ip', '$current_dt', 0);")) {
                    $_SESSION['id'] = $genId;
                    header('location: index.php?do=finish');
                    exit;
                } else {
                    header('location: index.php?err=2');
                    exit;
                }
            }
        }
        if ($_GET['do'] == 'finish') {

            if (!isset($_SESSION['id'])) {
                header('location: index.php?err=4');
                exit;
            } else {
                $id = $_SESSION['id'];
                session_destroy();
                session_unset();

                $url = $baseUrl . '?do=s&id=' . $id;

        ?>
                <!DOCTYPE html>
                <html lang="en">
                <!--
                        DON'T REMOVE THIS COPYRIGHT NOTICE!
                        This little tool was created by ldoerr.com
                        Host your own for free on https://www.ldoerr.com/tools/text-burner
                    -->

                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title><?php echo $title; ?></title>
                    <?php echo $additionalHeaders; ?>
                    <link rel="stylesheet" href="resources/css/bootstrap.min.css">
                    <link rel="stylesheet" href="resources/css/style.css">
                </head>

                <body>
                    <div class="spacerbig">

                    </div>
                    <div class="container">
                        <div class="row justify-content-center mb-3">
                            <div class="col-10 col-md-6 text-center">
                                <a href="index.php" class="none">
                                    <h1>
                                        <?php
                                        if (empty($logoUrl)) {
                                            echo $title;
                                        } else {
                                            echo '<img src="' . $logoUrl . '" class="topLogo" title="' . $title . '" alt="' . $title . '"><br>
                                            <b style="font-size: 16px; font-weight: 400;">This is the link you can share!</b>';
                                        }
                                        ?>
                                    </h1>
                                </a>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-10 col-md-6 text-center">
                                <input type="text" style="text-align: center;" readonly class="form-control" value="<?php echo $url; ?>">
                                <div id="emailHelp" class="form-text"><b>WARNING!</b> Don't open this link for yourself, unless you want the text to be deleted.</div>
                                <br><br>

                                <br>
                                <a class="btn btn-primary btn-lg px-4 gap-3 mt-5" href="index.php">+ Create a new paste</a>
                            </div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <div class="col-10 col-md-6 text-center">
                                <span class="text-secondary">
                                    <?php echo $copyright; ?><br>
                                    <a href="https://www.ldoerr.com/tools/text-burner" target="_blank" rel="noopener noreferrer">Host your own TextBurner for free!</a>
                                </span>
                            </div>
                        </div>
                    </div>


                    <script src="resources/js/bootstrap.bundle.min.js"></script>
                </body>

                </html>
                <?php

            }
        }
        if ($_GET['do'] == 's') {
            if (!isset($_GET['id'])) {
                header('location: index.php?err=3');
                exit;
            } else {
                $id = mysqli_real_escape_string($con, $_GET['id']);

                $get_id = mysqli_query($con, "SELECT `content` FROM `redirects` WHERE `used` = 0 AND `id` = '$id' LIMIT 1;");
                if (!mysqli_num_rows($get_id)) {
                    //Allready viewed or deleted
                    header('location: index.php?err=4');
                    exit;
                } else {
                    $info = mysqli_fetch_assoc($get_id);

                    if ($deleteAfterRead) {
                        $delete_query = mysqli_query($con, "DELETE FROM `redirects` WHERE `id` = '$id';");
                    } else {
                        $delete_query = mysqli_query($con, "UPDATE `redirects` SET `used` = 1 WHERE `id` = '$id';");
                    }

                    $content = stripslashes($info['content']);

                ?>
                    <!DOCTYPE html>
                    <html lang="en">
                    <!--
                        DON'T REMOVE THIS COPYRIGHT NOTICE!
                        This little tool was created by ldoerr.com
                        Host your own for free on https://www.ldoerr.com/tools/text-burner
                    -->

                    <head>
                        <meta charset="UTF-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title><?php echo $title; ?></title>
                        <?php echo $additionalHeaders; ?>
                        <link rel="stylesheet" href="resources/css/bootstrap.min.css">
                        <link rel="stylesheet" href="resources/css/style.css">
                    </head>

                    <body>
                        <div class="spacerbig">

                        </div>
                        <div class="container">
                            <div class="row justify-content-center mb-3">
                                <div class="col-10 col-md-6 text-center">
                                    <a href="index.php" class="none">
                                        <h1>
                                            <?php
                                            if (empty($logoUrl)) {
                                                echo $title;
                                            } else {
                                                echo '<img src="' . $logoUrl . '" class="topLogo" title="' . $title . '" alt="' . $title . '"><br>
                                    <b style="font-size: 16px; font-weight: 400;">This is your private message:</b>';
                                            }
                                            ?>
                                        </h1>
                                    </a>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-10 col-md-6 text-center">
                                    <textarea readonly class="form-control"><?php echo $content; ?></textarea>
                                    <div id="emailHelp" class="form-text"><b>WARNING!</b> This text is now deleted. If you reaload this page everything is reset.</div>
                                    <a class="btn btn-primary btn-lg px-4 gap-3 mt-5" href="index.php">+ Create a new paste</a>
                                </div>
                            </div>
                            <div class="row mt-5 justify-content-center">
                                <div class="col-10 col-md-6 text-center">
                                    <span class="text-secondary">
                                        <?php echo $copyright; ?><br>
                                        <a href="https://www.ldoerr.com/tools/text-burner" target="_blank" rel="noopener noreferrer">Host your own TextBurner for free!</a>
                                    </span>
                                </div>
                            </div>
                        </div>


                        <script src="resources/js/bootstrap.bundle.min.js"></script>
                    </body>

                    </html>
<?php

                }
            }
        }
    }
}






function idGenerator($length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}



mysqli_close($con);
