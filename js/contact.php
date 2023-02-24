<?php 
// Google reCAPTCHA API key configuration 
$siteKey     = '6LcpEqkkAAAAADtsbijeRHLdcbreS8C4sL2zZ0ts'; 
$secretKey     = '6LcpEqkkAAAAALw6AMTwPt25uKCIEqGVAlx8lfjy'; 
 
// Email configuration 
$toEmail = 'kimyoungjin06@gmail.com'; 
$fromName = 'Wedding RSVP'; 
$formEmail = 'kimyoungjin06@gmail.com'; 
 
$postData = $statusMsg = $valErr = ''; 
$status = 'error'; 
 
// If the form is submitted 
if(isset($_POST['submit'])){ 
    // Get the submitted form data 
    $postData = $_POST; 
    $name = trim($_POST['name']); 
    $email = trim($_POST['email']); 
    $attend_wedding = trim($_POST['attend_wedding_yes']); 
    $need_room = trim($_POST['need_room_yes']); 
    $need_bus = trim($_POST['need_bus_yes']); 
    $num_guests = trim($_POST['num_guests']); 
    $meal_meat = trim($_POST['meal_meat']); 
    $meal_fish = trim($_POST['meal_fish']); 
    $Soju = trim($_POST['Soju']); 
    $Beer = trim($_POST['Beer']); 
    $message = trim($_POST['message']); 
     
    // Validate form fields 
    if(empty($name)){ 
        $valErr .= 'Please enter your name.<br/>'; 
    } 
    if(empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false){ 
        $valErr .= 'Please enter a valid email.<br/>'; 
    }
    if(empty($message)){ 
        $valErr .= 'Please enter your message.<br/>'; 
    } 
     
    if(empty($valErr)){ 
         
        // Validate reCAPTCHA box 
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){ 
 
            // Verify the reCAPTCHA response 
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']); 
             
            // Decode json data 
            $responseData = json_decode($verifyResponse); 
             
            // If reCAPTCHA response is valid 
            if($responseData->success){ 
 
                // Send email notification to the site admin 
                $subject = 'New contact request submitted'; 
                $htmlContent = " 
                    <h2>Contact Request Details</h2> 
                    <p><b>Name: </b>".$name."</p> 
                    <p><b>Email: </b>".$email."</p> 
                    <p><b>attend_wedding: </b>".$attend_wedding."</p> 
                    <p><b>need_room: </b>".$need_room."</p> 
                    <p><b>need_bus: </b>".$need_bus."</p> 
                    <p><b>num_guests: </b>".$num_guests."</p> 
                    <p><b>meal_meat: </b>".$meal_meat."</p> 
                    <p><b>meal_fish: </b>".$meal_fish."</p> 
                    <p><b>Soju: </b>".$Soju."</p> 
                    <p><b>Beer: </b>".$Beer."</p> 
                    <p><b>Message: </b>".$message."</p> 
                "; 
                 
                // Always set content-type when sending HTML email 
                $headers = "MIME-Version: 1.0" . "\r\n"; 
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
                // More headers 
                $headers .= 'From:'.$fromName.' <'.$formEmail.'>' . "\r\n"; 
                 
                // Send email 
                @mail($toEmail, $subject, $htmlContent, $headers); 
                 
                $status = 'success'; 
                $statusMsg = 'Thank you! Your contact request has submitted successfully, we will get back to you soon.'; 
                $postData = ''; 
            }else{ 
                $statusMsg = 'Robot verification failed, please try again.'; 
            } 
        }else{ 
            $statusMsg = 'Please check on the reCAPTCHA box.'; 
        } 
    }else{ 
        $statusMsg = '<p>Please fill all the mandatory fields:</p>'.trim($valErr, '<br/>'); 
    } 
}

// Display status message 
echo $statusMsg;