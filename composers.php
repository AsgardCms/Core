<?php

View::creator('partials.sidebar-nav', 'Modules\Core\Composers\SidebarViewCreator');
View::composer('layouts.master', 'Modules\Core\Composers\MasterViewComposer');
View::composer('core::fields.select-theme', 'Modules\Core\Composers\ThemeComposer');
View::composer('*', 'Modules\Core\Composers\LocaleComposer');
