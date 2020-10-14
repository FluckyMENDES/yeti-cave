<main>
    <?=render('templates/_cat-menu.php', ['categories' => $categories]);?>
    <form novalidate class="form container" action="login.php" method="POST"> <!-- form--invalid -->
        <h2>Вход</h2>
        <?php $class_name = isset($errors['email']) ? 'form__item--invalid' : '';
        $value = isset($form['email']) ? $form['email'] : '';
        $error = isset($errors['email']) ? $errors['email'] : ""?>
        <div class="form__item <?=$class_name?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$value?>" required>
            <span class="form__error"><?=$error?></span>
        </div>
        <?php $class_name = isset($errors['password']) ? 'form__item--invalid' : '';

        $error = isset($errors['password']) ? $errors['password'] : ""?>
        <div class="form__item form__item--last  <?=$class_name?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="password" placeholder="Введите пароль" required>
            <span class="form__error"><?=$error?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
