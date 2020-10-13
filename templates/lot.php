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

  <section class="lot-item container">
    <?php if (isset($good)) : ?> <!-- Если в шаблон передан массив с данными о товаре -->
    <h2><?=$good['title']?></h2>
    <div class="lot-item__content">
      <div class="lot-item__left">
        <div class="lot-item__image">
          <img src="<?=$good['img']?>" alt="Сноуборд">
        </div>
        <p class="lot-item__category">Категория: <span><?=$good['category']?></span></p>
        <p class="lot-item__description"><?= $good['description'] ?></p>
      </div>
      <div class="lot-item__right">
        <div class="lot-item__state">
          <div class="lot-item__timer timer <?= add_timer_class($good['end_date']);?>">
              <?= get_time_left($good['end_date']);?>
          </div>
          <div class="lot-item__cost-state">
            <div class="lot-item__rate">
              <span class="lot-item__amount">Текущая цена</span>
              <span class="lot-item__cost"><?=format_price($good['current_price'])?></span>
            </div>
            <div class="lot-item__min-cost">
                Мин. ставка <span><?=format_price(($good['current_price'] + $good['price_step'])) ?></span>
            </div>
          </div>
            <?php if ($_SESSION['user']) :?>
          <form class="lot-item__form" action="add-bid.php" method="post">
            <p class="lot-item__form-item">
              <label for="cost">Ваша ставка</label>
              <input id="cost" type="number" min="<?=$good['current_price'] + $good['price_step']?>" name="bid" placeholder="<?=$good['current_price'] + $good['price_step']?>">
              <input name="good_id" value="<?=$_GET['id']?>" style="display: none">
            </p>
            <button type="submit" class="button">Сделать ставку</button>
          </form>
            <? endif; ?>
        </div>
        <div class="history">
            <h3>История ставок (<span><?=count($bids);?></span>)</h3>
            <table class="history__list">
            <?php
                foreach ($bids as $bid) { ?>
                    <tr class="history__item">
                        <td class="history__name"><?=$bid['name'];?></td>
                        <td class="history__price"><?=format_price($bid['amount']);?></td>
                        <td class="history__time"><?=format_date($bid['date']);?></td>
                    </tr>
                <?php
                }
            ?>
          </table>
        </div>
      </div>
    </div>
    <?php else : ?> <!--Иначе-->
      <h1>Товар с данным ID не найден.</h1>
    <?php endif; ?>
  </section>
</main>
