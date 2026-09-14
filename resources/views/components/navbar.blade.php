<header class="navbar">

    <div class="logo">
        MyDrive
    </div>

    <div class="search-container">
       <form
        action="{{ route('drive.search') }}"
        method="GET"
        class="search-container"
        >

        <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Search files..."
        class="search-input"
        required
        >

        </form>
    </div>

    <div class="user-profile">
        <div class="avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <span>{{ auth()->user()->name }}</span>
    </div>

</header>