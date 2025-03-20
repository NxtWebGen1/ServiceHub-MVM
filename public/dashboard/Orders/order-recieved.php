<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded">
        <div class="card-header bg-success text-white text-center">
            <h2>Order Receipt</h2>
        </div>
        <div class="card-body">
            <h4 class="text-center text-primary">Thank you for your order!</h4>

            <!-- Order Details -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Order Information</h5>
                    <p><strong>Order ID:</strong> #12345</p>
                    <p><strong>Service:</strong> Web Development Consultation</p>
                    <p><strong>Status:</strong> <span class="badge bg-warning text-dark">Pending</span></p>
                </div>
                <div class="col-md-6">
                    <h5>Customer Information</h5>
                    <p><strong>Name:</strong> John Doe</p>
                    <p><strong>Email:</strong> johndoe@example.com</p>
                    <p><strong>Phone:</strong> +1 234 567 890</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <h5>Payment Details</h5>
                    <p><strong>Amount:</strong> $50</p>
                    <p><strong>Payment Method:</strong> PayPal</p>
                    <p><strong>Transaction ID:</strong> ABCD1234</p>
                </div>
                <div class="col-md-6 text-center">
                    <h5>Need Help?</h5>
                    <p>If you have any issues, contact our support team.</p>
                    <a href="mailto:support@example.com" class="btn btn-primary">Contact Support</a>
                </div>
            </div>
        </div>

        <div class="card-footer text-center">
            <a href="/" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
