@include('includes.header')
<table style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; border-radius: 3px; background-color: #fff; margin: 0; " width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
            <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 22px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #fffefe; margin: 0; padding: 0px; border-bottom: 1px solid #b5b9bd"
                valign="top">
                <strong style="padding: 15px; float: left">
                    <span style="color: #212529">ChatOnix</span>
                </strong>
            </td>
        </tr>
        <tr style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
            <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; vertical-align: top; margin: 0; padding: 20px" valign="top">
                <table style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0" width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px">
                            <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px" valign="top">
                                Hi , {{ $user }}
                                <br /><br />
                                We hope this email finds you well. We would like to extend a warm welcome to ChatOnix, and we are thrilled to have you on board.
                                <br /><br />
                                To get started with your account, kindly activate it by clicking the button provided below:
                            </td>
                        </tr>
                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px" z>
                            <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                                valign="top">
                                <a class="btn btn-primary w-50 d-block m-auto" style="color:black" href="{{ $url }}">Activate
                                    Your Account
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
@include('includes.footer')