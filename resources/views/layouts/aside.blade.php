@php
    $page = config('site.page');
@endphp
<div class="app-sidebar__user"><img class="app-sidebar__user-avatar"src="{{asset('images/avatar.png')}}" alt="User Image">
    <div>
        <p class="app-sidebar__user-name">{{Auth::user()->name}}</p>
        <p class="app-sidebar__user-designation">Admin</p>
    </div>
</div>
<ul class="app-menu">
    <li><a class="app-menu__item " href="{{route('home')}}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
    <li><a class="app-menu__item @if($page == 'owner') active @endif" href="{{route('owner.index')}}"><i class="app-menu__icon fa fa-bars"></i><span class="app-menu__label">Owners</span></a></li>
    <li><a class="app-menu__item @if($page == 'setting') active @endif" href="{{route('setting.index')}}"><i class="app-menu__icon fa fa-cog"></i><span class="app-menu__label">Setting</span></a></li>
</ul>
