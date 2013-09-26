A lightweight Captcha for PHP5.

Tested on PHP 5.2.0+

Features

    Extremely simple configuration.
    
Let's See Some Code

Make a form with captcha:

    <form action="check.php" method="post">
    <label for="captcha">Type symbols from picture</label><br />
    <input type="text" name="captcha"/><br />
    <img src="render.php" id="render"/><br />
    <input type="submit" value="Send"/>
    </form>


Generate captcha with Captcha class in render.php:

    <?php
    session_start();
    require_once 'captcha.php';
    
    $captcha = new Captcha();
    $captcha->create();
    ?>


Method to validate captcha in check.php:

    <?php
    session_start();
    if ($_POST['captcha'] == $_SESSION['str_cap']) {
        echo 'OK. Captcha valid';
    }
    else {
        echo 'ERROR. Captcha failed';
    }
    ?>


jQuery code to update render of image in the form with captcha

    <script type="text/javascript">
    $(function() {
        $('#render').click(function() {
            $(this).attr('src', 'render.php?id=' + Math.random());
        })
    })
    </script>

