<?php

namespace App\Filament\Resources\ProductImages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                TextInput::make('cloudinary_url')
                    ->label('Image URL')
                    ->url()
                    ->required(),
                TextInput::make('public_id')
                    ->required(),
                Select::make('type')
                    ->options([
                        'primary' => 'Primary',
                        'gallery' => 'Gallery',
                    ]),
            ]);
    }
}
