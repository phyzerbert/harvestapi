@php
    $page = config('site.page');
@endphp
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item @if($page == 'home') active @endif">
            <a class="nav-link" href="{{route('home')}}">Home</a>
        </li>
        @if (Auth::user()->role == 'admin')
            <li class="nav-item @if($page == 'hidden') active @endif">
                <a class="nav-link" href="{{route('hidden_projects')}}">Banned Projects</a>
            </li>
            <li class="nav-item @if($page == 'user') active @endif">
                <a class="nav-link" href="{{route('user.index')}}">User Management</a>
            </li>
            <li class="nav-item @if($page == 'owner') active @endif">
                <a class="nav-link" href="{{route('owner.index')}}">Project Owners</a>
            </li>
            <li class="nav-item @if($page == 'setting') active @endif">
                <a class="nav-link" href="{{route('setting.index')}}">Settings</a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="{{route('logout')}}" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">Log Out</a>
        </li>
    </ul>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>