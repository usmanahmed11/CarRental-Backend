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
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'email_status' => 'required',
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

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $growth = new Growth;
        $growth->title = $request->title;
        $growth->email_status = $request->email_status;
        $growth->status = $request->input('status');
        $growth->save();

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

        // Fetch email configuration data from database
        $emailConfig = EmailConfiguration::first();

        // Send email
        $mail = new CandidateInfoAdded($request->candidateInfo, $emailConfig->subject, $emailConfig->greetings, $emailConfig->signature, $growth->title);
        $to = $emailConfig->to;
        $cc = $emailConfig->cc;
        $bcc = $emailConfig->bcc;

        if ($cc !== null) {
            $mail->cc($cc);
        }

        if ($bcc !== null) {
            $mail->bcc($bcc);
        }

        Mail::to($to)->send($mail);

        // Return response
        return response()->json(['message' => 'Candidate Info is added successfully '], 200);
    }


    public function show(Request $request)
    {
        $perPage = 10;
        $page = $request->query('page') ?: 1;
        $totalTitles = Growth::count();

        $data = DB::table('growth')
            ->select('id', 'title', 'email_status', 'created_at', 'status')
            ->latest()
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        foreach ($data as $title) {
            $candidates = DB::table('candidate_info')
                ->select('name', 'experience', 'skillSet', 'jobTitle', 'team', 'location', 'joiningDate', 'status', 'created_at')
                ->where('growth_id', $title->id)
                ->get();

            $title->candidates = $candidates;
        }

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
        // Get the title and candidate information for the specified title id
        $title = DB::table('growth')
            ->where('id', $titleId)
            ->first();

        $candidates = DB::table('candidate_info')
            ->select('name', 'experience', 'skillSet', 'jobTitle', 'team', 'location', 'joiningDate', 'status', 'created_at')
            ->where('growth_id', $titleId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Add the candidate information to the title object
        $title->candidates = $candidates;

        // Return the title object as JSON
        return response()->json([
            'data' => $title
        ]);
    }



    public function destroy($id)
    {
        // Find the user to delete
        $data = Growth::find($id);

        // Check if the user exists
        if (!$data) {
            return response()->json([
                'error' => 'Growth User not found'
            ], 404);
        }

        // Delete related candidate_info records
        $candidate_info = CandidateInfo::where('growth_id', $id)->delete();

        // Delete the growth-user
        $data->delete();

        // Return a success response
        return response()->json([
            'message' => 'Growth User and related candidate_info records deleted successfully'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:250',
            'email_status' => 'required',
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

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $growth = Growth::find($id);

        if (!$growth) {
            return response()->json(['error' => 'Growth User not found'], 404);
        }

        $growth->title = $request->title;
        $growth->email_status = $request->email_status;
        $growth->status = $request->input('status');
        $growth->save();

        foreach ($request->candidateInfo as $candidate) {
            $candidateInfo = CandidateInfo::find($candidate['id']);

            if (!$candidateInfo) {
                return response()->json(['error' => 'Candidate Info not found'], 404);
            }

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

        return response()->json(['message' => 'Growth User updated successfully.'], 200);
    }

    public function showCandidate($id)
    {
        $growth = Growth::with('candidateInfo')->find($id);

        if (!$growth) {
            return response()->json(['error' => 'Growth User not found'], 404);
        }

        return response()->json(['growth' => $growth], 200);
    }




    public function getJobTitles()
    {
        $jobTitles = jobTitle::all();
        return response()->json($jobTitles);
    }
    public function getTeam()
    {
        $team = team::all();
        return response()->json($team);
    }
    public function getLocation()
    {
        $location = location::all();
        return response()->json($location);
    }
    public function getStatus()
    {
        $status = status::all();
        return response()->json($status);
    }

    public function getSkillSet()
    {
        $skillSet = skillSet::all();
        return response()->json($skillSet);
    }

    public function growthStatus(Request $request)
    {
        $perPage = 10;
        $page = $request->query('page') ?: 1;

        $totalTitles = Growth::where('status', '=', 'Draft')->count();

        $draftGrowth = DB::table('growth')
            ->select('id', 'title', 'email_status', 'created_at', 'status')
            ->where('status', '=', 'Draft')
            ->latest()
            ->get();
        return response()->json([
            'data' => $draftGrowth,
            'total' => $totalTitles,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($totalTitles / $perPage)
        ]);
    }
}
