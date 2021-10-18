<?php

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
				$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $dni = trim($_POST["dni"]);
        $data_nacemento = trim($_POST["data_nacemento"]);
        $enderezo = trim($_POST["enderezo"]);
        $cp = trim($_POST["cp"]);
        $cidade = trim($_POST["cidade"]);
        $ocupacion = trim($_POST["ocupacion"]);
        $telefono = trim($_POST["telefono"]);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $udc = trim($_POST["udc"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR 
            empty($dni) OR 
            empty($data_nacemento) OR
            empty($enderezo) OR
            empty($cp) OR
            empty($cidade) OR
            empty($ocupacion) OR
            empty($telefono) OR
            !filter_var($email, FILTER_VALIDATE_EMAIL) OR
            empty($udc)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Please complete the form and try again.";
            exit;
        }

        // Set the recipient email address.
        $recipient = "secretario@gpul.org";

        // Set the email subject.
        $subject = "$name se ha asociado a GPUL!";

        // Build the email content.
        $email_content = "Nombre: $name\n";
        $email_content .= "DNI: $dni\n";
        $email_content .= "Data nacemento: $data_nacemento\n";
        $email_content .= "Enderezo: $enderezo\n";
        $email_content .= "Código Postal: $cp\n";
        $email_content .= "Cidade: $cidade\n";
        $email_content .= "Ocupación: $ocupacion\n";
        $email_content .= "Teléfono: $telefono\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Estuda na udc?: $udc\n\n";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your message.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>

