<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Purchase!</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #21242e, #21242e);
            color: #fff;
        }
        .container {
            background: #ffffff;
            color: #333;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .logo img {
            max-width: 150px; /* Adjust size as needed */
            margin-bottom: 20px;
        }
        .message {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #32325d;
        }
        .thank-you-note {
            font-size: 16px;
            line-height: 1.6;
            color: #525f7f;
            margin-bottom: 30px;
        }
        .back-button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            background-color:#f68b08;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 12px rgba(103, 114, 229, 0.4);
        }
        .back-button:hover {
            background-color: #d68b2f;
        }
        .back-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(50, 115, 220, 0.5);
        }
    </style>
    <script>
        function goBack() {
            window.location.href = "/shop";
        }

        // Clear the cart from local storage
        localStorage.removeItem('cart');

        // Automatically go back after 7 seconds
        setTimeout(goBack, 7000);
    </script>
</head>
<body>
    <div class="container">
        <!-- Logo centered at the top -->
        <div class="logo">
            <a href="https://interplus.my/user/me">
                <img src="/assets/images/interplus_logo_merdeka.png" alt="Hotel logo">
            </a>
        </div>

        <!-- Success message -->
        <div class="message">Payment Successful!</div>
        <div class="thank-you-note">
            We appreciate your support! <br>
            Please allow a moment for your transaction to be processed. If the amount hasn't been credited to your account yet, don't hesitate to reach out to our support team. <br>
            You’ll be redirected back to the shop soon.
        </div>


        <!-- Back to Shop button -->
        <a href="javascript:void(0);" class="back-button" onclick="goBack()">Back to Shop</a>
    </div>
</body>
</html>
