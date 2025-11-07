<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('assets/logo/logo-light.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('assets/logo/logo-light.png') }}" alt="" height="17">
                        </span>
                    </a>

                    <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('assets/logo/logo-light.png') }}" alt=""
                                height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('assets/logo/logo-light.png') }}" alt="" height="17">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <!-- App Search-->

            </div>

            <div class="d-flex align-items-center">

                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-bell fs-22"></i>
                        <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger" id="notification-badge">
                            <span class="notification-count">0</span>
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                        <div class="dropdown-head bg-primary bg-pattern rounded-top">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold text-white">Notifications</h6>
                                    </div>
                                    <div class="col-auto dropdown-tabs">
                                        <span class="badge badge-soft-light fs-13" id="notification-count">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content" id="notificationItemsTabContent">
                            <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                <div data-simplebar style="max-height: 200px; overflow-y: auto;" class="pe-2" id="notification-list">
                                    <!-- Notifications will be loaded via AJAX -->
                                    <div class="text-center" id="no-notifications">
                                        <div class="avatar-md mx-auto my-3">
                                            <div class="avatar-title bg-light text-info rounded-circle fs-24">
                                                <i class="ri-notification-off-line"></i>
                                            </div>
                                        </div>
                                        <h5 class="mb-3">No new notifications</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>
                        <div class="text-center p-2">
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-soft-info btn-sm">
                                View All Notifications <i class="ri-arrow-right-s-line align-middle"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        data-toggle="fullscreen">
                        <i class='bx bx-fullscreen fs-22'></i>
                    </button>
                </div>

                {{-- <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-moon fs-22'></i>
                    </button>
                </div> --}}

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="{!! Helpers::image(Auth::guard('admin')->user()->photo, 'admin/images/', 'user.png') !!}"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span
                                    class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::guard('admin')->user()->name }}</span>
                                <span
                                    class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">{{ Auth::guard('admin')->user()->IsSuper()? 'Founder': 'Staff' }}</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome {{ Auth::guard('admin')->user()->name }}!</h6>
                        <a class="dropdown-item" href="{{ route('admin.profile') }}"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profile</span></a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('admin.generalsettings') }}"><span
                                class="badge bg-soft-success text-success mt-1 float-end">New</span><i
                                class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Settings</span></a>
                        <a class="dropdown-item" href="{{ route('admin.password') }}"><i
                                class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Change Password</span></a>
                        <a class="dropdown-item " href="javascript:void();"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="bx bx-power-off font-size-16 align-middle me-1"></i> <span
                                key="t-logout">@lang('translation.logout')</span></a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function getNotificationStyle(type) {
            const styles = {
                'new_user_comment': {
                    icon: 'ri-chat-3-line',
                    bg: 'bg-soft-primary text-primary'
                },
                'new_evidence': {
                    icon: 'ri-file-add-line',
                    bg: 'bg-soft-info text-info'
                },
                'claim_submitted': {
                    icon: 'ri-file-list-3-line',
                    bg: 'bg-soft-success text-success'
                },
                'claim_approved': {
                    icon: 'ri-checkbox-circle-line',
                    bg: 'bg-soft-success text-success'
                },
                'claim_rejected': {
                    icon: 'ri-close-circle-line',
                    bg: 'bg-soft-danger text-danger'
                },
                'default': {
                    icon: 'ri-notification-3-line',
                    bg: 'bg-soft-info text-info'
                }
            };
            
            return styles[type] || styles['default'];
        }
        
        function renderNotification(notification) {
            const style = getNotificationStyle(notification.type);
            
            return `
                <div class="text-reset notification-item d-block dropdown-item position-relative" data-notification-id="${notification.id}">
                    <div class="d-flex">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title ${style.bg} rounded-circle fs-16">
                                <i class="${style.icon}"></i>
                            </span>
                        </div>
                        <div class="flex-1 notification-list-content">
                            <a onclick="openClaim('${notification.link}', ${notification.id})" class="stretched-link">
                                <h6 class="mt-0 mb-1 lh-base">${notification.title}</h6>
                            </a>
                            <p class="mb-1 fs-13 text-muted">${notification.message}</p>
                            <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                <span><i class="mdi mdi-clock-outline"></i> ${notification.created_at}</span>
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function fetchNotifications() {
            fetch('{{ route("admin.notifications.list") }}')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notification-list');
                    const noNotifications = document.getElementById('no-notifications');
                    
                    if (data.notifications && data.notifications.length > 0) {
                        noNotifications.style.display = 'none';
                        
                        const notificationsHtml = data.notifications.map(renderNotification).join('');
                        notificationList.innerHTML = notificationsHtml;
                    } else {
                        noNotifications.style.display = 'block';
                        const existingNotifications = notificationList.querySelectorAll('.notification-item');
                        existingNotifications.forEach(item => item.remove());
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                });
        }
        
        function fetchNotificationCount() {
            fetch('{{ route("admin.notifications.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    const count = data.count;
                    document.querySelector('.notification-count').textContent = count;
                    document.getElementById('notification-count').textContent = count;
                    
                    // Hide badge if no notifications
                    if (count === 0) {
                        document.getElementById('notification-badge').style.display = 'none';
                    } else {
                        document.getElementById('notification-badge').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching notification count:', error);
                });
        }
        
        const notificationDropdown = document.getElementById('page-header-notifications-dropdown');
        notificationDropdown.addEventListener('click', function() {
            fetchNotifications();
        });
        
        fetchNotificationCount();
        
        setInterval(fetchNotificationCount, 15000);
    });

    function openClaim(claimLink, notificationId) {
        
        fetch(`/management0712/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(() => {
          console.log('Notification marked as read');
          window.location.href=claimLink;
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }
</script>



<style>
    #notification-list {
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
}

#notification-list::-webkit-scrollbar {
    width: 6px;
}

#notification-list::-webkit-scrollbar-track {
    background: transparent;
}

#notification-list::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}

#notification-list::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.3);
}
</style>