{{-- USERS --}}
@if(Auth::guard('admin')->user()->sectionCheck('users'))
<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route('admin.users.index') }}">
        <i class="ri-group-fill"></i> <span>Users</span>
    </a>
</li>
@endif


@if(Auth::guard('admin')->user()->sectionCheck('claims_management'))
                <!-- Claims Management -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.claims.index') }}">
                        <i class="ri-file-list-3-line"></i> <span>Claims Management</span>
                    </a>
                </li>
@endif
@if(Auth::guard('admin')->user()->sectionCheck('subscriptions'))
                 <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPages">
                        <i class="ri-pages-line"></i> <span>Subscriptions</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarPages">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.subplan.index') }}" class="nav-link">Subscriptions</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.subscriptions.transactions') }}" class="nav-link">Transactions</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.subplan.create') }}" class="nav-link"> Add Subscription 
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
@endif

{{-- SUPPORT TICKETS --}}
@if(Auth::guard('admin')->user()->sectionCheck('support_tickets'))
<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route('admin.tickets.index') }}">
        <i class="ri-ticket-2-line"></i> <span>Support Tickets</span>
    </a>
</li>
@endif

{{-- BLOG MANAGEMENT GROUP --}}
@if(Auth::guard('admin')->user()->sectionCheck('blogs'))
<li class="nav-item">
    <a class="nav-link menu-link" href="#blogManagement" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="blogManagement">
        <i class="ri-article-line"></i> <span>Blog Management</span>
    </a>
    <div class="collapse menu-dropdown" id="blogManagement">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.blog.category.index') }}" class="nav-link">Categories</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.blog.index') }}" class="nav-link">Blog Posts</a>
            </li>
        </ul>
    </div>
</li>
@endif



{{-- SETTINGS TITLE --}}
@if(
    Auth::guard('admin')->user()->sectionCheck('live_chat') ||
    Auth::guard('admin')->user()->sectionCheck('profile') ||
    Auth::guard('admin')->user()->sectionCheck('social') ||
    Auth::guard('admin')->user()->sectionCheck('manage_staffs') ||
    Auth::guard('admin')->user()->sectionCheck('roles') ||
    Auth::guard('admin')->user()->sectionCheck('general_settings') ||
    Auth::guard('admin')->user()->sectionCheck('clear_cache')
)
<li class="menu-title"><span>Settings</span></li>
@endif

{{-- LIVE CHAT --}}
@if(Auth::guard('admin')->user()->sectionCheck('live_chat'))
<li class="nav-item">
    <a class="nav-link menu-link" target="_blank" href="https://dashboard.tawk.to/">
        <i class="ri-message-2-line"></i> <span>Live Chat</span>
    </a>
</li>
@endif

{{-- PROFILE --}}
<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route('admin.profile') }}">
        <i class="ri-user-settings-fill"></i> <span>Profile</span>
    </a>
</li>


{{-- SOCIAL SETTINGS --}}
{{-- @if(Auth::guard('admin')->user()->sectionCheck('social_settings'))
<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route('admin.social') }}">
        <i class="ri-team-fill"></i> <span>Social</span>
    </a>
</li>
@endif --}}

{{-- MANAGE STAFFS --}}
@if(Auth::guard('admin')->user()->sectionCheck('manage_staffs'))
<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route('admin.staff.index') }}">
        <i class="ri-group-2-fill"></i> <span>Manage Staff</span>
    </a>
</li>
@endif


{{-- GENERAL SETTINGS --}}
@if(Auth::guard('admin')->user()->sectionCheck('general_settings'))
<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route('admin.generalsettings') }}">
        <i class="ri-settings-2-fill"></i> <span>General</span>
    </a>
</li>
@endif

{{-- CLEAR CACHE --}}
@if(Auth::guard('admin')->user()->sectionCheck('clear_cache'))
<li class="nav-item">
    <a class="nav-link menu-link" href="{{ route('admin.cache.clear') }}">
        <i class="ri-refresh-fill"></i> <span>Clear Cache</span>
    </a>
</li>
@endif
