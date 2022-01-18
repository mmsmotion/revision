<a href="{{ route($name) }}" class="nav-link {{ request()->url() == route($name) ? 'active' : '' }}">
    {{ $slot }}
</a>
