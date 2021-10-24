<?php
//Error vaiables
$nameErr = $emailErr = $phoneErr = $dniErr = $dateErr = $addressErr = $cpErr = $cityErr = $jobErr = $udcErr = "";
$name = $dni = $data_nacemento = $enderezo = $cp = $cidade = $ocupacion = $telefono = $email = $udc = "";

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        if (empty($_POST["name"])) {
            $nameErr = "Introduza un nome.";
        } else {
            $name = strip_tags(trim($_POST["name"]));
            $name = str_replace(array("\r","\n"),array(" "," "),$name);
            if (!preg_match("/^[a-zA-ZñÑáÁéÉíÍóÓúÚçÇäÄëËïÏöÖüÜàÀèÈìÌòÒùÙâÂêÊîÎôÔûÛ' ]*$/", $name)) {
                $nameErr = "Nome inválido: só letras e espazos en blanco permitidos.";
            }
        }

        if (empty($_POST["dni"])) {
            $dniErr = "Introduza un DNI.";
        } else {
            $dni = trim($_POST["dni"]);
            if (!preg_match("/^[0-9]{8}[a-zA-Z]$|^[XYZ][0-9]{7}[a-zA-Z]$|^[a-zA-Z]{3}[0-9]{6}[a-zA-Z]?$/", $dni)) {
                $dniErr = "Introduza un DNI válido (12345678D).";
            }
        }
        
        if (empty($_POST["data_nacemento"])) {
            $dateErr = "Introduza unha data de nacemento.";
        } else {
            $data_nacemento = trim($_POST["data_nacemento"]);
            if (!preg_match("/[0-9]{2}-[0-9]{2}-[0-9]{4}$/", $data_nacemento)) {
                $dateErr = "Data de nacemento inválida (DD-MM-YYYY).";
            }
        }

        if (empty($_POST["enderezo"])) {
            $addressErr = "Introduza un enderezo.";
        } else {
            $enderezo = trim($_POST["enderezo"]);
        }
        
        if (empty($_POST["cp"])) {
            $cpErr = "Introduza un código postal.";
        } else {
            $cp = trim($_POST["cp"]);
            if (!preg_match("/^[0-9]{5}$/", $cp)) {
                $cpErr = "Introduza un código postal válido (12345).";
            }
        }

        if (empty($_POST["cidade"])) {
            $cityErr = "Introduza unha cidade.";
        } else {
            $cidade = trim($_POST["cidade"]);
            if (!preg_match("/^[a-zA-ZñÑáÁéÉíÍóÓúÚçÇäÄëËïÏöÖüÜàÀèÈìÌòÒùÙâÂêÊîÎôÔûÛ' ]*$/", $cidade)) {
            $cityErr = "Cidade inválida: só letras e espazos en blanco permitidos.";
            }
        }

        if (empty($_POST["ocupacion"])) {
            $jobErr = "Introduza unha ocupación.";
        } else {
            $ocupacion = trim($_POST["ocupacion"]);
            if (!preg_match("/^[a-zA-ZñÑáÁéÉíÍóÓúÚçÇäÄëËïÏöÖüÜàÀèÈìÌòÒùÙâÂêÊîÎôÔûÛ' ]*$/", $ocupacion)) {
            $jobErr = "Ocupación inválida: só letras e espazos en blanco permitidos.";
            }
        }

        if (empty($_POST["telefono"])) {
            $phoneErr = "Introduza un número de teléfono.";
        } else {
            $telefono = trim($_POST["telefono"]);
            if (!preg_match("/^[0-9]{9}$/", $telefono)) {
                $phoneErr = "Introduza un teléfono válido (123456789).";
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Introduza un email.";
        } else {
            $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
            if (!filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL)) {
                $emailErr = "Introduza un email válido (email@dominio).";
            }
        }

        if (empty($_POST["udc"])) {
            $udcErr = "Introduza se estudas na UDC.";
        } else {
            $udc = trim($_POST["udc"]);
            if (!preg_match("/^[a-zA-ZñÑáÁéÉíÍóÓúÚçÇäÄëËïÏöÖüÜàÀèÈìÌòÒùÙâÂêÊîÎôÔûÛ' ]*$/", $udc)) {
                $udcErr = "Só letras e espazos en blanco permitidos.";
            }
        }

        // Check that data was sent to the mailer.
        if (!empty($nameErr) OR 
            !empty($dniErr) OR 
            !empty($dateErr) OR
            !empty($addressErr) OR
            !empty($cpErr) OR
            !empty($cityErr) OR
            !empty($jobErr) OR
            !empty($phoneErr) OR
            !empty($emailErr) OR
            !empty($udcErr)) {
            // Set a 400 (bad request) response code and exit.
            // http_response_code(400);
            header('Location: http_codes/400.html');         
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
            // http_response_code(200);
            header("Location: http_codes/200.html")
            echo "Thank You! Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            // http_response_code(500);
            header("Location: http_codes/500.html")
            echo "Oops! Something went wrong and we couldn't send your message.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        // http_response_code(403);
        header("Locarion: http_codes/403.html")
        echo "There was a problem with your submission, please try again.";
    }

?>

