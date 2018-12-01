<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/img/account.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        {{--<!-- search form -->--}}
        {{--<form action="#" method="get" class="sidebar-form">--}}
            {{--<div class="input-group">--}}
                {{--<input type="text" name="q" class="form-control" placeholder="Search...">--}}
                {{--<span class="input-group-btn">--}}
                {{--<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>--}}
                {{--</button>--}}
              {{--</span>--}}
            {{--</div>--}}
        {{--</form>--}}
        {{--<!-- /.search form -->--}}
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
        @if(Auth::user()->user_type == 1)
            <li class="{{ (Request::is('admin/dashboard')) ? 'active' : '' }}">
                <a href="admin/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            {{--Products--}}
            <li class="treeview {{ (Request::is('admin/products')) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-list"></i>
                    <span>Products</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li ><a href="admin/products?id=1"><i class="fa fa-circle-o text-blue"></i> MCOAT</a></li>
                    <li ><a href="admin/products?id=2"><i class="fa fa-circle-o text-red"></i> ALLIED</a></li>
                </ul>
            </li>
            {{--Manage Products--}}
            <li class="treeview {{ (Request::is('admin/manage-products')) ? 'active' : '' }}" >
                <a href="#">
                    <i class="fa fa-list"></i>
                    <span>Manage Products</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="admin/manage-products?id=1"><i class="fa fa-circle-o text-blue"></i> MCOAT</a></li>
                    <li><a href="admin/manage-products?id=2"><i class="fa fa-circle-o text-red"></i> ALLIED</a></li>
                </ul>
            </li>
            {{--Product out--}}
            <li class="treeview  {{ (Request::is('admin/product-out')) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Product Out</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="admin/product-out?id=1&cart=1"><i class="fa fa-circle-o text-blue"></i> MCOAT</a></li>
                    <li><a href="admin/product-out?id=2&cart=3"><i class="fa fa-circle-o text-red"></i> ALLIED</a></li>
                </ul>
            </li>
            {{--Product in--}}
            <li class="treeview {{ (Request::is('admin/product-in')) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-file-text-o"></i>
                    <span>Product In </span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="admin/product-in?id=1&cart=2"><i class="fa fa-circle-o text-blue"></i> MCOAT</a></li>
                    <li><a href="admin/product-in?id=2&cart=4"><i class="fa fa-circle-o text-red"></i> ALLIED</a></li>
                </ul>
            </li>

            {{--Rceipts--}}
            <li class="treeview {{ (Request::is('admin/receipts') || Request::is('admin/receipts-in')) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Receipts</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="admin/receipts"><i class="fa fa-circle-o text-blue"></i> PRODUCT OUT</a></li>
                    <li><a href="admin/receipts-in"><i class="fa fa-circle-o text-red"></i> PRODUCT IN</a></li>
                    <li><a href="admin/receipts-exchange"><i class="fa fa-circle-o text-blue"></i> STOCK TRANSFER</a></li>
                    <li><a href="admin/receipts-purchase"><i class="fa fa-circle-o text-red"></i> PURCHASE ORDER</a></li>
                </ul>
            </li>
            <li class="treeview {{ (Request::is('branch-total-inventory') || Request::is('manage-inventory')) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Inventory</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('branch-total-inventory') }}"><i class="fa fa-circle-o text-blue"></i> Inventory for branch</a></li>
                    <li><a href="{{ url('/manage-inventory') }}"><i class="fa fa-circle-o text-red"></i> Manage Inventory</a></li>
               </ul>
            </li>
            {{--Rceipts--}}
            <li class="treeview {{ (Request::is('admin/product-tracking') || Request::is('user/product-tracking') || Request::is('admin/product-tracking-delivery') || Request::is('user/product-tracking-delivery') || Request::is('admin/product-tracking-in') || Request::is('user/product-tracking-in')) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-search"></i>
                    <span>Products Tracking</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="admin/product-tracking"><i class="fa fa-circle-o text-blue"></i> PRODUCT OUT</a></li>
                    <li><a href="admin/product-tracking-in"><i class="fa fa-circle-o text-red"></i> PRODUCT IN</a></li>
                    <li><a href="admin/product-tracking-delivery"><i class="fa fa-circle-o text-blue"></i> DELIVERY</a></li>
                </ul>
            </li>
            
            <li class="{{ (Request::is('admin/stock-exchange')) ? 'active' : '' }}">
                <a href="{{ url('/admin/stock-exchange') }}">
                    <i class="fa fa-list"></i> <span>Stocks Transfer</span>
                </a>
            </li>
            <li class="{{ (Request::is('admin/purchase-order')) ? 'active' : '' }}">
                <a href="{{ url('admin/purchase-order') }}">
                    <i class="fa fa-list"></i> <span>Purchase Order</span>
                </a>
            </li>


            <li class="{{ (Request::is('admin/stock-report') ) ? 'active' : '' }}">
                <a href="{{ url('/admin/stock-report') }}">
                    <i class="fa fa-list"></i> <span>Stocks Report</span>
                </a>
            </li>
            <li class="{{ (Request::is('admin/branches')) ? 'active' : '' }}">
                <a href="{{ url('/admin/branches') }}">
                    <i class="fa fa-map-marker"></i> <span>Branches</span>
                </a>
            </li>
            <li class="{{ (Request::is('admin/suppliers')) ? 'active' : '' }}">
                <a href="{{ url('/admin/suppliers') }}">
                    <i class="fa fa-user-plus"></i> <span>Suppliers</span>
                </a>
            </li>
            <li class="{{ (Request::is('admin/users')) ? 'active' : '' }}">
                <a href="{{ url('/admin/users') }}">
                    <i class="fa fa-user"></i> <span>Users</span>
                </a>
            </li>


            <li class="">
                <a href="{{ url('branch-sale') }}">
                    <i class="fa fa-map-marker"></i> <span>Branch Sales</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('admin/activity-logs') }}">
                    <i class="fa fa-history"></i> <span>Activity Logs</span>
                </a>
            </li>
            <li class="{{ (Request::is('admin/reset')) ? 'active' : '' }}">
                <a href="{{ url('admin/reset') }}">
                    <i class="fa fa-sort-amount-desc"></i> <span>Reset Quantity</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('/print-report') }}">
                    <i class="fa fa-files-o"></i> <span>Print Branch Report</span>
                </a>
            </li>
        @else
            <li class="{{ (Request::is('user/dashboard')) ? 'active' : '' }}">
                <a href="user/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ (Request::is('user/dashboard')) ? 'active' : '' }}">
                <a href="{{ url('user/products?id='.Auth::user()->warehouse) }}">
                    <i class="fa fa-list"></i> <span>Products</span>
                </a>
            </li>

            <!-- {{--Manage Products--}} -->

            <li class=" {{ (Request::is('user/manage-products')) ? 'active' : '' }}" >
                <a href="{{ url('user/manage-products?id='.Auth::user()->warehouse) }}">
                    <i class="fa fa-list"></i> <span>Manage Products</span>
                </a>
            </li>
            {{--Products Out--}}
            <li class=" {{ (Request::is('user/product-out')) ? 'active' : '' }}" >
                <a href="{{ url('user/product-out?id='.Auth::user()->warehouse) }}">
                    <i class="fa fa-files-o"></i> <span>Product Out</span>
                </a>
            </li>

            {{--Product in--}}
            <li class=" {{ (Request::is('user/product-in')) ? 'active' : '' }}" >
                <a href="{{ url('user/product-in?id='.Auth::user()->warehouse) }}">
                    <i class="fa fa-file-text-o"></i> <span>Product In</span>
                </a>
            </li>

            <li class="treeview {{ (Request::is('user/receipts') || Request::is('user/receipts-in')) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Receipts</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('user/receipts') }}"><i class="fa fa-circle-o text-blue"></i> PRODUCT OUT</a></li>
                    <li><a href="{{ url('user/receipts-in') }}"><i class="fa fa-circle-o text-red"></i> PRODUCT IN</a></li>
                    <li><a href="user/receipts-exchange"><i class="fa fa-circle-o text-blue"></i> EXCHANGE</a></li>
                    <li><a href="user/receipts-purchase"><i class="fa fa-circle-o text-red"></i> PURCHASE</a></li>
                </ul>
            </li>
            <li class="treeview {{ (Request::is('branch-total-inventory') || Request::is('manage-inventory')) ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Inventory</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('branch-total-inventory') }}"><i class="fa fa-circle-o text-blue"></i> Inventory for branch</a></li>
                    <li><a href="{{ url('/manage-inventory') }}"><i class="fa fa-circle-o text-red"></i> Manage Inventory</a></li>
               </ul>
            </li>
            <li class="{{ (Request::is('user/branch-total-inventory')) ? 'active' : '' }}">
                <a href="{{ url('/user/branch-total-inventory') }}">
                    <i class="fa fa-files-o"></i> <span>Inventory for branches</span>
                </a>
            </li>
            <li class="{{ (Request::is('/user/stock-exchange') || Request::is('admin/receipts-in')) ? 'active' : '' }}">
                <a href="{{ url('/user/stock-exchange') }}">
                    <i class="fa fa-list"></i> <span>Stocks Transfer</span>
                </a>
            </li>

            <li class="{{ (Request::is('/user/stock-report') || Request::is('admin/receipts-in')) ? 'active' : '' }}">
                <a href="{{ url('/user/stock-report') }}">
                    <i class="fa fa-list"></i> <span>Stocks Report</span>
                </a>
            </li>
            <li class="{{ (Request::is('/user/branches')) ? 'active' : '' }}">
                <a href="{{ url('/user/branches') }}">
                    <i class="fa fa-map-marker"></i> <span>Branches</span>
                </a>
            </li>
            <li class="{{ (Request::is('/user/suppliers')) ? 'active' : '' }}">
                <a href="{{ url('/user/suppliers') }}">
                    <i class="fa fa-user-plus"></i> <span>Suppliers</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('/branch-sale') }}">
                    <i class="fa fa-map-marker"></i> <span>Branch Sales</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('/print-report') }}">
                    <i class="fa fa-files-o"></i> <span>Print Branch Report</span>
                </a>
            </li>
        @endif

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
