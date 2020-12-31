<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <li>
                <a href="{{route('dashboard')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-users"></i>
                    <span>Users</span>
                </a>
                <ul class="sub">
                    <li><a href="{{route('supplier.index')}}">Manage Suppliers</a></li>
                    <li><a href="{{route('customer.index')}}">Manage Customers</a></li>
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-bars"></i>
                    <span>Products</span>
                </a>
                <ul class="sub">
                    <li><a href="{{route('unit.index')}}">Unit</a></li>
                    <li><a href="{{route('category.index')}}">Category</a></li>
                    <li><a href="{{route('product.index')}}">Product</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-users"></i>
                    <span>Admin</span>
                </a>
                <ul class="sub">
                    <li><a href="{{route('admin.index')}}">Admin</a></li>
                    <li><a href="{{route('admin.index')}}">Profile</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-tasks"></i>
                    <span>Purchase</span>
                </a>
                <ul class="sub">
                    <li><a href="{{route('purchase.index')}}">Manage Purchase</a></li>
                    <li><a href="{{route('admin.index')}}">Profile</a></li>
                </ul>
            </li>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
<!--main content start-->