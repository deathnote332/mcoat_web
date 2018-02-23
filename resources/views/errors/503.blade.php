<!DOCTYPE html>
<html>
    <head>
        <title>Page not found.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
                background: url('../../images/mcoat-bg.jpg');

            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
                font-weight: bold;
                color: red
            }
            .content a{
                 padding: 15px 60px;
                 text-decoration: none;
                 background: #337ab7;
                 color: white;
                 cursor: pointer;
                 font-size: 16px;
             }
            .content a:hover{

                background: #1451b7;

            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">404 Page not found.</div>
                <a class="back" href="javascript:history.back()">Back</a>
            </div>
        </div>
    </body>

</html>
