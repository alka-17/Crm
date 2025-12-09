<!-- HEADER / TOP NAV -->
<!-- <header class="main-header">
    <h2></h2>

    <div class="header-actions">
        <input type="text" placeholder="Search here...">
        <img src="https://i.pravatar.cc/35" class="user">
    </div>
</header> -->


<header class="main-header">
    <!-- <div class="nav-right col p-0"> -->
        <!-- <div class="flex justify-between h-10"> -->
            <h2></h2>
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <span class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                        </span>
                    </x-slot>

                </x-dropdown>
            </div>
        <!-- </div> -->
    <!-- </div> -->
</header>