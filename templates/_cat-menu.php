<nav class="nav">
    <ul class="nav__list container">
        <?php
        foreach ($categories as $category) { ?>
            <li class="nav__item <?if($cur_category == $category['eng']) :?>nav__item--current<?endif;?>">
                <a href="all-lots.php?category=<?=$category['eng'];?>"><?=$category['category']?></a>
            </li>
        <?php    }
        ?>
    </ul>
</nav>
