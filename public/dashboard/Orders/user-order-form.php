

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h4>Place Your Order</h4>
        </div>
        <div class="card-body">
            <form id="orderForm" action="order-auth.php" method="POST">
                
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
                </div>

                <!-- Phone -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number *</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required placeholder="Enter your phone number">
                </div>

                <!-- Address (Optional) -->
                <div class="mb-3">
                    <label for="address" class="form-label">Address (Optional)</label>
                    <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter your address"></textarea>
                </div>

                <!-- Additional Notes (Optional) -->
                <div class="mb-3">
                    <label for="notes" class="form-label">Additional Notes (Optional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special requests?"></textarea>
                </div>

                <!-- Hidden Fields for Service and Vendor IDs -->
                <input type="hidden" name="service_id" value="SERVICE_ID_HERE">
                <input type="hidden" name="vendor_id" value="VENDOR_ID_HERE">

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit Order</button>
                </div>
            </form>
        </div>
    </div>
</div>


