@include('includes.header')

<p>{!! $greetings !!}</p>

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
@include('includes.candidate-footer')