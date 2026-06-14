<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Automated cleanup of old clip files - runs every 6 hours
Schedule::command('clips:cleanup --hours=24')->everySixHours();
