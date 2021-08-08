<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- Icons -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- CSS -->
        <link href = {{ asset("css/app.css") }} rel="stylesheet" />
    </head>
    <body class="antialiased">
       <div id="app">
            <div class="wrapper">
                <nav id="sidebar">
                    <div class="sidebar-header">
                        <h3 href="/">Words App</h3>
                        <hr>              
                    </div>
                    <ul class="list-unstyled components">
                        <p>Manage Panel</p>
                        <li> 
                            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle ">Type List</a>
                            <ul class="collapse list-unstyled " id="homeSubmenu">
                            @if(count($types) == 0)
                            <li> <a >Not type defined</a> </li>
                            @endif
                            @foreach ($types as $type)
                            <li> <a href='{{$type->id}}'>{{$type->type_name}}</a> </li>
                            @endforeach
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div class="content">
                    <button type="button" id="sidebarCollapse" class="btn btn-info"> <i class="fa fa-align-justify"></i></button> 
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                </div>
            </div>
       </div>
    </body>
    <!-- SCRIPTS -->
    <script src="{{ mix('/js/app.js') }}"></script>
    <script>
            $(document).ready(function(){
                $("#sidebarCollapse").on('click', function(){
                    $("#sidebar").toggleClass('active');
                });
            });
    </script>
   
    <style>
    @import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";

    body {
        font-family: 'Poppins', sans-serif;
        background: #fafafa
    }

    p {
        font-size: 1.1em;
        font-weight: 300;
        line-height: 1.7em;
        color: #999
    }

    a,
    a:hover,
    a:focus {
        color: inherit;
        text-decoration: none;
        transition: all 0.3s
    }

    .navbar {
        padding: 15px 10px;
        background: #fff;
        border: none;
        border-radius: 0;
        margin-bottom: 40px;
        box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1)
    }

    .navbar-btn {
        box-shadow: none;
        outline: none !important;
        border: none
    }

    .line {
        width: 100%;
        height: 1px;
        border-bottom: 1px dashed #ddd
    }

    .wrapper {
        display: flex;
        width: 100%;
        align-items: stretch
    }

    #sidebar {
        min-width: 250px;
        max-width: 250px;
        background: #005086;
        color: #fff;
        transition: all 0.3s
    }

    #sidebar.active {
        margin-left: -250px
    }

    #sidebar .sidebar-header {
        padding: 20px;
        background: #005086
    }

    #sidebar ul.components {
        padding: 20px 0px;
        border-bottom: 1px solid #47748b
    }

    #sidebar ul p {
        padding: 10px;
        font-size: 15px;
        display: block;
        color: #fff
    }

    #sidebar ul li a {
        padding: 10px;
        font-size: 1.1em;
        display: block
    }

    #sidebar ul li a:hover {
        color: #fff;
        background: #318fb5
    }

    #sidebar ul li.active>a,
    a[aria-expanded="true"] {
        color: #fff;
        background: #318fb5
    }

    a[data-toggle="collapse"] {
        position: relative
    }

    .dropdown-toggle::after {
        display: block;
        position: absolute;
        top: 50%;
        right: 20px;
        transform: translateY(-50%)
    }

    ul ul a {
        font-size: 0.9em !important;
        padding-left: 30px !important;
        background: #318fb5
    }

    ul.CTAs {
        padding: 20px
    }

    ul.CTAs a {
        text-align: center;
        font-size: 0.9em !important;
        display: block;
        border-radius: 5px;
        margin-bottom: 5px
    }

    a.download,
    a.download:hover {
        background: #318fb5;
        color: #fff
    }

    .content {
        width: 100%;
        padding: 20px;
        min-height: 100vh;
        transition: all 0.3s;
        align-items:center;
    }

    .content-wrapper {
        padding: 15px;
    }

    @media(maz-width:768px) {
        #sidebar {
            margin-left: -250px
        }

        #sidebar.active {
            margin-left: 0px
        }

        #sidebarCollapse span {
            display: none
        }
    }
    </style>
</html>
