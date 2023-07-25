<!DOCTYPE html>
<html>
<head>
    <title>Email from Laravel</title>
    <style>
        .btn-primary {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            touch-action: manipulation;
            cursor: pointer;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <p>Click the following link to download the file:</p>
    <a href="https://www.dropbox.com/s/aco2c3msfldn8b8/champagne.rar?dl=1" class="btn-primary" download>Download File</a>
    <p>Thanks,</p>
    <p>{{ config('app.name') }}</p>
</div>
</body>
</html>
