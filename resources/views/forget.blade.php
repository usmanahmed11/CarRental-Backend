<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body style="background: #f6f6f6;">
    <table
        style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; width: 100%; background-color: #f6f6f6; margin: 0">
        <tbody>
            <tr style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
                <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; vertical-align: top; margin: 0"
                    valign="top"></td>
                <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; vertical-align: top; display: block !important; max-width: 900px !important; clear: both !important; margin: 0 auto"
                    width="900" valign="top">
                    <div
                        style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; max-width: 900px; display: block; margin: 0 auto; padding: 20px">
                        <table
                            style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9"
                            width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr
                                    style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
                                    <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 22px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #fffefe; margin: 0; padding: 0px; border-bottom: 1px solid #b5b9bd"
                                        valign="top">
                                        <strong style="padding: 15px; float: left">
                                            <span style="color: #212529">GrowthTracker</span>
                                        </strong>
                                    </td>
                                </tr>
                                <tr
                                    style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
                                    <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; vertical-align: top; margin: 0; padding: 20px"
                                        valign="top">
                                        <table
                                            style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0"
                                            width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr
                                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px">
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px"
                                                        valign="top">
                                                        Hi , {{ $user }}
                                                        <br />
                                                        Sorry to hear you're having trouble logging into your account.We
                                                        got a Message That you Forgot your Password. If this was you,
                                                        You can reset your password now:
                                                        <br />
                                                        Kindly click the given below link or copy and paste it
                                                        on your browser address bar to change your password


                                                    </td>

                                                </tr>
                                                <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px"
                                                    z>
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px"
                                                        valign="top">
                                                        <a class="btn btn-primary w-50 d-block m-auto"
                                                            style="color:black"
                                                            href="{{ env('FRONTEND_URL') }}/update-password/{{ $token }}">Reset
                                                            your password
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <span style="color:#007bff">{{ env('FRONTEND_URL') }}/update-password/{{ $token }}<span>
                                                    </td>
                                                </tr>
                                                <tr
                                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px">
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px"
                                                        valign="top">
                                                        If you didn't request a login link or a password reset,you can
                                                        ignore this message
                                                        <br />
                                                        Only people who know your account password or click the login
                                                        link in this email can login into your account
                                                    </td>

                                                </tr>
                                                <tr
                                                    style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                                                        valign="top">
                                                        <strong>Thanks</strong>,
                                                        <br>
                                                        <strong>GrowthTracker</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr
                                    style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
                                    <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 14px; vertical-align: top; color: #000; text-align: center; border-top: 1px solid #b5b9bd; margin: 0; padding: 0px; "
                                        valign="top">
                                        <div style="padding: 15px">
                                            <p style="margin-bottom:0px ; margin-top:5px">Please contact
                                                support@nxb.com.pk
                                                If you find any
                                                issue with growthtracker.vteamslabs.com</p>
                                            <p style="margin-top:5px">Copyright @2023 Nextbridge (Pvt.) Ltd.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
                <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; vertical-align: top; margin: 0"
                    valign="top"></td>
            </tr>
        </tbody>
    </table>
</body>

</html>