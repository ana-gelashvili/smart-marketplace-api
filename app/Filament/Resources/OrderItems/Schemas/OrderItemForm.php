<?php

namespace App\Filament\Resources\OrderItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'uuid')
                    ->label('Order')
                    ->required(),
                Select::make('product_id')
                    ->relationship('product', 'name'),
                TextInput::make('product_name')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
            ]);
    }
}
