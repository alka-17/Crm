<h2 class="logo">CRM ADMIN</h2>

<ul class="menu">
    <!-- PARENT MENU -->
    <li class="has-submenu">
        <a href="javascript:void(0)" onclick="toggleSubmenu('contactSubmenu', '.arrow')">
            <i class="fa fa-address-book"></i> Contacts
            <span class="arrow">▼</span>
        </a>

        <!-- SUBMENU -->
        <ul class="submenu" id="contactSubmenu">
            <li><a href="{{ route('contacts.list') }}">Contact List</a></li>
            <li><a href="{{ route('contacts.index') }}">Add Contact</a></li>
        </ul>
    </li>

    <li class="has-submenu">
        <a href="javascript:void(0)" onclick="toggleSubmenu('customFieldsSubmenu', '.arrow')">
            <i class="fa fa-list"></i> Custom Fields
            <span class="arrow">▼</span>
        </a>

        <!-- SUBMENU -->
        <ul class="submenu" id="customFieldsSubmenu">
            <li><a href="{{ route('custom-fields.list') }}">List</a></li>
            <li><a href="{{ route('custom-fields.index') }}">Add Custom Fields</a></li>
        </ul>
    </li>

    <li><a href="{{ route('profile.edit') }}"><i class="fa fa-user"></i> Profile</a></li>

    <li><a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class=" fa fa-sign-out-alt"></i> Logout</a></li>

    <form id="logout-form" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>
</ul>