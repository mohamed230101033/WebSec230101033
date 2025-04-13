<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;


class VerificationEmail extends Mailable
{
 private $link = null;
 private $name = null;

 public function __construct($link, $name) {
 $this->link = $link; $this->name = $name;
 }

 public function content(): Content
 {
 return new Content(
 view: 'emails.verification',
 with: [ 'link' => $this->link,'name' => $this->name],
 );
 }
}
