<?php

namespace App\Filament\Reports;

use App\Models\Patient;
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
use Illuminate\Support\Facades\Auth;

class PatientSummary extends Report
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
                                Text::make("Patient Summary Report")
                                    ->title()
                                    ->primary(),
                                // Text::make("Patient Summary Report")
                                //     ->subtitle(),
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
                        TextColumn::make("fullname"),
                        TextColumn::make("contact_no"),
                        TextColumn::make("gender"),
                        TextColumn::make("email"),
                        TextColumn::make("status"),
                        TextColumn::make("account_created"),
                    ])
                    ->data(
                        fn(?array $filters) => $this->patientSummary($filters)
                    ),
                VerticalSpace::make(),
            ]);
    }

    public function footer(Footer $footer): Footer
    {
        return $footer
            ->schema([
                // ...
            ]);
    }

    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('start_date')
                    ->placeholder('Account Created Start Date')
                    ->autofocus(),
                DatePicker::make('end_date')
                    ->placeholder('Account Created End Date')
                    ->autofocus(),
                TextInput::make('search')
                    ->placeholder('Search by Name or Email')
                    ->autofocus(),
                // Select::make('status')
                //     ->placeholder('Status')
                //     ->options([
                //         'ACTIVE' => 'Active',
                //         'INACTIVE' => 'Inactive',
                //         'SUSPENDED' => 'Suspended',
                //     ]),
            ]);
    }

    public function patientSummary(?array $filters = null)
    {
        $query = Patient::query()
            ->select(['id', 'first_name', 'last_name', 'contact_no', 'gender', 'email', 'created_at']) // Select relevant columns
            ->orderBy('id', 'asc');

        // Apply filters if provided
        if (!empty($filters)) {
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Apply search filter for patient names or email
            if (isset($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('first_name', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('last_name', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                });
            }

            if (isset($filters['start_date']) && isset($filters['end_date'])) {
                $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
            }
        }

        // Get patient data and format the response
        return $query->get()->map(function ($patient) {
            return [
                'id' => $patient->id,
                'fullname' => $patient->first_name . ' ' . $patient->last_name,
                'contact_no' => $patient->contact_no,
                'gender' => ucfirst($patient->gender),
                'email' => $patient->email,
                'status' => 'Active',
                'account_created' => $patient->created_at->format('Y-m-d h:i A'),
            ];
        });
    }

    // public static function canAccess(): bool
    // {
    //     return Auth::user()->isAdmin();
    // }

    // public static function shouldRegisterNavigation(): bool
    // {
    //     return Auth::user()->isAdmin();
    // }
}
