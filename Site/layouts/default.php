<html>
    <head>
        <title><?php echo $this->getTitle(); ?></title>
        <?php echo $this->renderCss(); ?>
        <?php echo $this->renderJs(); ?>
    </head>
    <body>
        <h1><?php echo $this->getTitle(); ?></h1>
        <div class="menu">
            <ul>
                <li><a href="#">Главная</a></li>
                <li><a href="#">Фото</a></li>
                <li><a href="#">Ссылки</a></li>
            </ul>

            <div class="auth-form">
                <form method="post" action="/auth/login">
                    <label>Ключ для входа:<input type="password" name="auth[key]"/></label>
                    <input type="submit" value="Войти"/>
                </form>
            </div>
        </div>
        <div class="content">
            <?php include $TEMPLATE_PATH; ?>
        </div>
    </body>
</html>