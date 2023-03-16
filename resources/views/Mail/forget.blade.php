@include('includes.header')

Hi , {{ $user }}
<br />
Sorry to hear you're having trouble logging into your account.We
got a Message That you Forgot your Password. If this was you,
You can reset your password now:
<br />
Kindly click the given below button to change your password.


</td>

</tr>
<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px" z>
    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px"
        valign="top">
        <a class="btn btn-primary w-50 d-block m-auto" style="color:black"
            href="{{ env('FRONTEND_URL') }}/update-password/{{ $token }}">Reset your password</a>
    </td>
</tr>
@include('includes.footer')