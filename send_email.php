<?php
	//Import PHPMailer classes into the global namespace
	//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

	//Load Composer's autoloader
require 'vendor/autoload.php';

	// echo "Successfully imported sendemail.php";

function send_email($email, $firstName_sess, $lastName_sess, $restaurantName, $cartID, $count, $foodName_list, $price_list, 
										$quantity_list_sess, $deliveryFee, $rdopayment, $foodID_list_sess, $totalAmount, $chosen_address, $chosen_latitude, 
										$chosen_longitude, $rdodelivery)
{
	// echo $QACoordinatorEmail,$txtideaid,$txttitle,$txtdescription,$FileName,$date,$time,$anonymous,$cbocategory,$userid;
	//Create an instance; passing `true` enables exceptions
	$mail = new PHPMailer(true);
	try
	{
			//Server settings
		// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		$mail->SMTPDebug = SMTP::DEBUG_OFF;
		$mail->isSMTP();                                          //Send using SMTP
		$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username   = 'htunh9609@gmail.com';                     //SMTP username
		$mail->Password   = 'gdjuckqptzfwpmcx';                               //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port       = 465; 

		//Recipients
		$mail->setFrom('htunh9609@gmail.com', 'StrEat');
		// $mail->addAddress('hatsunegranger@gmail.com', 'Joe User');     //Add a recipient
		$mail->addAddress($email);               //Name is optional
		$mail->addReplyTo('htunh9609@gmail.com', 'Information');
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');

		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = "Your New Order to $restaurantName";

		$mail->Body    = "Dear $firstName_sess $lastName_sess, you ordered the following items from $restaurantName.<br/>
											Cart ID: $cartID<br/>
											Food Entries: 
											<br/><br/>";
		$mail->Body .= "<table>
											<tr>
												<th>Food Name</th>
												<th></th>
												<th>Price</th>
												<th></th>
												<th>Quantity</th>
												<th></th>
												<th>Total</th>
											</tr>";	

		$sub_total = 0;
		for($i=0; $i<$count; $i++){
			$foodName = $foodName_list[$i];
			$price = $price_list[$i];
			$quantity = $quantity_list_sess[$i];
			$total = $price * $quantity;
			$sub_total += $total;
			$mail->Body .= "<tr>
												<td>$foodName</td>
												<td></td>
												<td>$price</td>
												<td> x </td>
												<td>$quantity</td>
												<td>=</td>
												<td>$total Ks</td>
											</tr>";
		}
		$mail->Body .= "<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>Subtotal</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>=</td>
											<td>$sub_total Ks</td>
										</tr>";
		$mail->Body .= "<tr>
											<td>Delivery Fees</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>=</td>
											<td>$deliveryFee Ks</td>
										</tr>
										<tr>
											<td><b>Grand Total</b></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td><b>=</b></td>
											<td><b>$totalAmount Ks</b></td>
										</tr>";
		$mail->Body .= "</table>
										<br/><br/>";
		$mail->Body .= "To address: $chosen_address<br/>
										Latitude & Longitude: $chosen_latitude, $chosen_longitude<br/>
										Delivery Type: $rdodelivery<br/>
										Payment Type: $rdopayment<br/>";
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
		// echo "<script>window.alert('Message has been sent!')</script>";
	} 
	catch (Exception $e)
	{
		echo "In the catch condition";
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
}
?>