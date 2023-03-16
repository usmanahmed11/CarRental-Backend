@include('includes.header')

Hi , {{ $user }}
<br />
We hope this email finds you well. We would like to extend a
warm welcome to GrowthTracker, and we are thrilled to have you
on board.
<br />
To get started with your account, kindly activate it by clicking
the button provided below:


</td>

</tr>
<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px" z>
    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px"
        valign="top">
        <a class="btn btn-primary w-50 d-block m-auto" style="color:black" href="{{ $url }}">Activate
            Your Account
        </a>
    </td>
</tr>
@include('includes.footer')