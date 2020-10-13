<main>
    <nav class="nav">
        <ul class="nav__list container">
            <? foreach ($categories as $category) {?>
                <li class="nav__item">
                    <a href="all-lots.html"><?=$category['category']?></a>
                </li>
            <? }?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <? if(count($goods)) : ?>
            <h2>Результаты поиска по запросу «<span><?=$search?></span>»</h2>
            <ul class="lots__list">
                <?php
                foreach ($goods as $good) { ?>
                    <a class="text-link" href="lot.php?id=<?=$good['id']?>">
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="<?=$good['img']?>" width="350" height="260" alt="<?=$good['title']?>">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?=$good['category']?></span>
                                <h3 class="lot__title"><?=$good['title']?></h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">12 ставок</span>
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
                <? else :?>
                    <h2>По вашему запросу «<span><?=$search?></span>» ничего не найдено</h2>
                <? endif; ?>
            </ul>
        </section>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
            <li class="pagination-item pagination-item-active"><a>1</a></li>
            <li class="pagination-item"><a href="#">2</a></li>
            <li class="pagination-item"><a href="#">3</a></li>
            <li class="pagination-item"><a href="#">4</a></li>
            <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
        </ul>
    </div>
</main>
