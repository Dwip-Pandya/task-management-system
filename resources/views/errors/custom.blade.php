<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>Error - TaskFlow Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        
    </style>
</head>

<body>
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>

    <div class="error-container">
        <div class="error-glass-card">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

            <h1 class="error-title">Oops! Something Went Wrong</h1>

            <p class="error-message">
                {{ $error_message ?? 'An unexpected error occurred. Please try again or contact support if the problem persists.' }}
            </p>

            <div class="error-actions">
                <a href="{{ url()->previous() }}" class="error-btn secondary">
                    <i class="fas fa-arrow-left"></i>
                    Go Back
                </a>
                <a href="{{ url('/') }}" class="error-btn primary">
                    <i class="fas fa-home"></i>
                    Return Home
                </a>

            </div>

            @if(isset($error_code))
            <div class="error-code">
                Error Code: {{ $error_code }}
            </div>
            @endif
        </div>
    </div>

    <script>
        // Add smooth entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.error-glass-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';

            setTimeout(() => {
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>

</html>