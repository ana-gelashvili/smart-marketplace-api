<?php

namespace App\Filament\Resources\MemberAddresses\Pages;

use App\Filament\Resources\MemberAddresses\MemberAddressResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditMemberAddress extends EditRecord
{
    protected static string $resource = MemberAddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
