<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleInquiry;
use App\Models\EmailTemplate;
use App\Mail\DynamicTemplateMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class VehicleViewingController extends Controller
{
    /**
     * Store a general inquiry about a vehicle.
     */
    public function inquiry(Request $request, $vehicleId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $vehicle = Vehicle::with('user')->find($vehicleId);
        if (!$vehicle) return response()->json(['success' => false, 'message' => 'Vehicle not found'], 404);

        $owner = $vehicle->user;
        if (!$owner || empty($owner->email)) return response()->json(['success' => false, 'message' => 'Owner email not found'], 400);

        // Save
        $inquiry = VehicleInquiry::create([
            'vehicle_id' => $vehicle->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // Send Dynamic Email
        $template = EmailTemplate::where('slug', 'vehicle-inquiry')->first();
        if ($template) {
            $data = [
                'car_title' => $vehicle->title,
                'sender_name' => $request->name,
                'sender_email' => $request->email,
                'sender_phone' => $request->phone ?? 'N/A',
                'message_content' => $request->message,
            ];
            $rendered = $template->render($data);
            Mail::to($owner->email)->send(new DynamicTemplateMail($rendered['subject'], $rendered['body']));
        }

        return response()->json(['success' => true, 'message' => 'Inquiry sent to vehicle owner.', 'data' => $inquiry]);
    }

    /**
     * Store a viewing request.
     */
    public function store(Request $request, $vehicleId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'required|string|max:50',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $vehicle = Vehicle::with('user')->find($vehicleId);
        if (!$vehicle) return response()->json(['success' => false, 'message' => 'Vehicle not found'], 404);

        $owner = $vehicle->user;
        if (!$owner || empty($owner->email)) return response()->json(['success' => false, 'message' => 'Owner email not found'], 400);

        $inquiry = VehicleInquiry::create([
            'vehicle_id' => $vehicle->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // Send Dynamic Email
        $template = EmailTemplate::where('slug', 'arrange-viewing-time')->first();
        if ($template) {
            $data = [
                'car_title' => $vehicle->title,
                'sender_name' => $request->name,
                'sender_email' => $request->email,
                'sender_phone' => $request->phone ?? 'N/A',
                'requested_date' => $request->preferred_date,
                'requested_time' => $request->preferred_time,
                'message_content' => $request->message ?? 'No message provided.',
            ];
            $rendered = $template->render($data);
            Mail::to($owner->email)->send(new DynamicTemplateMail($rendered['subject'], $rendered['body']));
        }

        return response()->json(['success' => true, 'message' => 'Viewing request sent to owner.', 'data' => $inquiry]);
    }
}
