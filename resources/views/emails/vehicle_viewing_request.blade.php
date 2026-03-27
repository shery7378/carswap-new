<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Viewing Request</title>
    <style>
        body { font-family: 'Public Sans', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px solid #696cff; padding-bottom: 10px; margin-bottom: 20px; }
        .vehicle-info { background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 4px solid #696cff; margin-bottom: 20px; }
        .request-details { margin-bottom: 20px; }
        .footer { font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 10px; text-align: center; margin-top: 20px; }
        .btn { background: #696cff; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Vehicle Viewing Request</h2>
        </div>
        
        <p>Hello,</p>
        <p>You have received a new viewing request for your vehicle listing on <strong>CarSwap</strong>.</p>
        
        <div class="vehicle-info">
            <strong>Vehicle:</strong> {{ $vehicle->title }}<br>
            <strong>Price:</strong> {{ $vehicle->currency }} {{ number_format($vehicle->price) }}<br>
            <strong>ID:</strong> #{{ $vehicle->id }}
        </div>
        
        <div class="request-details">
            <h3>Requestor Details</h3>
            <p><strong>Name:</strong> {{ $data['name'] }}</p>
            <p><strong>Email:</strong> {{ $data['email'] }}</p>
            <p><strong>Phone:</strong> {{ $data['phone'] ?? 'N/A' }}</p>
            
            <h3>Requested Schedule</h3>
            <p><strong>Date:</strong> {{ $data['preferred_date'] ?? 'N/A' }}</p>
            <p><strong>Time:</strong> {{ $data['preferred_time'] ?? 'N/A' }}</p>
            
            @if(!empty($data['message']))
            <h3>Message</h3>
            <p>"{{ $data['message'] }}"</p>
            @endif
        </div>
        
        <p>Please contact the potential buyer directly to confirm the viewing time.</p>
        
        <div class="footer">
            <p>© {{ date('Y') }} CarSwap. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
