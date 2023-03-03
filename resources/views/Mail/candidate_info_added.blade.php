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
                                @include('includes.header')
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
                                                        Hi ,
                                                        <br />
                                                        <p>{!! $greetings !!}</p>
                                                        
                                                        <table border="2" style="border-collapse: collapse;">
                                                            <thead>
                                                                <tr>
                                                                    {{-- <th>Title</th> --}}
                                                                    <th>Name</th>
                                                                    <th>Experience</th>
                                                                    <th>Skill Set</th>
                                                                    <th>Job Title</th>
                                                                    <th>Team</th>
                                                                    <th>Location</th>
                                                                    <th>Joining Date</th>
                                                                    <th>Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    {{-- <td>{{ $title }}</td> --}}
                                                                    <td>{{ $candidateInfo[0]['name'] }}</td>
                                                                    <td>{{ $candidateInfo[0]['experience'] }}</td>
                                                                    <td>{{ implode(',', $candidateInfo[0]['skillSet'])
                                                                        }}</td>
                                                                    <td>{{ $candidateInfo[0]['jobTitle'] }}</td>
                                                                    <td>{{ $candidateInfo[0]['team'] }}</td>
                                                                    <td>{{ $candidateInfo[0]['location'] }}</td>
                                                                    <td>{{ $candidateInfo[0]['joiningDate'] }}</td>
                                                                    <td>{{ $candidateInfo[0]['status'] }}</td>
                                                                </tr>
                                                                @for ($i = 1; $i < count($candidateInfo); $i++) <tr>
                                                                   
                                                                    <td>{{ $candidateInfo[$i]['name'] }}</td>
                                                                    <td>{{ $candidateInfo[$i]['experience'] }}</td>
                                                                    <td>{{ implode(',', $candidateInfo[$i]['skillSet'])
                                                                        }}</td>
                                                                    <td>{{ $candidateInfo[$i]['jobTitle'] }}</td>
                                                                    <td>{{ $candidateInfo[$i]['team'] }}</td>
                                                                    <td>{{ $candidateInfo[$i]['location'] }}</td>
                                                                    <td>{{ $candidateInfo[$i]['joiningDate'] }}</td>
                                                                    <td>{{ $candidateInfo[$i]['status'] }}</td>
                                                </tr>
                                                @endfor
                                            </tbody>
                                        </table>



                                    </td>

                                </tr>


                                <tr
                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px">
                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px"
                                        valign="top">
                                        <p>{!! $signature !!}</p>
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                </td>
            </tr>
            @include('includes.footer')
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