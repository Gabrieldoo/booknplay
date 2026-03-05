<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookNPlay</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background: linear-gradient(135deg,#0f172a,#1e293b);
            display:flex;
            align-items:center;
            justify-content:center;
            min-height:100vh;
            color:white;
            padding:20px;
        }

        .auth-card{
            background: rgba(255,255,255,0.05);
            padding:40px;
            border-radius:15px;
            width:min(100%, 420px);
            backdrop-filter: blur(10px);
            box-shadow:0 0 25px rgba(0,0,0,0.5);
        }

        .form-control{
            background:rgba(255,255,255,0.08);
            border:none;
            color:white;
        }

        .form-control:focus{
            background:rgba(255,255,255,0.15);
            color:white;
            box-shadow:none;
        }

        .btn-primary{
            background:#2563eb;
            border:none;
        }

        .brand{
            font-size:24px;
            font-weight:bold;
            margin-bottom:25px;
            text-align:center;
        }

        a{
            color:#60a5fa;
            text-decoration:none;
        }
    </style>
</head>

<body>

<div class="auth-card">
<div class="text-center mb-4">
    <img src="/logo.png" height="80">
</div>
    {{ $slot }}
</div>

</body>
</html>
