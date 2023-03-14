<?php

namespace App\Http\Controllers;

use App\Mail\CandidateInfoAdded;
use App\Models\CandidateInfo;
use App\Models\EmailConfiguration;
use App\Models\jobTitle;
use Illuminate\Http\Request;
use App\Models\Growth;
use App\Models\location;
use App\Models\skillSet;
use App\Models\status;
use App\Models\team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class GrowthController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input data using Laravel's built-in validator
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'candidateInfo.*.name' => 'required',
            'candidateInfo.*.experience' => 'required|numeric',
            'candidateInfo.*.skillSet' => 'required',
            'candidateInfo.*.jobTitle' => 'required',
            'candidateInfo.*.team' => 'required',
            'candidateInfo.*.location' => 'required',
            'candidateInfo.*.joiningDate' => 'required',
            'candidateInfo.*.status' => 'required',
            'status' => 'required'
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Create a new growth record
        $growth = new Growth;
        $growth->title = $request->title;
        $growth->status = $request->input('status');
        $growth->save();

        // Loop through each candidate in the request and create a new candidate record
        foreach ($request->candidateInfo as $candidate) {
            $candidateInfo = new CandidateInfo;
            $candidateInfo->name = $candidate['name'];
            $candidateInfo->experience = $candidate['experience'];
            $candidateInfo->skillSet = implode(',', $candidate['skillSet']);

            $candidateInfo->jobTitle = $candidate['jobTitle'];
            $candidateInfo->team = $candidate['team'];
            $candidateInfo->location = $candidate['location'];
            $candidateInfo->joiningDate = $candidate['joiningDate'];
            $candidateInfo->status = $candidate['status'];
            $candidateInfo->growth_id = $growth->id;
            $candidateInfo->save();
        }

        // Check the status value
        if ($growth->status === 'Sent') {
            // Fetch email configuration data from database
            $emailConfig = EmailConfiguration::first();

            // // Send email
            $mail =  $emailConfig->to;
            $cc = $emailConfig->cc;
            $bcc = $emailConfig->bcc;
            $candidateInfo =   $request->candidateInfo;
            $subject = $emailConfig->subject;
            $greetings = $emailConfig->greetings;
            $signature = $emailConfig->signature;
            $title = $emailConfig->title;


            Mail::send("candidate_info_added", ['candidateInfo' => $candidateInfo, 'subject' => $subject, 
            'greetings' => $greetings, 'signature' => $signature, 'title' => $title], function ($message) use ($mail,$cc , $bcc) {


                $message->to($mail);
                // Add CC recipients if any
                if ($cc !== null) {
                    $message->cc($cc);
                }

                // Add BCC recipients if any
                if ($bcc !== null) {
                    $message->bcc($bcc);
                }
            
                $message->from(env('MAIL_FROM_Email'), env('MAIL_FROM_NAME'));
                $message->subject('GrowthTracker Nextbridge User Activation');
            });
        }

        // Return success response
        return response()->json(['message' => 'Candidate Info is added successfully'], 200);
    }


    public function show(Request $request)
    {
        // The number of records to be shown per page.
        $perPage = 10;
        // Get the current page number from the query string, or set it to 1 by default.
        $page = $request->query('page') ?: 1;
        // Count the total number of growth records in the database.
        $totalTitles = Growth::count();

        // Fetch the growth records along with some specific columns using query builder.
        // Order them by the latest created records, skip the records of previous pages and fetch only $perPage records per page.
        $data = DB::table('growth')
            ->select('id', 'title', 'created_at', 'status')
            ->latest()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        // Iterate over each growth record and fetch its associated candidate information using query builder.
        foreach ($data as $title) {
            $candidates = DB::table('candidate_info')
                ->select('name', 'experience', 'skillSet', 'jobTitle', 'team', 'location', 'joiningDate', 'status', 'created_at')
                ->where('growth_id', $title->id)
                ->get();
            // Add the associated candidate information to the corresponding growth record.
            $title->candidates = $candidates;
        }
        // Return the growth data along with pagination information in the form of a JSON response.
        return response()->json([
            'data' => $data,
            'total' => $totalTitles,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($totalTitles / $perPage)
        ]);
    }
    public function candidateInfo($titleId)
    {
        // Query the 'growth' table to get the title associated with the given id
        $title = DB::table('growth')
            ->where('id', $titleId)
            ->first();
        // Query the 'candidate_info' table to get information about candidates associated with the given title id
        $candidates = DB::table('candidate_info')
            ->select('name', 'experience', 'skillSet', 'jobTitle', 'team', 'location', 'joiningDate', 'status', 'created_at')
            ->where('growth_id', $titleId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Add the retrieved candidate information to the 'title' object as a new property
        $title->candidates = $candidates;

        // Return the 'title' object as a JSON response
        return response()->json([
            'data' => $title
        ]);
    }



    public function destroy($id)
    {
        // Find the Growth User to delete using the given ID
        $data = Growth::find($id);

        // Check if the user exists
        if (!$data) {
            // If the Growth User doesn't exist, return a 404 error response
            return response()->json([
                'error' => 'Growth User not found'
            ], 404);
        }

        // Delete any related CandidateInfo records for the Growth User
        $candidate_info = CandidateInfo::where('growth_id', $id)->delete();

        // Delete the Growth User itself
        $data->delete();

        // Return a success response with a message indicating that both the Growth User 
        // and related CandidateInfo records were deleted successfully
        return response()->json([
            'message' => 'Growth User and related Candidate Info records deleted successfully'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Validate the input data using Laravel's built-in validator
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:250',
            'candidateInfo.*.name' => 'required',
            'candidateInfo.*.experience' => 'required|numeric',
            'candidateInfo.*.skillSet' => 'required',
            'candidateInfo.*.jobTitle' => 'required',
            'candidateInfo.*.team' => 'required',
            'candidateInfo.*.location' => 'required',
            'candidateInfo.*.joiningDate' => 'required',
            'candidateInfo.*.status' => 'required',
            'status' => 'required'
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Finding the Growth user record to update
        $growth = Growth::find($id);

        // If the Growth user is not found, return error response
        if (!$growth) {
            return response()->json(['error' => 'Growth User not found'], 404);
        }

        // Updating Growth user record
        $growth->title = $request->title;
        $growth->status = $request->input('status');
        $growth->save();

        // Updating or Creating Candidate Info records associated with the Growth user
        foreach ($request->candidateInfo as $candidate) {
            $candidateInfo = CandidateInfo::find($candidate['id']);

            if (!$candidateInfo) {
                // Create a new Candidate Info record if not found
                $candidateInfo = new CandidateInfo;
            }

            // Updating the Candidate Info record
            $candidateInfo->growth_id = $id;
            $candidateInfo->name = $candidate['name'];
            $candidateInfo->experience = $candidate['experience'];
            $candidateInfo->skillSet = implode(',', $candidate['skillSet']);
            $candidateInfo->jobTitle = $candidate['jobTitle'];
            $candidateInfo->team = $candidate['team'];
            $candidateInfo->location = $candidate['location'];
            $candidateInfo->joiningDate = $candidate['joiningDate'];
            $candidateInfo->status = $candidate['status'];

            $candidateInfo->save();
        }

        // Returning success response
        return response()->json(['message' => 'Growth User updated successfully.'], 200);
    }


    public function showCandidate($id)
    { // Retrieve the growth information of the candidate with the given id, along with their candidateInfo
        $growth = Growth::with('candidateInfo')->find($id);
        // If the growth information of the candidate is not found, return a JSON response
        if (!$growth) {
            return response()->json(['error' => 'Growth User not found'], 404);
        }
        // If the growth information of the candidate is found, return a JSON response with the growth information and 200 status code
        return response()->json(['growth' => $growth], 200);
    }




    public function getJobTitles()
    {
        // This retrieves all job titles from the database and returns them as a JSON response.
        $jobTitles = jobTitle::all();
        return response()->json($jobTitles);
    }
    public function getTeam()
    { // This retrieves all teams from the database and returns them as a JSON response.
        $team = team::all();
        return response()->json($team);
    }
    public function getLocation()
    { // This retrieves all locations from the database and returns them as a JSON response. // This function retrieves all locations from the database and returns them as a JSON response.
        $location = location::all();
        return response()->json($location);
    }
    public function getStatus()
    {
        // This retrieves all the statues from the database and returns them as a JSON response.
        $status = status::all();
        return response()->json($status);
    }

    public function getSkillSet()
    { // This retrieves all skill Set from the database and returns them as a JSON response.
        $skillSet = skillSet::all();
        return response()->json($skillSet);
    }

    public function growthStatus(Request $request)
    {
        // Set the number of results to display per page.

        $perPage = 10;
        // Get the current page from the query parameter, or set it to 1 if it is not set.

        $page = $request->query('page') ?: 1;
        // Count the total number of growth titles that have a status of "Draft".
        $totalTitles = Growth::where('status', '=', 'Draft')->count();
        // Get the list of draft growth data from the database.
        $draftGrowth = DB::table('growth')
            ->select('id', 'title', 'email_status', 'created_at', 'status')
            ->where('status', '=', 'Draft')
            ->latest()
            ->get();

        // Return the response as a JSON object.
        return response()->json([
            'data' => $draftGrowth,
            'total' => $totalTitles,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($totalTitles / $perPage)
        ]);
    }

    public function testEmail()
    {
        $mail = 'imran.yousaf@nxvt.com';


        Mail::send("TestEmailMessage", ['user' => "Imran Yousaf", 'lead' => "test abc"], function ($message) use ($mail) {
            $message->to($mail);
            $message->from(env('MAIL_FROM_Email'), env('MAIL_FROM_NAME'));
            $message->subject('GrowthTracker Nextbridge User Activation');
        });



        return response()->json(['message' => 'Email sent successfully.'], 200);
    }
}
