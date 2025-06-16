<!DOCTYPE html>
<html>
<head>
    <title>Appointment Confirmation</title>
    <!-- CSS styling for the confirmation popup -->
    <style>
        /* Basic page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
        }
        
        /* Overlay styling for modal effect */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000; /* Ensure it appears above other content */
        }
        
        /* Popup container styling */
        .popup {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); /* Drop shadow */
            padding: 30px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            position: relative;
            animation: fadeIn 0.3s ease-out; /* Fade-in animation */
        }
        
        /* Success icon styling (green circle with checkmark) */
        .success-icon {
            width: 70px;
            height: 70px;
            background-color: #4CAF50; /* Green color */
            border-radius: 50%; /* Makes it circular */
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px; /* Centered with margin below */
        }
        
        /* Checkmark SVG styling */
        .success-icon svg {
            fill: white;
            width: 40px;
            height: 40px;
        }
        
        /* Heading styling */
        h2 {
            color: #333;
            margin-bottom: 15px;
        }
        
        /* Message text styling */
        p {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        
        /* Close button styling */
        .close-btn {
            background-color: rgb(6, 86, 113);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        /* Close button hover effect */
        .close-btn:hover {
            background-color: rgb(6, 86, 113);
        }
        
        /* Fade-in animation definition */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px); /* Starts slightly above */
            }
            to {
                opacity: 1;
                transform: translateY(0); /* Ends at normal position */
            }
        }
    </style>
</head>
<body>
    <!-- Overlay div that covers the entire screen -->
    <div class="overlay">
        <!-- The actual popup container -->
        <div class="popup">
            <!-- Success icon with checkmark -->
            <div class="success-icon">
                <svg viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path>
                </svg>
            </div>
            <!-- Confirmation message -->
            <h2>Appointment Booked!</h2>
            <p>Your appointment has been successfully scheduled. We look forward to seeing you!</p>
            <!-- Close button that redirects to booking page -->
            <button class="close-btn" onclick="closePopup()">OK</button>
        </div>
    </div>

    <!-- JavaScript function to handle popup closing -->
    <script>
        function closePopup() {
            // Redirect to booking page after closing the popup
            window.location.href = "booking.php";
        }
    </script>
</body>
</html>