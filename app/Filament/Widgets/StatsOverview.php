<?php

namespace App\Filament\Widgets;

use App\Models\Conversion;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Total Conversions', Conversion::count()),
            Stat::make('Successful Conversions', Conversion::where('status', 'completed')->count()),
            Stat::make('Failed Conversions', Conversion::where('status', 'failed')->count()),
        ];
    }
}
