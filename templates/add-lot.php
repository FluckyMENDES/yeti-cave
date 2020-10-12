<main>
    <nav class="nav">
        <ul class="nav__list container">
            <li class="nav__item">
                <a href="all-lots.html">Доски и лыжи</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Крепления</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Ботинки</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Одежда</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Инструменты</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Разное</a>
            </li>
        </ul>
    </nav>
    <?php $class_name = isset($errors) ? 'form--invalid' : '' ?>
    <form novalidate class="form form--add-lot container <?=$class_name?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php $class_name = isset($errors['Наименование']) ? 'form__item--invalid' : '';
            $value = isset($good['title']) ? $good['title'] : '';
            $error = isset($errors['Наименование']) ? $errors['Наименование'] : ""?>
            <div class="form__item <?= $class_name ?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="title" placeholder="Введите наименование лота" value="<?= $value ?>" required >
                <span class="form__error"><?=$error?></span>
            </div>
            <?php $class_name = isset($errors['Категория']) ? 'form__item--invalid' : '';
            $value = isset($good['category']) ? $good['category'] : '';
            $error = isset($errors['Категория']) ? $errors['Категория'] : ""?>
            <div class="form__item <?= $class_name ?>">
                <label for="category">Категория</label>
                <select id="category" name="category" required>
                    <?
                        foreach ($categories as $category) { ?>
                            <option><?= $category['category'] ?></option>
                    <?php } ?>
                </select>
                <span class="form__error"><?=$error?></span>
            </div>
        </div>
        <?php $class_name = isset($errors['Описание']) ? 'form__item--invalid' : '';
        $value = isset($good['description']) ? $good['description'] : '';
        $error = isset($errors['Описание']) ? $errors['Описание'] : ""?>
        <div class="form__item form__item--wide <?=$class_name?>">
            <label for="message">Описание</label>
            <textarea id="message" name="description" placeholder="Напишите описание лота" required><?= $value ?></textarea>
            <span class="form__error"><?=$error?></span>
        </div>
        <?php $class_name = isset($errors['Изображение']) ? 'form__item--invalid' : '';
        $value = isset($good['img']) ? $good['img'] : '';
        $error = isset($errors['Изображение']) ? $errors['Изображение'] : ""?>
        <div class="form__item form__item--file <?=$class_name?>"> <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="<?=$value?>" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="photo2" value="" name="img">
                <label for="photo2">
                    <span class="form__file-button">+ Добавить</span>
                </label>
                <span class="form__error"><?=$error?></span>
            </div>
        </div>
        <div class="form__container-three">
            <?php $class_name = isset($errors['Начальная цена']) ? 'form__item--invalid' : '';
            $value = isset($good['start_price']) ? $good['start_price'] : '';
            $error = isset($errors['Начальная цена']) ? $errors['Начальная цена'] : ""?>
            <div class="form__item form__item--small <?=$class_name?>">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="number" name="start_price" placeholder="0" value="<?=$value?>" required>
                <span class="form__error"><?=$error?></span>
            </div>
            <?php $class_name = isset($errors['Шаг ставки']) ? 'form__item--invalid' : '';
            $value = isset($good['price_step']) ? $good['price_step'] : '';
            $error = isset($errors['Шаг ставки']) ? $errors['Шаг ставки'] : ""?>
            <div class="form__item form__item--small <?=$class_name?>">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="number" name="price_step" placeholder="0" value="<?=$value?>" required>
                <span class="form__error"><?=$error?></span>
            </div>
            <?php $class_name = isset($errors['Дата окончания торгов']) ? 'form__item--invalid' : '';
            $value = isset($good['end_date']) ? $good['end_date'] : '';
            $error = isset($errors['Дата окончания торгов']) ? $errors['Дата окончания торгов'] : ""?>
            <div class="form__item <?=$class_name?>">
                <label for="lot-date">Дата окончания торгов</label>
                <input class="form__input-date" id="end_date" type="date" name="end_date" value="<?=$value?>" required>
                <span class="form__error"><?=$error?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom"></span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
