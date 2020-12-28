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
                    <li><a href="{{route('unit.index')}}">Manage Suppliers</a></li>
                    <li><a href="{{route('unit.index')}}">Manage Customers</a></li>
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
                    <li><a href="javascript:;">Products</a></li>
                </ul>
            </li>

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
<!--main content start-->