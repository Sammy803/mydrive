<header class="navbar">

    <div class="logo">
        MyDrive
    </div>

    <div class="search-container">
        <input
            type="text"
            placeholder="Search files..."
            class="search-input"
        >
    </div>

    <div class="user-profile">
        <div class="avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <span>{{ auth()->user()->name }}</span>
    </div>

</header>