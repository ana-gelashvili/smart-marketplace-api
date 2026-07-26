<?php

namespace App\Filament\Resources\MemberAddresses\Pages;

use App\Filament\Resources\MemberAddresses\MemberAddressResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMemberAddresses extends ListRecords
{
    protected static string $resource = MemberAddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
