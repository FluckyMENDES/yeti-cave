<? if ($pages_count > 1) : ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev <? if ($cur_page == 1) : ?>pagination-item-inactive<?endif;?>">
            <a <? if ($cur_page != 1) : ?> href="?page=<?=($cur_page-1)?><?=insert_get_attributes('page')?>" <? endif; ?>>Назад</a>
        </li>
        <? foreach ($pages as $page) { ?>
            <li class="pagination-item <? if($page == $cur_page):?>pagination-item-active<?php endif; ?>">
                <a <? if($page != $cur_page):?>href="?page=<?=$page?><?=insert_get_attributes('page')?>" <? endif;?> ><?=$page?></a>
            </li>
        <? } ?>
        <??>
        <li class="pagination-item pagination-item-next <? if ($cur_page == $pages_count) : ?>pagination-item-inactive<?endif;?>">
            <a <? if ($cur_page != $pages_count) : ?> href="?page=<?=($cur_page+1)?><?=insert_get_attributes('page')?>" <?endif;?> >Вперед</a>
        </li>
    </ul>
<? endif;?>

