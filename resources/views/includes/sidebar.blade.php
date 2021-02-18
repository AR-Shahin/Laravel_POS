<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <li class="">
                <a href="{{route('dashboard')}}" class="@if($sub_menu == 'Dashboard') active @endif">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sub-menu  ">
                <a href="javascript:;" class="@if($main_menu == 'User') active @endif">
                    <i class="fa fa-users"></i>
                    <span>Users</span>
                </a>
                <ul class="sub">
                    <li class="@if($sub_menu == 'Supplier') active @endif"><a href="{{route('supplier.index')}}" >Manage Suppliers</a></li>
                    <li class="@if($sub_menu == 'Customer') active @endif"><a href="{{route('customer.index')}}">Manage Customers</a></li>
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" class="@if($main_menu == 'Product') active @endif" >
                    <i class="fa fa-bars"></i>
                    <span>Products </span>
                </a>
                <ul class="sub">
                    <li class="@if($sub_menu == 'Unit') active @endif"><a href="{{route('unit.index')}}">Unit</a></li>
                    <li class="@if($sub_menu == 'Category') active @endif"><a href="{{route('category.index')}}">Category</a></li>
                    <li class="@if($sub_menu == 'Product') active @endif"><a href="{{route('product.index')}}">Product</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="javascript:;"  class="@if($main_menu == 'Admin') active @endif">
                    <i class="fa fa-users"></i>
                    <span>Admin</span>
                </a>
                <ul class="sub">
                    <li class="@if($sub_menu == 'Admin') active @endif"><a href="{{route('admin.index')}}">Admin</a></li>
                    <li><a href="{{route('admin.index')}}">Profile</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="javascript:;" class="@if($main_menu == 'Purchase') active @endif">
                    <i class="fa fa-tasks"></i>
                    <span>Purchase</span>
                </a>
                <ul class="sub">
                    <li class="@if($sub_menu == 'Purchase') active @endif"><a href="{{route('purchase.index')}}">Manage Purchase</a></li>
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;"class="@if($main_menu == 'Invoice') active @endif" >
                    <i class="fa fa-bars"></i>
                    <span>Invoice</span>
                </a>
                <ul class="sub">
                    <li class="@if($sub_menu == 'Invoice') active @endif"><a href="{{route('invoice.index')}}">Manage Invoice</a></li>
                </ul>
            </li>
            {{--Report--}}
            <li class="sub-menu">
                <a href="javascript:;" class="@if($main_menu == 'Report') active @endif" >
                    <i class="fa fa-bar-chart-o"></i>
                    <span>Reports</span>
                </a>
                <ul class="sub">
                    <li class="@if($sub_menu == 'Credit_Report') active @endif"><a href="{{route('report.credit.customer')}}">Credit Customers</a></li>
                    <li class="@if($sub_menu == 'Paid') active @endif"><a href="{{route('report.credit.customer')}}">Paid Customers</a></li>
                    <li class="@if($sub_menu == 'Purchase_Report') active @endif"><a href="{{route('report.purchase')}}">Purchases</a></li>
                    <li class="@if($sub_menu == 'Invoice_Report') active @endif"><a href="{{route('report.invoice')}}">Invoices</a></li>
                    <li class="@if($sub_menu == 'Payment_Report') active @endif"><a href="{{route('report.credit.customer')}}">Payments</a></li>
                </ul>
            </li>

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
<!--main content start-->