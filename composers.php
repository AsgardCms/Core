<?php

View::creator('partials.sidebar-nav', 'Modules\Core\Composers\SidebarViewCreator');
view()->composer('partials.footer', \Modules\Core\Composers\ApplicationVersionViewComposer::class);
View::composer('layouts.master', 'Modules\Core\Composers\MasterViewComposer');
View::composer('core::fields.select-theme', 'Modules\Core\Composers\ThemeComposer');
View::composer('core::fields.select-locales', 'Modules\Core\Composers\SettingLocalesComposer');
View::composer('*', 'Modules\Core\Composers\LocaleComposer');
