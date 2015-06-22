<?php

View::creator('partials.sidebar-nav', 'Modules\Core\Composers\SidebarViewCreator');
View::composer('layouts.master', 'Modules\Core\Composers\MasterViewComposer');
View::composer('core::fields.select-theme', 'Modules\Core\Composers\ThemeComposer');
View::composer('core::fields.select-locales', 'Modules\Core\Composers\SettingLocalesComposer');
View::composer('*', 'Modules\Core\Composers\LocaleComposer');
