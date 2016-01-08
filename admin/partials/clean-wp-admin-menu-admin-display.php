<div class="wrap">

    <h1><?php esc_html_e('Clean Admin Menu', $pluginName); ?></h1>


    <div class="toggle-extra">
        <?php
        // Reminder for parent menu items array
        // 0 = menu_title, 1 = capability, 2 = menu_slug, 3 = page_title, 4 = classes, 5 = hookname, 6 = icon_url

        ?>
        <p class="toggle-extra__desc">
            <?php esc_html_e("WordPress admin menu tends get out of hand when you have lots of plugins and post types.", $pluginName); ?>
            <?php esc_html_e("With this plugin you can hide admin menu items that you don't use frequently.", $pluginName); ?>
        </p>

        <p class="toggle-extra__desc">
            <?php esc_html_e('Hidden items will appear when they are current item or Toggle Extra button is pressed', $pluginName); ?>
            .
        </p>

        <p class="toggle-extra__desc">
            <strong><?php esc_html_e('Select the menu items you want to hide by default.', $pluginName); ?></strong>
        </p>

        <form action="<?php echo esc_attr(admin_url('options-general.php?page=clean-wp-admin-menu_options')); ?>"
              method="post">
            <?php wp_nonce_field($this->nonceName, $this->nonceName, true, true); ?>
            <ul class="toggle-extra__list">
                <?php foreach ($menu as $key => $menuItem): ?>
                    <?php

                    $isSeparator = strpos($menuItem[4], 'wp-menu-separator');
                    $isSelected  = in_array($menuItem[2], $selectedItems);
                    ?>
                    <?php if ($isSeparator === 0): ?>
                        <li class="toggle-extra__list-sep"></li>
                    <?php endif; ?>
                    <?php
                    if ($isSeparator !== false OR $menuItem[2] === 'toggle_extra') {
                        continue;
                    }
                    ?>
                    <li class="toggle-extra__list-item">
                        <label for="toggle_extra_item_<?php echo $key; ?>">
                            <input type="checkbox" name="toggle_extra_items[]" value="<?php echo $menuItem[2]; ?>"
                                   id="toggle_extra_item_<?php echo $key; ?>"
                                <?php echo ($isSelected) ? 'checked' : ''; ?>
                                <?php echo ($menuItem[2] === 'index.php') ? 'disabled' : ''; ?> />
                            <?php if ($isSelected): ?>
                                <?php echo $menuItem[0]; ?>
                            <?php else: ?>
                                <strong>
                                    <?php echo $menuItem[0]; ?>
                                </strong>
                            <?php endif; ?>
                            <?php if ($isSelected): ?>
                                <span class="dashicons-before dashicons-hidden"></span>
                            <?php endif; ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
            <input type="submit" class="button-primary" value="<?php esc_html_e('SAVE', $pluginName); ?>"/>
        </form>


        <div class="toggle-extra__credits"><?php echo esc_html_e('Plugin by ',$pluginName);?><a href="http://borayalcin.me" target="_blank">Bora Yalcin</a></div>
    </div>


</div><!-- .wrap -->