
<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>{{$gs->name}}</title>
    <meta name="description" content="Email From {{$gs->name}}">
    <style type="text/css">
        a:hover {text-decoration: underline !important;}
        .footer-social-icon tr td img{
                margin-left:5px;
                margin-right:5px;
            }
            .text-center{
                text-align: center;
            }
            .social-links td a{
                margin: 5px;
            }
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
    <!--100% body table-->
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
        style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
        <tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                   cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                          <a href="{{ route('front.index') }}" title="logo" target="_blank">
                            <img width="160" src="{{ URL::asset('assets/logo/logo-light.png') }}" title="logo" alt="logo">
                          </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center23" cellpadding="0" cellspacing="0"
                                style="max-width:670px;background:#fff; border-radius:3px; -webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        @if(isset($subject))
                                        <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;text-align: center;">{{$subject}}</h1>
                                        <div style="width:100%;display: flex;justify-content: center;">
                                            <span
                                                style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                        </div>
                                        @endif

                                         <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            {!! $email_body !!}
                                        </p>
                                       
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;"> <strong> © {{now()->year}}, {{$gs->name}}. All rights reserved.</strong></p>
                        </td>
                    </tr>


                 
                    <!-- <div style="border-top: 1px solid #ddd; margin: 20px auto 0;"></div> -->
                    <table  border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 20px auto 0; text-align:center" >
                        <tr>
                            <td >
                                <p style="margin:0px;font-size:13px">Problems or questions? </p>
                                <p style="margin: 5px;font-size:13px"> Email at  <a href="mailto:{{$gs->from_email}}" style="color:#a1a8ad;text-decoration:underline;" target="_blank">{{$gs->from_email}}</a>
                                </p>
                            </td>
                        </tr> 
                    </table>

                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!--/100% body table-->
</body>

</html>