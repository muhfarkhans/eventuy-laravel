<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'Total User';

    protected static ?int $sort = 21;

    protected function getData(): array
    {
        $dataClient = Trend::query(User::with([
            'roles'
        ])->whereHas('roles', function ($query) {
            $query->where('name', 'client')->where('name', '!=', 'super_admin');
        }))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $dataUser = Trend::query(User::with([
            'roles'
        ])->whereHas('roles', function ($query) {
            $query->where('name', 'user')->where('name', '!=', 'super_admin');
        }))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Client',
                    'data' => $dataClient->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#06b6d4',
                    'borderColor' => '#0ea5e9',
                ],
                [
                    'label' => 'User',
                    'data' => $dataUser->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#a855f7',
                    'borderColor' => '#d946ef',
                ],
            ],
            'labels' => $dataUser->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
