<?php

view()->creator('partials.sidebar-nav', 'Modules\Core\Composers\SidebarViewCreator');
view()->composer('partials.footer', \Modules\Core\Composers\ApplicationVersionViewComposer::class);
view()->composer('layouts.master', 'Modules\Core\Composers\MasterViewComposer');
view()->composer('core::fields.select-theme', 'Modules\Core\Composers\ThemeComposer');
view()->composer('core::fields.select-locales', 'Modules\Core\Composers\SettingLocalesComposer');
view()->composer('*', 'Modules\Core\Composers\LocaleComposer');
