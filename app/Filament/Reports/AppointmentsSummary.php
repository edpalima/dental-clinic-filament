<?php

namespace App\Filament\Reports;

use App\Models\Appointment;
use EightyNine\Reports\Components\VerticalSpace;
use EightyNine\Reports\Report;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Body\TextColumn;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\Image;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\DB;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Carbon\Carbon;

class AppointmentsSummary extends Report
{
    public ?string $heading = "Report";

    // public ?string $subHeading = "A great report";

    public function header(Header $header): Header
    {
        return $header
            ->schema([
                Header\Layout\HeaderRow::make()
                    ->schema([
                        Header\Layout\HeaderColumn::make()
                            ->schema([
                                Text::make("Appointment Summary Report")
                                    ->title()
                                    ->primary(),
                                Text::make("Appointment Summary Report")
                                    ->subtitle(),
                            ]),
                        Header\Layout\HeaderColumn::make()
                            ->schema([
                                Image::make(asset('assets/img/logo.png')),
                            ])
                            ->alignRight(),
                    ]),
            ]);
    }


    public function body(Body $body): Body
    {
        return $body
            ->schema([
                Body\Table::make()
                    ->columns([
                        TextColumn::make("id"),
                        TextColumn::make("patient"),
                        TextColumn::make("doctor"),
                        TextColumn::make("date"),
                        TextColumn::make("time"),
                        TextColumn::make("status"),
                    ])
                    ->data(
                        fn(?array $filters) => $this->appointmentSummary($filters)
                    ),
                VerticalSpace::make(),
            ]);
    }

    public function footer(Footer $footer): Footer
    {
        return $footer
            ->schema([
                Footer\Layout\FooterRow::make()
                    ->schema([
                        Footer\Layout\FooterColumn::make()
                            ->schema([
                                // Text::make("Footer title")
                                //     ->title()
                                //     ->primary(),
                                // Text::make("Footer subtitle")
                                //     ->subtitle(),
                            ]),
                        Footer\Layout\FooterColumn::make()
                            ->schema([
                                Text::make("Generated on: " . now()->format('Y-m-d H:i:s')),
                            ])
                            ->alignRight(),
                    ]),
            ]);
    }

    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([
                // DateRangeFilter::make('created_at')
                //     ->modifyQueryUsing(
                //         fn(Builder $query, ?Carbon $startDate, ?Carbon $endDate, $dateString) =>
                //         $query->when(
                //             !empty($dateString),
                //             fn(Builder $query, $date): Builder =>
                //             $query->whereBetween('created_at', [$startDate->subDays(3), $endDate])
                //         )
                //     ),
                DatePicker::make('start_date')
                    ->placeholder('Start Date')
                    ->autofocus(),
                DatePicker::make('end_date')
                    ->placeholder('End Date')
                    ->autofocus(),
                TextInput::make('search')
                    ->placeholder('Search')
                    ->autofocus(),
                Select::make('status')
                    ->placeholder('Status')
                    ->options([
                        'PENDING' => 'Pending',
                        'CONFIRMED' => 'Confirmed',
                        'CANCELLED' => 'Cancelled',
                        'REJECTED' => 'Rejected',
                    ]),
            ]);
    }

    public function appointmentSummary(?array $filters = null)
    {
        $query = Appointment::query()
            ->with(['patient', 'doctor'])  // Load patient and doctor relationships
            ->select(['id', 'patient_id', 'doctor_id', 'status', 'date', 'time_id'])  // Select relevant columns
            ->orderBy('id', 'asc');

        // Apply filters if provided
        if (!empty($filters)) {
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['doctor_name'])) {
                $query->whereHas('doctor', function ($q) use ($filters) {
                    $q->where('first_name', 'like', '%' . $filters['doctor_name'] . '%')
                        ->orWhere('last_name', 'like', '%' . $filters['doctor_name'] . '%');
                });
            }

            // Apply search filter for patient or doctor names
            if (isset($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->whereHas('patient', function ($query) use ($filters) {
                        $query->where('first_name', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('last_name', 'like', '%' . $filters['search'] . '%');
                    })->orWhereHas('doctor', function ($query) use ($filters) {
                        $query->where('first_name', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('last_name', 'like', '%' . $filters['search'] . '%');
                    });
                });
            }

            if (isset($filters['start_date']) && isset($filters['end_date'])) {
                $query->whereBetween('date', [$filters['start_date'], $filters['end_date']]);
            }
        }

        // Get appointment data and format with patient and doctor full names
        return $query->get()->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'patient' => $appointment->patient->fullname,
                'doctor' => $appointment->doctor->fullname,
                'date' => $appointment->date,
                'time' => Carbon::parse($appointment->time->time_start)->format('h:i A'),
                'status' => $appointment->status,
            ];
        });
    }
}
