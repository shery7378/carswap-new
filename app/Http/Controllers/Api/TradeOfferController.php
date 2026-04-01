<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\TradeOffer;
use App\Models\EmailTemplate;
use App\Mail\DynamicTemplateMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TradeOfferController extends Controller
{
    /**
     * Store a new trade offer and notify the vehicle owner.
     */
    public function store(Request $request, $vehicleId)
    {
        $offerComment = $request->input('comment') ?? $request->input('comments');
        $validator = Validator::make($request->all(), [
            // Car Info
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:4',
            'odometer' => 'nullable|string|max:20',
            'fuel_type' => 'nullable|string|max:50',
            'displacement' => 'nullable|string|max:50',
            'gearbox_type' => 'nullable|string|max:50',
            'drive_type' => 'nullable|string|max:50',
            'exterior_color' => 'nullable|string|max:50',
            'interior_color' => 'nullable|string|max:50',

            // Files & Info
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
            'video_url' => 'nullable|url|max:255',
            'chassis_number' => 'nullable|string|max:100',
            'owner_name' => 'nullable|string|max:255',

            // Condition
            'exterior_condition' => 'nullable|string|max:100',
            'interior_condition' => 'nullable|string|max:100',
            'is_accident' => 'nullable|string|max:100',

            // Contact
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'comment' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'received_data' => $request->all() // Diagnostic helper
            ], 422);
        }

        $vehicle = Vehicle::with('user')->find($vehicleId);
        if (!$vehicle) {
            return response()->json(['success' => false, 'message' => 'Target vehicle not found'], 404);
        }

        // Identify recipient (owner of the listed vehicle)
        $owner = $vehicle->user;
        $ownerEmail = $owner ? $owner->email : null;

        if (empty($ownerEmail) && $vehicle->user_id) {
            $admin = \App\Models\Admin::find($vehicle->user_id);
            if ($admin && !empty($admin->email)) {
                $ownerEmail = $admin->email;
            }
        }

        if (empty($ownerEmail)) {
            $ownerEmail = config('settings.contactEmail') ?? config('mail.from.address') ?? 'admin@yoursite.com';
        }

        // Handle Photos upload
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('trade-offers', 'public');
                $photoPaths[] = $path;
            }
        }

        // Create the Trade Offer record
        $tradeOffer = TradeOffer::create([
            'vehicle_id' => $vehicle->id,
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'odometer' => $request->odometer,
            'fuel_type' => $request->fuel_type,
            'displacement' => $request->displacement,
            'gearbox_type' => $request->gearbox_type,
            'drive_type' => $request->drive_type,
            'exterior_color' => $request->exterior_color,
            'interior_color' => $request->interior_color,
            'chassis_number' => $request->chassis_number,
            'owner_name' => $request->owner_name,
            'video_url' => $request->video_url,
            'photos' => $photoPaths,
            'exterior_condition' => $request->exterior_condition,
            'interior_condition' => $request->interior_condition,
            'is_accident' => $request->is_accident,
            'sender_first_name' => $request->first_name,
            'sender_last_name' => $request->last_name,
            'sender_email' => $request->email,
            'sender_phone' => $request->phone,
            'comment' => $offerComment,
        ]);

        $template = EmailTemplate::where('slug', 'trade-offer-received')->first();
        if ($template) {
            $data = [
                'car_title' => $vehicle->title,
                'target_car_title' => $vehicle->title,
                'offered_car_title' => trim(($request->brand ?? '') . ' ' . ($request->model ?? '') . ' ' . ($request->year ?? '')),
                'offered_car_brand' => $request->brand ?? 'N/A',
                'offered_car_model' => $request->model ?? 'N/A',
                'offered_car_year' => $request->year ?? 'N/A',
                'offered_car_odometer' => $request->odometer ?? 'N/A',
                'offered_car_fuel' => $request->fuel_type ?? 'N/A',
                'offered_car_gearbox' => $request->gearbox_type ?? 'N/A',
                'offered_car_drive' => $request->drive_type ?? 'N/A',
                'offered_car_ext_color' => $request->exterior_color ?? 'N/A',
                'offered_car_int_color' => $request->interior_color ?? 'N/A',
                'offered_car_chassis' => $request->chassis_number ?? 'N/A',
                'offered_car_owner' => $request->owner_name ?? 'N/A',
                'exterior_condition' => $request->exterior_condition ?? 'N/A',
                'interior_condition' => $request->interior_condition ?? 'N/A',
                'is_accident' => $request->is_accident ?? 'N/A',
                'sender_name' => $request->first_name . ' ' . $request->last_name,
                'sender_email' => $request->email,
                'sender_phone' => $request->phone,
                'comment' => !empty($offerComment) ? $offerComment : 'No comment provided.',
            ];

            $rendered = $template->render($data);

            try {
                Mail::to($ownerEmail)->send(new DynamicTemplateMail($rendered['subject'], $rendered['body'], $photoPaths));
            } catch (\Exception $e) {
                // Email failed but record is saved
                return response()->json([
                    'success' => true,
                    'message' => 'Trade offer saved, but email notification failed.',
                    'data' => $tradeOffer,
                    'debug' => $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Trade offer sent successfully to the vehicle owner.',
            'data' => $tradeOffer
        ]);
    }

    /**
     * Store a new trade offer from a user's garage vehicle.
     */
    public function storeFromGarage(Request $request, $vehicleId)
    {
        $offerComment = $request->input('comment') ?? $request->input('comments');
        $validator = Validator::make($request->all(), [
            'offered_vehicle_id' => 'required|exists:vehicles,id',
            // Condition
            'exterior_condition' => 'nullable|string|max:100',
            'interior_condition' => 'nullable|string|max:100',
            'is_accident' => 'nullable|string|max:100',

            // Contact
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'comment' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $vehicle = Vehicle::with('user')->find($vehicleId);
        if (!$vehicle) {
            return response()->json(['success' => false, 'message' => 'Target vehicle not found'], 404);
        }

        $offeredVehicle = Vehicle::with([
            'brand',
            'model',
            'fuelType',
            'transmission',
            'driveType',
            'exteriorColor',
            'interiorColor'
        ])->where('user_id', auth()->id())->find($request->offered_vehicle_id);

        if (!$offeredVehicle) {
            return response()->json(['success' => false, 'message' => 'Offered vehicle not found in your garage.'], 404);
        }

        // Identify recipient (owner of the listed vehicle)
        $owner = $vehicle->user;
        $ownerEmail = $owner ? $owner->email : null;

        if (empty($ownerEmail) && $vehicle->user_id) {
            $admin = \App\Models\Admin::find($vehicle->user_id);
            if ($admin && !empty($admin->email)) {
                $ownerEmail = $admin->email;
            }
        }

        if (empty($ownerEmail)) {
            $ownerEmail = config('settings.contactEmail') ?? config('mail.from.address') ?? 'admin@yoursite.com';
        }

        // Prepare Photos
        $photoPaths = [];
        if (!empty($offeredVehicle->main_image)) {
            $photoPaths[] = $offeredVehicle->main_image;
        }
        if (!empty($offeredVehicle->gallery_images)) {
            $gallery = is_string($offeredVehicle->gallery_images) ? json_decode($offeredVehicle->gallery_images, true) : $offeredVehicle->gallery_images;
            if (is_array($gallery)) {
                $photoPaths = array_merge($photoPaths, $gallery);
            }
        }

        // Create the Trade Offer record
        $tradeOffer = TradeOffer::create([
            'vehicle_id' => $vehicle->id,
            'brand' => optional($offeredVehicle->brand)->name ?? 'N/A',
            'model' => optional($offeredVehicle->model)->name ?? 'N/A',
            'year' => $offeredVehicle->year ?? 'N/A',
            'odometer' => $offeredVehicle->mileage ?? 'N/A',
            'fuel_type' => optional($offeredVehicle->fuelType)->name ?? 'N/A',
            'displacement' => $offeredVehicle->cylinder_capacity ?? 'N/A',
            'gearbox_type' => optional($offeredVehicle->transmission)->name ?? 'N/A',
            'drive_type' => optional($offeredVehicle->driveType)->name ?? 'N/A',
            'exterior_color' => optional($offeredVehicle->exteriorColor)->name ?? 'N/A',
            'interior_color' => optional($offeredVehicle->interiorColor)->name ?? 'N/A',
            'chassis_number' => $offeredVehicle->vin_number ?? 'N/A',
            'owner_name' => auth()->user() ? auth()->user()->first_name . ' ' . auth()->user()->last_name : 'N/A',
            'photos' => $photoPaths,
            'exterior_condition' => $request->exterior_condition,
            'interior_condition' => $request->interior_condition,
            'is_accident' => $request->is_accident,
            'sender_first_name' => $request->first_name,
            'sender_last_name' => $request->last_name,
            'sender_email' => $request->email,
            'sender_phone' => $request->phone,
            'comment' => $offerComment,
        ]);

        // Send Email using Dynamic Template
        $template = EmailTemplate::where('slug', 'trade-offer-garage-received')->first();
        if ($template) {
            $data = [
                'car_title' => $vehicle->title,
                'target_car_title' => $vehicle->title,
                'offered_car_title' => $offeredVehicle->title,
                'offered_car_brand' => $tradeOffer->brand,
                'offered_car_model' => $tradeOffer->model,
                'offered_car_year' => $tradeOffer->year,
                'offered_car_odometer' => $tradeOffer->odometer,
                'offered_car_fuel' => $tradeOffer->fuel_type,
                'offered_car_gearbox' => $tradeOffer->gearbox_type,
                'offered_car_drive' => $tradeOffer->drive_type,
                'offered_car_ext_color' => $tradeOffer->exterior_color,
                'offered_car_int_color' => $tradeOffer->interior_color,
                'offered_car_chassis' => $tradeOffer->chassis_number,
                'offered_car_owner' => $tradeOffer->owner_name,
                'exterior_condition' => $tradeOffer->exterior_condition ?? 'N/A',
                'interior_condition' => $tradeOffer->interior_condition ?? 'N/A',
                'is_accident' => $tradeOffer->is_accident ?? 'N/A',
                'sender_name' => $tradeOffer->sender_first_name . ' ' . $tradeOffer->sender_last_name,
                'sender_email' => $tradeOffer->sender_email,
                'sender_phone' => $tradeOffer->sender_phone,
                'comment' => !empty($offerComment) ? $offerComment : 'No comment provided.',
            ];

            $rendered = $template->render($data);

            try {
                Mail::to($ownerEmail)->send(new DynamicTemplateMail($rendered['subject'], $rendered['body'], $photoPaths));
            } catch (\Exception $e) {
                // Email failed but record is saved
                return response()->json([
                    'success' => true,
                    'message' => 'Trade offer saved, but email notification failed.',
                    'data' => $tradeOffer,
                    'debug' => $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Trade offer sent successfully to the vehicle owner.',
            'data' => $tradeOffer
        ]);
    }
}
