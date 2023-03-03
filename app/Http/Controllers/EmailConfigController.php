<?php

namespace App\Http\Controllers;

use App\Mail\CustomEmail;
use App\Models\EmailConfiguration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class EmailConfigController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'to' => 'required|array',
            'cc' => 'nullable|array',
            'bcc' => 'nullable|array',
            'subject' => 'required|string',
            'greetings' => 'required|string',
            'signature' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $userEmails = $request->input('to');

        $data = [
            'subject' => $request->input('subject'),
            'greetings' => $request->input('greetings'),
            'signature' => $request->input('signature'),
            'cc' => $request->input('cc') ? array_filter($request->input('cc')) : null,
            'bcc' => $request->input('bcc') ? array_filter($request->input('bcc')) : null,
        ];
        // Extract the date format from the placeholder
        // preg_match('/\{\{date:([^\}]+)\}\}/', $data['subject'], $matches);
        // $dateFormat = isset($matches[1]) ? $matches[1] : 'l d M, Y';

        // Replace all occurrences of the date placeholder with the actual date

        // $dateString = Carbon::now()->format('l d M, Y');
        // $data['subject'] = str_replace('{{date:dddd DD MMM, YYYY}}', $dateString, $data['subject']);

        if ($request->has('cc')) {
            $data['cc'] = $request->input('cc') ? array_filter($request->input('cc')) : null;
        }

        if ($request->has('bcc')) {
            $data['bcc'] = $request->input('bcc') ? array_filter($request->input('bcc')) : null;
        }
        $toEmails = implode(',', $userEmails);

        $existingConfig = null;
        foreach ($userEmails as $email) {
            $emailConfig = EmailConfiguration::where('to', 'like', "%{$email}%")->first();
            if ($emailConfig) {
                $existingConfig = $emailConfig;
            }
        }

        if ($existingConfig) {

            if ($request->has('cc') || $request->has('bcc')) {

                $existingConfig->to = $toEmails;
                $existingConfig->subject = $data['subject'];
                $existingConfig->greetings = $data['greetings'];
                $existingConfig->signature = $data['signature'];
                $existingConfig->cc = $data['cc'] ? implode(',', $data['cc']) : null;
                $existingConfig->bcc = $data['bcc'] ? implode(',', $data['bcc']) : null;
                $existingConfig->save();
            } else {
                $existingConfig->to = $toEmails;
                $existingConfig->subject = $data['subject'];
                $existingConfig->greetings = $data['greetings'];
                $existingConfig->signature = $data['signature'];
                $existingConfig->save();
            }

            $emailConfig = $existingConfig;
        } else {
            $emailConfig = EmailConfiguration::create([
                'to' => $toEmails,
                'subject' => $data['subject'],
                'greetings' => $data['greetings'],
                'signature' => $data['signature'],
                'cc' => isset($data['cc']) ? implode(',', $data['cc']) : null,
                'bcc' => isset($data['bcc']) ? implode(',', $data['bcc']) : null,
            ]);
        }

        foreach ($userEmails as $email) {
            $email = trim($email);

            $mail = new CustomEmail($data);

            if (isset($data['cc'])) {
                $mail->cc($data['cc']);
            }

            if (isset($data['bcc'])) {
                $mail->bcc($data['bcc']);
            }

            Mail::to($email)->send($mail);
        }

        return response()->json(['message' => 'Email Configuration Updated Successfully.'], 200);
    }



    public function getEmailConfig()
    {
        $emailConfig = EmailConfiguration::first();
        return response()->json([
            'to' => explode(',', $emailConfig->to),
            'cc' => explode(',', $emailConfig->cc),
            'bcc' => explode(',', $emailConfig->bcc),
            'subject' => $emailConfig->subject,
            'greetings' => $emailConfig->greetings,
            'signature' => $emailConfig->signature
        ]);
    }
}
