<?php

namespace App\Filament\Resources\MemberAddresses;

use App\Filament\Resources\MemberAddresses\Pages\CreateMemberAddress;
use App\Filament\Resources\MemberAddresses\Pages\EditMemberAddress;
use App\Filament\Resources\MemberAddresses\Pages\ListMemberAddresses;
use App\Filament\Resources\MemberAddresses\Schemas\MemberAddressForm;
use App\Filament\Resources\MemberAddresses\Tables\MemberAddressesTable;
use App\Models\MemberAddress;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberAddressResource extends Resource
{
    protected static ?string $model = MemberAddress::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|\UnitEnum|null $navigationGroup = 'Customers';

    public static function form(Schema $schema): Schema
    {
        return MemberAddressForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MemberAddressesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMemberAddresses::route('/'),
            'create' => CreateMemberAddress::route('/create'),
            'edit' => EditMemberAddress::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
