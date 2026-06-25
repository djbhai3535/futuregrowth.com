<?php

use Illuminate\Support\Facades\Schedule;

// Schedule the daily ROI distribution to run every day at midnight
Schedule::command('roi:distribute')->daily();

// You can add more automated cleanup or tracking tasks here if needed
