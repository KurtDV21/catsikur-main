<?php
$routes = [];

// Define the route function
function route(string $path, callable $callback) {
    global $routes;
    $routes[$path] = $callback;
}

// Define the run function to match routes
function run() {
    global $routes;

    // Normalize the request URI
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/');
    $uri = $uri === '' ? '/' : $uri;

    foreach ($routes as $path => $callback) {
        if ($path !== $uri) continue;

        // Call the matching callback
        $callback();
        return;
    }

    http_response_code(404);
    echo "404 Not Found";
}

// Define routes
route('/', function () {
    include __DIR__ . '/../resource/view/home.php';
});

route('/loginto', function () {
    include __DIR__ . '/../resource/view/loginto.php';
});

route('/admin-login', function () {
    include __DIR__ . '/../resource/view/admin-login.php';
});

route('/register-form', function () {
    include __DIR__ . '/../resource/view/register-form.php';
});

route('/admin-register', function () {
    include __DIR__ . '/../resource/view/admin-register.php';
});

route('/post-adoption', function () {
    include __DIR__ . '/../resource/view/post-adoption.php';
});

route('/add-post', function () {
    include __DIR__ . '/../resource/view/Add_post.php';
});

route('/process-addpost', function () {
    include __DIR__ . '/../public/process/add_post.php';
});
route('/inquiry-form', function () {
    include __DIR__ . '/../resource/view/inquiry-form.php';
});

route('/inquiry-form2', function () {
    include __DIR__ . '/../resource/view/inquiry-form2.php';
});

route('/inquiry-form3', function () {
    include __DIR__ . '/../resource/view/inquiry-form3.php';
});

route('/inquiry-form4', function () {
    include __DIR__ . '/../resource/view/inquiry-form4.php';
});
route('/user-homepage', function () {
    include __DIR__ . '/../resource/view/user-homepage.php';
});

route('/admin-homepage', function () {
    include __DIR__ . '/../resource/view/admin-homepage.php';
});

route('/admin-rescue', function () {
    include __DIR__ . '/../resource/view/admin-rescue.php';
});

route('/admin-adoption', function () {
    include __DIR__ . '/../resource/view/admin-adoption.php';
});

route('/admin', function () {
    include __DIR__ . '/../resource/view/admin.php';
});

route('/process-signup', function () {
    include __DIR__ . '/../resource/view/auth/process-signup.php';
});

route('/process-admin-signup', function () {
    include __DIR__ . '/../resource/view/auth/process-admin-signup.php';
});

route('/signup-success', function () {
    include __DIR__ . '/../resource/view/layout/signup-success.php';
});

route('/ajaxFilterPosts', function () {
    
    include __DIR__ . '/../resource/view/ajaxFilterPosts.php';
});

route('/admin-restrict', function () {
    
    include __DIR__ . '/../resource/view/admin-restrict.php';
});

route('/admin-pdf', function () {
    
    include __DIR__ . '/../resource/view/admin-pdf.php';
});

route('/fpdf', function () {
    
    include __DIR__ . '/../resource/view/fpdf/fpdf.php';
});

route('/chat', function () {
    
    include __DIR__ . '/../resource/view/chat.php';
});


route('/process-form-approval', function () {
    
    include __DIR__ . '/../resource/view/process-form-approval.php';
});


route('/restrict-user', function () {
    
    include __DIR__ . '/../resource/view/restrict-user.php';
});

route('/activate-account', function () {
    include __DIR__ . '/../resource/view/auth/activate-account.php';    
});

route('/otp-verify', function () {
    include __DIR__ . '/../resource/view/layout/otp-verify.php';
});

route('/otp', function () {
    include __DIR__ . '/../resource/view/layout/otp.php';
});

route('/otp-admin', function () {
    include __DIR__ . '/../resource/view/layout/otp-admin.php';
});

route('/logout', function () {
    include __DIR__ . '/../resource/view/auth/logout.php';
});

route('/forgot-password', function () {
    include __DIR__ . '/../resource/view/layout/forgot-password.php';
});

route('/reset-password', function () {
    include __DIR__ . '/../resource/view/layout/reset-password.php';
});

route('/changepass-success', function () {
    include __DIR__ . '/../resource/view/layout/changepass-success.php';
});


route('/email-check', function () {
    include __DIR__ . '/../resource/view/layout/email-check.php';
});

route('/process-reset-password', function () {
    include __DIR__ . '/../resource/view/auth/process-reset-password.php';
});


route('/cat-details', function () {
    include __DIR__ . '/../resource/view/catdetails.php';
});

route('/profile2', function () {
    include __DIR__ . '/../resource/view/profile2.php';
});

route('/process-profile2', function () {
    include __DIR__ . '/../resource/view/auth/process-profile2.php';
});

route('/send-password-reset', function () {
    include __DIR__ . '/../resource/view/auth/send-password-reset.php';
});

route('/updateApproval', function () {
    include __DIR__ . '/../public/process/updateApproval.php';
});

// Run the routing logic
run();
