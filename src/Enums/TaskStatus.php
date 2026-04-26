<?php

namespace App\Enums;



enum TaskStatus : string 
{
    case Pending = "pending";
    case Completed = "completed";
    case Encours = "en cours";

}