<?php

namespace App\Filament\Resources\MemberAddresses\Pages;

use App\Filament\Resources\MemberAddresses\MemberAddressResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMemberAddress extends CreateRecord
{
    protected static string $resource = MemberAddressResource::class;
}
