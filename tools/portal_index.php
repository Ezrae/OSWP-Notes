<!DOCTYPE html>
<html lang="en">

        <head>
                <link href="assets/css/style.css" rel="stylesheet">
                <title>MegaCorp One - Nanotechnology Is the Future</title>
        </head>
        <body style="background-color:#000000;">
                <div class="navbar navbar-default navbar-fixed-top" role="navigation">
                        <div class="container">
                                <div class="navbar-header">
                                        <a class="navbar-brand" style="font-family: 'Raleway', sans-serif;font-weight: 900;" href="index.php">MegaCorp One</a>
                                </div>
                        </div>
                </div>

                <div id="headerwrap" class="old-bd">
                        <div class="row centered">
                                <div class="col-lg-8 col-lg-offset-2">
                                        <?php
                                                if (isset($_GET["success"])) {
                                                        echo '<h3>Login successful</h3>';
                                                        echo '<h3>You may close this page</h3>';
                                                } else {
                                                        if (isset($_GET["failure"])) {
                                                                echo '<h3>Invalid network key, try again</h3><br/><br/>';
                                                        }
                                        ?>
                                <h3>Enter network key</h3><br/><br/>
                                <form action="login_check.php" method="post">
                                        <input type="password" id="passphrase" name="passphrase"><br/><br/>
                                        <input type="submit" value="Connect"/>
                                </form>
                                <?php
                                                }
                                ?>
                                </div>

                                <div class="col-lg-4 col-lg-offset-4 himg ">
                                        <i class="fa fa-cog" aria-hidden="true"></i>
                                </div>
                        </div>
                </div>

        </body>
</html>
