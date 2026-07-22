<?php

use Illuminate\Support\Facades\Schedule;

// Schedule the daily ROI distribution to run every minute
Schedule::command('roi:distribute')->everyMinute();

// You can add more automated cleanup or tracking tasks here if needed
