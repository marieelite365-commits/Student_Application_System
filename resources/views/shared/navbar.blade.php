<div class="navbar">

    <div class="left">
        <h2>@yield('title')</h2>
    </div>

    <div class="right">
        {{ Auth::user()->name }}
    </div>

</div>