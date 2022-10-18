<div class="hidden">
    <nav id="off-canvas-menu"><span class="icon icon-xl flaticon-delete30" id="off-canvas-menu-close"></span>
        <ul class="expander-list">
            <?php foreach ($categories as $categorie) { ?>
                <?php if (isset($categorie['childs'])) { ?>
                    <li><span class="name"><span class="expander">-</span><a href="<?= $categorie['alias'] ?>"><?= $categorie['name'] ?></a></span>
                        <ul>
                            <?php foreach ($categorie['childs'] as $child) { ?>
                                <li><span class="name"><a href="<?= $child['alias'] ?>"><?= $child['name'] ?></a></span></li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                    <li><span class="name"><a href="<?= $categorie['alias'] ?>"><?= $categorie['name'] ?></a></span></li>
                        <?php } ?>
                    <?php } ?>
        </ul>
    </nav>
</div>