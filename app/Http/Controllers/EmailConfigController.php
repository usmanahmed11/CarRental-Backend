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
        // Validate the input data using Laravel's built-in validator
        $validator = Validator::make($request->all(), [
            'to' => 'required|array',
            'cc' => 'nullable|array',
            'bcc' => 'nullable|array',
            'subject' => 'required|string',
            'greetings' => 'required|string',
            'signature' => 'nullable|string',
        ]);
        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        // retrieve user emails from request input
        $userEmails = $request->input('to');

        // Create an array with the email configuration data
        $data = [
            'subject' => $request->input('subject'),
            'greetings' => $request->input('greetings'),
            'signature' => $request->input('signature'),
            'cc' => $request->input('cc') ? array_filter($request->input('cc')) : null,
            'bcc' => $request->input('bcc') ? array_filter($request->input('bcc')) : null,
        ];

        // Check if cc parameter exists and add it to the data array
        if ($request->has('cc')) {
            $data['cc'] = $request->input('cc') ? array_filter($request->input('cc')) : null;
        }
        // Check if bcc parameter exists and add it to the data array

        if ($request->has('bcc')) {
            $data['bcc'] = $request->input('bcc') ? array_filter($request->input('bcc')) : null;
        }

        // Convert the recipient emails array to a comma separated string
        $toEmails = implode(',', $userEmails);
        // Find the email configuration that matches any of the recipient emails
        $existingConfig = null;
        foreach ($userEmails as $email) {
            // check if email configuration already exists for any email address
            $emailConfig = EmailConfiguration::where('to', 'like', "%{$email}%")->first();
            if ($emailConfig) {
                $existingConfig = $emailConfig;
            }
        }
        // If an existing email configuration is found
        if ($existingConfig) {
            // If cc or bcc parameter exists, update the email configuration with the new data
            if ($request->has('cc') || $request->has('bcc')) {

                $existingConfig->to = $toEmails;
                $existingConfig->subject = $data['subject'];
                $existingConfig->greetings = $data['greetings'];
                $existingConfig->signature = $data['signature'];
                $existingConfig->cc = $data['cc'] ? implode(',', $data['cc']) : null;
                $existingConfig->bcc = $data['bcc'] ? implode(',', $data['bcc']) : null;
                $existingConfig->save();
            } else {
                // If cc or bcc parameter does not exist, update only the basic email configuration data
                $existingConfig->to = $toEmails;
                $existingConfig->subject = $data['subject'];
                $existingConfig->greetings = $data['greetings'];
                $existingConfig->signature = $data['signature'];
                $existingConfig->save();
            }
            // Set the email configuration to the existing one
            $emailConfig = $existingConfig;
        } else {
            // If no email configuration found, create a new configuration record
            $emailConfig = EmailConfiguration::create([
                'to' => $toEmails,
                'subject' => $data['subject'],
                'greetings' => $data['greetings'],
                'signature' => $data['signature'],
                'cc' => isset($data['cc']) ? implode(',', $data['cc']) : null,
                'bcc' => isset($data['bcc']) ? implode(',', $data['bcc']) : null,
            ]);
        }
        // Send the email to all of the recipients
        foreach ($userEmails as $email) {
            // Trim any whitespace from the email address
            $email = trim($email);
            // Create a new instance of the custom email class, passing in the data
            $mail = new CustomEmail($data);
            // If there are CC recipients, add them to the email
            if (isset($data['cc'])) {
                $mail->cc($data['cc']);
            }
            // If there are BCC recipients, add them to the email
            if (isset($data['bcc'])) {
                $mail->bcc($data['bcc']);
            }
            // Send the email to the current recipient
            Mail::to($email)->send($mail);
        }
        // Return a JSON response indicating success
        return response()->json(['message' => 'Email Configuration Updated Successfully.'], 200);
    }



    public function getEmailConfig()
    { // Get the first row from the EmailConfiguration table.
        $emailConfig = EmailConfiguration::first();
        // Return the email configuration data in JSON format.
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
