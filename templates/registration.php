<main>
    <?=render('templates/_cat-menu.php', ['categories' => $categories]);?>
    <?php $class_name = isset($errors) ? 'form--invalid' : ''; ?>
    <form class="form container <?=$class_name?>" action="registration.php" method="post" enctype="multipart/form-data" novalidate> <!-- form--invalid -->
        <h2>Регистрация нового аккаунта</h2>
        <?php $class_name = isset($errors['email']) ? 'form__item--invalid' : '';
        $value = isset($form['email']) ? $form['email'] : '';
        $error = isset($errors['email']) ? $errors['email'] : ""?>
        <div class="form__item <?=$class_name?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$value?>" required>
            <span class="form__error"><?=$error?></span>
        </div>
        <?php $class_name = isset($errors['password']) ? 'form__item--invalid' : '';
        $value = isset($form['password']) ? $form['password'] : '';
        $error = isset($errors['password']) ? $errors['password'] : ""?>
        <div class="form__item <?=$class_name?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="password" placeholder="Введите пароль" required>
            <span class="form__error"><?=$error?></span>
        </div>
        <?php $class_name = isset($errors['name']) ? 'form__item--invalid' : '';
        $value = isset($form['name']) ? $form['name'] : '';
        $error = isset($errors['name']) ? $errors['name'] : ""?>
        <div class="form__item <?=$class_name?>">
            <label for="name">Имя*</label>
            <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=$value?>" required>
            <span class="form__error"><?=$error?></span>
        </div>
        <?php $class_name = isset($errors['address']) ? 'form__item--invalid' : '';
        $value = isset($form['address']) ? $form['address'] : '';
        $error = isset($errors['address']) ? $errors['address'] : ""?>
        <div class="form__item <?=$class_name?>">
            <label for="address">Контактные данные*</label>
            <textarea id="address" name="address" placeholder="Напишите как с вами связаться" required><?=$value?></textarea>
            <span class="form__error"><?=$error?></span>
        </div>
        <?php $class_name = isset($errors['avatar']) ? 'form__item--invalid' : '';
        $error = isset($errors['avatar']) ? $errors['avatar'] : ""?>
        <div class="form__item form__item--file form__item--last <?=$class_name?>">
            <label>Аватар</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
                </div>

            </div>
            <div class="form__input-file">
                <input class="visually-hidden" name="avatar" type="file" id="photo2" value="">
                <label for="photo2">
                    <span>+ Добавить изображение</span>
                </label>
            </div>
            <span class="form__error"><?=$error?></span>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
</main>
