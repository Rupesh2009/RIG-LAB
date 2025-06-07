<?php
// Block inspection and source view entirely for everyone
echo <<<EOD
<script>
// Disable right-click
document.addEventListener("contextmenu", e => e.preventDefault());

// Disable F12, Ctrl+Shift+I/J/C, Ctrl+U
document.addEventListener("keydown", function(e) {
    if (
        e.key === "F12" ||
        (e.ctrlKey && e.shiftKey && ["I", "J", "C"].includes(e.key)) ||
        (e.ctrlKey && e.key === "U")
    ) {
        e.preventDefault();
    }
});
</script>
EOD;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="light">

    <head>
        

        <meta charset="utf-8" />
                <title>500 | RIG LAB</title>
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
                <meta content="" name="author" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />

                <!-- App favicon -->

       
         <!-- App css -->
         <link href="assetss/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
         <link href="assetss/css/icons.min.css" rel="stylesheet" type="text/css" />
         <link href="assetss/css/app.min.css" rel="stylesheet" type="text/css" />

    </head>

    
    <!-- Top Bar Start -->
    <body>
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                    <div class="text-center p-3">
                                        <a href="dashboard" class="logo logo-admin">
                                            <img src="assetss/images/logo-sm.png" height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Sorry! Unexpected Server Error</h4>   
                                        <p class="text-muted fw-medium mb-0">Back to dashboard of RIG LAB</p>  
                                    </div>
                                </div>
                                <div class="card-body pt-0">                                    
                                    <div class="ex-page-content text-center">
                                        <img src="assetss/images/extra/error.svg" alt="0" class="" height="170">
                                        <h1 class="my-2">500!</h1>  
                                        <h5 class="fs-16 text-muted mb-3">Internal Server Error</h5>                                    
                                    </div>   
                                    <a class="btn btn-primary w-100" href="dashboard">Back to Dashboard <i class="fas fa-redo ms-1"></i></a> 
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end col-->
        </div><!--end row-->                                        
    </div><!-- container -->
    </body>
    <!--end body-->
</html>