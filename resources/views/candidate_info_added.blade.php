<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="{{ asset('theme/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

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
                                                        Hi ,
                                                        <br />
                                                        <p><strong>Subject: </strong>{{ $subject }}</p>
                                                        <p>{!! $greetings !!}</p>

                                                        <table id="datatable" class="table table-striped table-bordered"
                                                            width="100%">

                                                            <thead>
                                                                <tr>

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

                                                                    <td>{{ $candidateInfo[0]['name'] }}</td>
                                                                    <td>{{ $candidateInfo[0]['experience'] }}</td>
                                                                    <td>{{ implode(',', $candidateInfo[0]['skillSet'])
                                                                        }}</td>
                                                                    <td>{{ $candidateInfo[0]['jobTitle'] }}</td>
                                                                    <td>{{ $candidateInfo[0]['team'] }}</td>
                                                                    <td>{{ $candidateInfo[0]['location'] }}</td>
                                                                    <td>{{ date('F j, Y', strtotime($candidateInfo[0]['joiningDate'])) }}</td>

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
                                                                    <td>{{ date('F j, Y',
                                                                        strtotime($candidateInfo[$i]['joiningDate'])) }}
                                                                    </td>

                                                                    <td>{{ $candidateInfo[$i]['status'] }}</td>
                                                </tr>
                                                @endfor
                                            </tbody>
                                        </table>

                                    </td>

                                </tr>
                                <tr>
                                    <td <p>{!! $signature !!}</p>

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
            <tr style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
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
    <script src="{{ asset('theme/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('theme/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>

</body>

</html>