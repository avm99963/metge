<?php
# Include the Autoloader (see "Libraries" for install instructions)
require 'vendor/autoload.php';
use Mailgun\Mailgun;

# Instantiate the client.
$mgClient = new Mailgun('key-6k-69rev4m6hbdeaa1bjjsn5y8mcqt58');
$domain = "sandbox54137.mailgun.org";

# Make the call to the client.
$result = $mgClient->sendMessage("$domain",
                  array('from'    => 'Mailgun Sandbox <postmaster@sandbox54137.mailgun.org>',
                        'to'      => 'Adrià <adria-vilanova@stpauls.es>',
                        'subject' => 'Hello Adrià',
                        'text'    => 'Congratulations Adrià, you just sent an email with Mailgun!  You are truly awesome!  You can see a record of this email in your logs: https://mailgun.com/cp/log .  You can send up to 300 emails/day from this sandbox server.  Next, you should add your own domain so you can send 10,000 emails/month for free.'));
?>