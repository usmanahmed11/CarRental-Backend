@include('includes.candidate-header')
<table style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; border-radius: 3px; background-color: #fff; margin: 0; " width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
            <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 22px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #fffefe; margin: 0; padding: 0px; border-bottom: 1px solid #b5b9bd"
                valign="top">
                <strong style="padding: 15px; float: left">
                    <span style="color: #212529">GrowthTracker</span>
                </strong>
            </td>
        </tr>
        <tr style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
            <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; vertical-align: top; margin: 0; padding: 20px" valign="top">
                <table style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0" width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px">
                            <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px" valign="top">
                                {!! $greetings !!}
                            </td>
                        </tr>
                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px">
                            <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px" valign="top">
                                <table id="datatable" class="table table-striped table-bordered" width="100%">
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
                                        @for ($i =0; $i < count($candidateInfo); $i++)
                                            <tr>
                                                <td>{{ $candidateInfo[$i]['name'] }}</td>
                                                <td>{{ $candidateInfo[$i]['experience'] }}</td>
                                                <td>{{ implode(',', $candidateInfo[$i]['skillSet']) }}</td>
                                                <td>{{ $candidateInfo[$i]['jobTitle'] }}</td>
                                                <td>{{ $candidateInfo[$i]['team'] }}</td>
                                                <td>{{ $candidateInfo[$i]['location'] }}</td>
                                                <td>{{ date('F j, Y', strtotime($candidateInfo[$i]['joiningDate'])) }} </td>
                                                <td>{{ $candidateInfo[$i]['status'] }}</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {!! $signature !!}
                                <br /><br />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 12px; margin: 0">
            <td style="font-family: Helvetica, 'Open Sans', Arial; box-sizing: border-box; font-size: 14px; vertical-align: top; color: #000; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #fffefe; margin: 0; padding: 0px; border-top: 1px solid #b5b9bd"
                valign="top">
                <p style="margin-bottom:0px ; margin-top:5px">Please contact support@nxb.com.pk If you find any issue with growthtracker.vteamslabs.com</p>
                <p style="margin-top:5px">Copyright @2023 Nextbridge (Pvt.) Ltd.</p>
            </td>
        </tr>
    </tbody>
</table>

@include('includes.candidate-footer')