<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
    <div class="navbar-wrapper">
        <div class="navbar-toggle">
            <button type="button" class="navbar-toggler sidebar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </button>
        </div>
        <a class="navbar-brand" href="#">{{ __('Sistem Manajemen Pelaporan & Perbaikan Fasilitas') }}</a>

        <!-- Search Form (Visible on Large Screens) -->
        <form class="navbar-search-form d-none d-lg-flex">
            <div class="input-group no-border">
                <input type="text" id="nav-search-input" class="form-control" placeholder="Cari menu...">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="nc-icon nc-zoom-split"></i>
                    </div>
                </div>
            </div>
            <div class="search-results d-none"></div>
        </form>

        <!-- Right Group (Always on the Far Right) -->
        <div class="navbar-right-group">
            <!-- Kebab Toggler (Visible on Medium/Small Screens) -->
            <button class="navbar-toggler kebab-toggler d-lg-none" type="button" data-toggle="collapse"
                data-target="#navbar-content">
                <span class="navbar-toggler-bar navbar-kebab"></span>
                <span class="navbar-toggler-bar navbar-kebab"></span>
                <span class="navbar-toggler-bar navbar-kebab"></span>
            </button>

            <!-- Navbar Content (Collapsible) -->
            <div class="collapse navbar-collapse" id="navbar-content">
                <!-- Search Form (Hidden on Large Screens) -->
                <form class="navbar-search-form d-lg-none">
                    <div class="input-group no-border">
                        <input type="text" class="form-control" placeholder="Cari menu...">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="nc-icon nc-zoom-split"></i>
                            </div>
                        </div>
                    </div>
                    <div class="search-results d-none"></div>
                </form>

                <ul class="navbar-nav">
                    <li class="nav-item btn-rotate dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="nc-icon nc-bell-55"></i>
                            @if (in_array(auth()->user()->id_level, [2, 3, 4, 5, 6]))
                                 @if ($unreadNotifications->count() > 0)
                                    <span class="badge badge-danger">{{ $unreadNotifications->count() }}</span>
                                @endif
                            @endif
                            <p>
                                <span class="d-lg-none d-md-block">{{ __('Notifications') }}</span>
                            </p>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" style="cursor: pointer" aria-labelledby="navbarDropdownMenuLink">
                            @forelse (Auth::user()->unreadNotifications as $notification)
                                @if (auth()->user()->id_level == 3)
                                    <a class="dropdown-item" onclick="modalAction('{{ $notification->data['link'] ?? '#' }}')"
                                        onclick="markNotificationAsRead('{{ $notification->id }}')">
                                        <strong>{{ $notification->data['fasilitas'] ?? 'Tugas Baru' }}</strong>
                                        <br>
                                        <small>{{ $notification->data['keterangan'] ?? 'Tidak ada deskripsi' }}</small>
                                        <br>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </a>
                                @elseif (auth()->user()->id_level == 2)
                                    <a class="dropdown-item" onclick="modalAction('{{ $notification->data['link'] ?? '#' }}')"
                                        onclick="markNotificationAsRead('{{ $notification->id }}')">
                                        <strong>{{ $notification->data['tipe'] ?? 'tidak ada penugasan' }}</strong>
                                        <br>
                                        <small>{{ $notification->data['pesan'] ?? 'Tidak ada Pesan' }}</small>
                                        <br>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </a>
                                @elseif (in_array(auth()->user()->id_level, [4, 5, 6]))
                                    <a class="dropdown-item" onclick="modalAction('{{ $notification->data['link'] ?? '#' }}')"
                                        onclick="markNotificationAsRead('{{ $notification->id }}')">
                                        <strong>{{ $notification->data['fasilitas'] ?? 'tidak ada fasilitas' }}</strong>
                                        <br>
                                        <small>{{ $notification->data['status'] ?? 'Tidak ada status' }}</small>
                                        <br>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </a>
                                @endif
                            @empty
                                <a class="dropdown-item" href="#">Tidak ada pemberitahuan</a>
                            @endforelse
                            @if (Auth::user()->unreadNotifications->count() > 0)
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center" href="#" onclick="markAllNotificationsAsRead()">Tandai
                                    semua telah di baca</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center" href="{{ route('notifications.history') }}">Lihat Semua Notifikasi</a>
                        </div>
                    </li>
                    <li class="nav-item btn-rotate dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="nc-icon nc-settings-gear-65"></i>
                            <p>
                                <span class="d-lg-none d-md-block">{{ __('Account') }}</span>
                            </p>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink2">
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
                            <form id="formLogOut" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="dropdown-item" href="#" onclick="confirmLogout(event)">{{ __('Log out') }}</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<style>
    .dropdown-menu {
        min-width: 300px;
        font-size: 16px;
        max-height: 400px;
        overflow-y: auto;
    }

    .dropdown-item {
        padding: 10px 15px;
    }

    .dropdown-item small {
        font-size: 14px;
    }
</style>
<script>
    function markNotificationAsRead(notificationId) {
        fetch('/notifications/' + notificationId + '/mark-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Optionally, remove the notification from the dropdown or update the count
                    location.reload(); // Simple reload to update the UI
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
    }

    function markAllNotificationsAsRead() {
        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error marking all notifications as read:', error));
    }
</script>