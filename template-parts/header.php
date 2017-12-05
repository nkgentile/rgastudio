<section id="header-main" :class="{ open: isMenuOpen }">
    <navigation-block>
        <wp-site-icon
            url="<?php echo get_site_icon_url(); ?>"
        >
        </wp-site-icon>
        <h1 id="site-title"><?php echo bloginfo('title'); ?></h1>
        <?php wp_nav_menu([ 'menu' => 'Navigation' ]); ?>
    </navigation-block>
    <i id="close" class="menu-switch fa fa-times fa-2x" @click="closeMenu"></i>
</section>
