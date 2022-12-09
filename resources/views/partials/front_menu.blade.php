<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4 text-white" href="/projects">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("projects.index") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.overview') }}
            </a>
        </li>

        @can('project_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("projects.index") }}" class="c-sidebar-nav-link {{ request()->is("/projects") || request()->is("/projects/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-project-diagram c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.project.title') }}
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a href="{{ route("shared.index") }}" class="c-sidebar-nav-link {{ request()->is("/shared") || request()->is("/shared/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-project-diagram c-sidebar-nav-icon">

                    </i>
                    {{ trans('global.shared_with_me') }}
                </a>
            </li>
        @endcan

        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
