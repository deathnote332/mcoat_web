@push('styles')
<style>
    #page-wrapper{
        padding: 20px;
    }
</style>
@endpush
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">MCOAT</a>
        </div>

        <!-- /.navbar-header -->
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <span class="user-name">{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</span> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href=""><i class="fa fa-cog fa-fw"></i> Account settings</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                        <li>
                            <a href={{ URL('dashboard')  }}><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-list fa-fw"></i> Products<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href={{ URL('admin/mcoat')  }}>MCOAT WAREHOUSE STOCKS</a>
                                </li>
                                <li>
                                    <a href={{ URL('admin/allied')  }}>ALLIED WAREHOUSE STOCKS</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-list fa-fw"></i> Manage Products<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a  href={{ URL('admin/manageProduct')  }}>MCOAT WAREHOUSE STOCKS</a>
                                </li>
                                <li>
                                    <a href={{ URL('admin/alliedmanageproduct')  }}>ALLIED WAREHOUSE STOCKS</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Product out<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href={{ URL('admin/productout')  }}>MCOAT Product out</a>
                                </li>
                                <li>
                                    <a href={{ URL('admin/alliedproductout')  }}>ALLIED Product out</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-file-text-o fa-fw"></i> Product in<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href={{ URL('admin/productin')  }}>MCOAT Product in</a>
                                </li>
                                <li>
                                    <a href={{ URL('admin/alliedproductin')  }}>ALLIED Product in</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                        <li>
                            <a href={{ URL('receipts')  }}><i class="fa fa-files-o fa-fw"></i> Receipts</a>
                        </li>

                        <li>
                            <a href={{ URL('receiptin')  }}><i class="fa fa-file-text-o fa-fw"></i> Product in receipt</a>
                        </li>
                        <li>
                            <a href={{ URL('stocksreport')  }}><i class="fa fa-list fa-fw"></i> Stocks Report</a>
                        </li>
                        <li>
                            <a href={{ URL('branches')  }}><i class="fa fa-map-marker fa-fw"></i> Branches</a>
                        </li>
                        <li>
                            <a href={{ URL('admin/warehouse')  }}><i class="fa fa-caret-square-o-down fa-fw"></i>Warehouse</a>
                        </li>
                        <li>
                            <a href={{ URL('suppliers')  }}><i class="fa fa-user-plus fa-fw"></i> Suppliers</a>
                        </li>

                        <li>
                            <a href={{ URL('admin/users')  }}><i class="fa fa-user fa-fw"></i> Users</a>
                        </li>
                        <li>
                            <a href={{ URL('admin/employees')  }}><i class="fa fa-users fa-fw"></i> Employees</a>
                        </li>
                        <li>
                            <a href={{ URL('admin/reset')  }}><i class="fa fa-sort-amount-desc fa-fw"></i> Reset Quantity</a>
                        </li>
                        <li>
                            <a href={{ URL('admin/branchsales')  }}><i class="fa fa-map-marker fa-fw"></i> Branch Sales</a>
                        </li>
                        <li>
                            <a href={{ URL('admin/activity')  }}><i class="fa fa-history fa-fw"></i> Activity Logs</a>
                        </li>
                        {{--<li>--}}
                        {{--<a href={{ URL('admin/activity')  }}><i class="fa fa-history fa-fw"></i> Set Price per branch</a>--}}
                        {{--</li>--}}

                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>
    <!-- Page Content -->
    <div id="page-wrapper">
        @yield('content')
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
