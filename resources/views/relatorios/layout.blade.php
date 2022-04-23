<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        /** Define the margins of your page **/
        @page {
            margin: 100px 25px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        header {
            top: -60px;
            left: 0px;
            right: 0px;
            /* height: 50px; */
            width: 100%;

            /** Extra personal styles **/
            color: black;
            text-align: center;
            display: block;
            position: fixed;
            /* line-height: 35px; */
            margin-bottom: 30%;
        }

        footer {
            position: fixed; 
            bottom: -60px; 
            left: 0px; 
            right: 0px;
            height: 50px; 

            /** Extra personal styles **/
            background-color: #747474;
            color: white;
            text-align: center;
            line-height: 35px;
        }

        #header-left {
            float: left;
            text-align: left;
            padding: 20px;
            width: 26%;
        }

        #header-center {
            float: left;
            text-align: center;
            padding: 20px;
            width: 33%;
        }

        #header-right {
            float: left;
            text-align: right;
            padding: 20px;
            width: 26%;
        }

        #header-body {
            /* float: center; */
            /* width: 100%; */
            display:block;
            clear: both;
            margin-top: 3%;
        }

        main {
            top: 60;
        }
    </style>
</head>
    <body>
        <header>
            <div id="header-left" style="">
                @yield('header-left')
            </div>

            <div id="header-center" style="">
                @yield('header-center')
            </div>

            <div id="header-right" style="">
                @yield('header-right')
            </div>

            <div id="header-body" style="">
                @yield('header-body')
            </div>
        </header>
        <main style="position: relative">
            @yield('content')
        </main>
    </body>
</html>