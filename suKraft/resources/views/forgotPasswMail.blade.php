<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body style="background:#ccc">
<div style="margin:auto; width:50%; background:white;">
    <img src="https://i.ibb.co/BzTSSfT/top-Border.png" alt="topPart" style="width: 100%;"/>

    <h2 style="text-align:center">Pozdrav!</h2>
    <h3 style="text-align:center">Molimo promenite vašu šifru tako što ćete stisnuti na dugme ispod. Hvala!</h3>
    <a style="color: blue; text-decoration:none;" href="{{url('/')}}/forgotPassword/{{ $token }}"><h3 style="text-align:center">Vas URL za promenu šifre</h3></a>
    <img src="https://i.ibb.co/8MgDp23/image-2.png" alt="bottomPart" style="width: 100%;"/>
</div>
</body>
</html>


