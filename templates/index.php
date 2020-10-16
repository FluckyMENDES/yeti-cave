<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>

        <ul class="promo__list">
            <? foreach ($categories as $category) {?>
                <li class="promo__item promo__item--<?=$category['eng'];?>">
                    <a class="promo__link" href="all-lots.php?category=<?=$category['eng'];?>"><?=$category['category'];?></a>
                </li>
            <? }?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">

            <?php
            foreach ($goods as $good) { ?>
                <a class="text-link" href="lot.php?id=<?=$good['id']?>">
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=$good['img']?>"  alt="<?=$good['title']?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$good['category']?></span>
                        <h3 class="lot__title"><?=$good['title']?></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Текущая цена</span>
                                <span class="lot__cost"><?=format_price($good['current_price']);?></span>
                            </div>
                            <div class="lot__timer timer <?=add_timer_class($good['end_date'])?>">
                                <?= get_time_left($good['end_date']) ?>
                            </div>
                        </div>
                    </div>
                </li>
                </a>
            <?php  }
            ?>
        </ul>
        <?=render('templates/_pagination.php', ['pages' => $pages, 'pages_count' => $pages_count, 'cur_page' => $cur_page]) ?>
    </section>
</main>
