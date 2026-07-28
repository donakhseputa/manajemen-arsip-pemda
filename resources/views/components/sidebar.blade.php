<style>
    /* Warna Dasar Teks & Ikon */
    #layout-menu .menu-link, 
    #layout-menu .menu-header-text, 
    #layout-menu .app-brand-text,
    #layout-menu .menu-icon {
        color: #ffffff !important;
    }

    
    /* 1. Mencegah menu induk (yang punya submenu) punya background saat anaknya aktif */
    /* Kita gunakan !important untuk menimpa class 'active' bawaan template */
    #layout-menu .menu-item.active.open > .menu-link {
        background-color: transparent !important;
        box-shadow: none !important;
    }

    /* 2. Menghapus efek hover pada menu induk agar tidak berubah warna */
    #layout-menu .menu-item.has-submenu > .menu-link:hover {
        background-color: transparent !important;
    }

    /* 3. Memberikan efek highlight HANYA pada menu anak (Surat Masuk) */
    /* Ini target spesifik ke menu yang aktif */
    #layout-menu .menu-sub .menu-item.active > .menu-link {
        background-color: rgba(0, 0, 0, 0.45) !important;
        border-left: 4px solid #ffffff; /* Opsional: Tambahkan garis tepi agar lebih terlihat */
    }

    /* 4. Memastikan teks tetap putih saat induk aktif */
    #layout-menu .menu-item.active.open > .menu-link,
    #layout-menu .menu-item.active.open > .menu-link i {
        color: #ffffff !important;
    }

    #layout-menu .menu-link:hover {
        /* Ganti rgba(0, 0, 0, 0.1) dengan warna yang kamu suka */
        /* Kode ini memberikan efek sedikit lebih gelap saat di-hover */
        background-color: rgba(0, 0, 0, 0.45) !important;
        color: #ffffff !important; /* Pastikan teks tetap putih */
    }

    /* Khusus untuk menu anak (submenu) agar konsisten */
    #layout-menu .menu-sub .menu-link:hover {
        background-color: rgba(0, 0, 0, 0.45) !important;
    }

    /* Menghilangkan efek background putih bawaan template */
    .menu-vertical .menu-item .menu-link:hover {
        background-image: none !important;
    }

    #layout-menu .menu-item.active > .menu-link {
        background-color: transparent !important;
       
    }
    
    /* Jika ingin background tetap ada tapi warnanya mengikuti warna biru sidebar (lebih gelap sedikit) */
    #layout-menu .menu-item.active > .menu-link {
        background-color: rgba(0, 0, 0, 0.45) !important;
    }

    .fs-tiny {
        color: rgb(255, 255, 255) !important; /* Warna putih transparan */
        font-size: 0.75rem; /* Ukuran font bawaan Sneat */
    }

</style>


<aside id="layout-menu" class="layout-menu menu-vertical menu" style="background-color: #26a0fc !important;">
    <div class="app-brand demo mt-0">
        <a href="{{ route('home') }}" class="app-brand-link">
            <img src="{{ asset('sneat/img/logo.png') }}" alt="{{ config('app.name') }}" width="60">
            <div class="d-flex flex-column ms-2">
                <span class="app-brand-text text-black fw-bolder fs-3">SMS</span>
                <span class="fs-tiny">{{ config('app.description') }}</span>
            </div>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Home -->
        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="{{ __('menu.home') }}">{{ __('menu.home') }}</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('menu.header.main_menu') }}</span>
        </li>
        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaction.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-mail-send"></i>
                <div data-i18n="{{ __('menu.transaction.menu') }}">{{ __('menu.transaction.menu') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaction.incoming.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('transaction.incoming.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.transaction.incoming_letter') }}">{{ __('menu.transaction.incoming_letter') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaction.outgoing.*') ? 'active' : '' }}">
                    <a href="{{ route('transaction.outgoing.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.transaction.outgoing_letter') }}">{{ __('menu.transaction.outgoing_letter') }}</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('agenda.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-book"></i>
                <div data-i18n="{{ __('menu.agenda.menu') }}">{{ __('menu.agenda.menu') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('agenda.incoming') ? 'active' : '' }}">
                    <a href="{{ route('agenda.incoming') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.agenda.incoming_letter') }}">{{ __('menu.agenda.incoming_letter') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('agenda.outgoing') ? 'active' : '' }}">
                    <a href="{{ route('agenda.outgoing') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.agenda.outgoing_letter') }}">{{ __('menu.agenda.outgoing_letter') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('menu.header.other_menu') }}</span>
        </li>
        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('gallery.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-images"></i>
                <div data-i18n="{{ __('menu.gallery.menu') }}">{{ __('menu.gallery.menu') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('gallery.incoming') ? 'active' : '' }}">
                    <a href="{{ route('gallery.incoming') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.gallery.incoming_letter') }}">{{ __('menu.gallery.incoming_letter') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('gallery.outgoing') ? 'active' : '' }}">
                    <a href="{{ route('gallery.outgoing') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.gallery.outgoing_letter') }}">{{ __('menu.gallery.outgoing_letter') }}</div>
                    </a>
                </li>
            </ul>
        </li>
        @if(auth()->user()->role == 'admin')
            <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('reference.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-analyse"></i>
                    <div data-i18n="{{ __('menu.reference.menu') }}">{{ __('menu.reference.menu') }}</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('reference.archive-classifications.*') ? 'active' : '' }}">
                        <a href="{{ route('reference.archive-classifications.index') }}" class="menu-link">
                            <div
                                data-i18n="{{ __('menu.reference.archive-classifications') }}">{{ __('menu.reference.archive-classifications') }}</div>
                        </a>
                    </li>
                    {{-- <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('reference.classification.*') ? 'active' : '' }}">
                        <a href="{{ route('reference.classification.index') }}" class="menu-link">
                            <div
                                data-i18n="{{ __('menu.reference.classification') }}">{{ __('menu.reference.classification') }}</div>
                        </a>
                    </li> --}}
                    <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('reference.status.*') ? 'active' : '' }}">
                        <a href="{{ route('reference.status.index') }}" class="menu-link">
                            <div data-i18n="{{ __('menu.reference.status') }}">{{ __('menu.reference.status') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- User Management -->
            <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('user.*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-pin"></i>
                    <div data-i18n="{{ __('menu.users') }}">{{ __('menu.users') }}</div>
                </a>
            </li>
        @endif
    </ul>
</aside>
