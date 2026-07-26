<?php

namespace App\Filament\Resources\MemberAddresses\Schemas;

use App\Enums\AddressType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MemberAddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('member_id')
                    ->relationship('member', 'name')
                    ->required(),
                Select::make('type')
                    ->options(AddressType::class)
                    ->required(),
                TextInput::make('label'),
                TextInput::make('recipient_name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('address_line_1')
                    ->required(),
                TextInput::make('address_line_2'),
                TextInput::make('city')
                    ->required(),
                TextInput::make('state'),
                TextInput::make('postal_code')
                    ->required(),
                TextInput::make('country')
                    ->required(),
                Toggle::make('is_default')
                    ->required(),
            ]);
    }
}
