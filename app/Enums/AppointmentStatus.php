<?php

namespace App\Enums;

enum AppointmentStatus: string {
    case Pending = 'pending';
    case Started = 'started';
    case Ended = 'ended';
}
