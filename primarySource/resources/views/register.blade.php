<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from thevectorlab.net/flatlab/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 14 Aug 2015 03:42:50 GMT -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.html">

    <title>Đăng kí tài khoản</title>

    <!-- Bootstrap core CSS -->
    <link href="admin_theme/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin_theme/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="admin_theme/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="admin_theme/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="admin_theme/css/owl.carousel.css" type="text/css">

    <link href="admin_theme/css/style-responsive.css" rel="stylesheet" />
    <link href="admin_theme/css/login-form.css" rel="stylesheet">

</head>

  <body>
    <div class="container">
        <div class="card card-container">
            <h2>Đăng kí tài khoản Quản trị viên</h2>
            <?php
            // if(isset($validator)){
            //     $errors = $validator->errors();
            //     print_r($errors);
            // }
            

            ?>
            @if($errors->any())
                <div class="alert alert-danger"> 
                    @foreach($errors->all() as $loi)
                        <li>{{$loi}}</li>
                    @endforeach
                </div>
            @endif

            <form class="form-horizontal" method="post" action="{{route('register')}}">
                {{csrf_field()}}
                <div class="form-group ">
                    <label class="control-label col-sm-3">Fullname: </label>
                    <div class="col-md-9">
                        <input type="text" name="fullname" placeholder="Nhập họ tên" class="form-control">
                    </div>
                </div>
                <div class="form-group ">
                    <label class="control-label col-sm-3">Email: </label>
                    <div class="col-md-9">
                        <input type="email" name="email" placeholder="Nhập Email" class="form-control">
                    </div>
                </div>
                <div class="form-group ">
                    <label class="control-label col-sm-3">Password: </label>
                    <div class="col-md-9">
                        <input type="password" name="password" placeholder="Nhập mật khẩu" class="form-control">
                    </div>
                </div>
                <div class="form-group ">
                    <label class="control-label col-sm-3">Re Enter Password: </label>
                    <div class="col-md-9">
                        <input type="password" name="re_password" placeholder="Nhập lại mật khẩu" class="form-control">
                    </div>
                </div>
                <div class="form-group ">
                    <div class="col-md-9 col-md-offset-3">
                        <div class="g-recaptcha" data-sitekey="6LcuZzIUAAAAAIlFv6trsgB7Dg5ObDFLFj08V0_d"></div>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Register</button>
            </form>
        </div><!-- /card-container -->
    </div><!-- /container -->

    <script src="admin_theme/js/jquery.js"></script>
    <script src="admin_theme/js/bootstrap.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    
  </body>

</html>
