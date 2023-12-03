<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
            <li class="header">MASTER</li>
            <!-- <li>
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-cube"></i> <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cubes"></i> <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Member</span>
                </a>
            </li>
            <li>
                <a href="{{ route('supplier.index') }}">
                    <i class="fa fa-truck"></i> <span>Supplier</span>
                </a>
            </li> -->
            <li>
                <a href="{{ route('customer.index') }}">
                    <i class="fa fa-user"></i> <span>Customer</span>
                </a>
            </li>
            <li>
                <a href="{{ route('penerima.index') }}">
                    <i class="fa fa-users"></i> <span>Penerima</span>
                </a>
            </li>
            <li>
                <a href="{{ route('harga.index') }}">
                    <i class="fa fa-money"></i> <span>Harga</span>
                </a>
            </li>
            <li class="header">PENGELOLAAN DATA</li>
            <li>
                <a href="{{ route('orderan.index') }}">
                    <i class="fa fa-shopping-cart"></i> <span>Orderan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('surat_angkut.index') }}">
                    <i class="fa fa-file-text"></i> <span>Surat Angkut</span>
                </a>
            </li>
            <li>
                <a href="{{ route('surat_angkut.sa_terkirim') }}">
                    <i class="fa fa-file-text-o"></i> <span>Surat Angkut Terkirim</span>
                </a>
            </li>
            <!-- <li>
                <a href="{{ route('dm.index') }}">
                    <i class="fa fa-archive"></i> <span>Daftar Muat</span>
                </a>
            </li> -->
            <li>
                <a href="{{ route('party.index') }}">
                    <i class="fa fa-users"></i> <span>Daftar Party</span>
                </a>
            </li>
            <li class="header">INVOICE</li>
            <li>
                <a href="{{ route('invoice.index') }}">
                    <i class="fa fa-file"></i> <span>Invoice</span>
                </a>
            </li>
            <li>
                <a href="{{ route('invoiceWeekly.index') }}">
                    <i class="fa fa-file"></i> <span>Invoice Mingguan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('invoiceMonthly.index') }}">
                    <i class="fa fa-file"></i> <span>Invoice Bulanan</span>
                </a>
            </li>
            <li class="header">SYSTEM</li>
            <li>
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-user"></i> <span>User</span>
                </a>
            </li>
            <li>
                <a href='{{ route("setting.index") }}'>
                    <i class="fa fa-cogs"></i> <span>Pengaturan</span>
                </a>
            </li>

            <li>
                <a href="{{ route('transaksi.index') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Aktif</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Baru</span>
                </a>
            </li>
            @elseif (auth()->user()->level == 2)
            <li class="header">MASTER</li>
            <!-- <li>
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-cube"></i> <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cubes"></i> <span>Produk</span>
                </a>
            </li> -->
            <!-- <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Member</span>
                </a>
            </li> -->
            <!-- <li>
                <a href="{{ route('supplier.index') }}">
                    <i class="fa fa-truck"></i> <span>Supplier</span>
                </a>
            </li> -->
            <li>
                <a href="{{ route('customer.index') }}">
                    <i class="fa fa-user"></i> <span>Customer</span>
                </a>
            </li>
            <li>
                <a href="{{ route('penerima.index') }}">
                    <i class="fa fa-users"></i> <span>Penerima</span>
                </a>
            </li>
            <li>
                <a href="{{ route('harga.index') }}">
                    <i class="fa fa-money"></i> <span>Harga</span>
                </a>
            </li>
            <li class="header">PENGELOLAAN DATA</li>
            <li>
                <a href="{{ route('orderan.index') }}">
                    <i class="fa fa-shopping-cart"></i> <span>Orderan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('surat_angkut.index') }}">
                    <i class="fa fa-file-text"></i> <span>Surat Angkut</span>
                </a>
            </li>
            <li>
                <a href="{{ route('surat_angkut.sa_terkirim') }}">
                    <i class="fa fa-file-text-o"></i> <span>Surat Angkut Terkirim</span>
                </a>
            </li>
            <li>
                <a href="{{ route('dm.index') }}">
                    <i class="fa fa-archive"></i> <span>Daftar Muat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('party.index') }}">
                    <i class="fa fa-users"></i> <span>Daftar Party</span>
                </a>
            </li>
            <li class="header">INVOICE</li>
            <li>
                <a href="{{ route('invoice.index') }}">
                    <i class="fa fa-file"></i> <span>Invoice</span>
                </a>
            </li>
            <li>
                <a href="{{ route('invoiceWeekly.index') }}">
                    <i class="fa fa-file"></i> <span>Invoice Mingguan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('invoiceMonthly.index') }}">
                    <i class="fa fa-file"></i> <span>Invoice Bulanan</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
</aside>
