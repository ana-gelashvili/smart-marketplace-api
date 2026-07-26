<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->formatStateUsing(function (Category $record): string {
                        $depth = 0;

                        for ($parent = $record->parent; $parent !== null; $parent = $parent->parent) {
                            $depth++;
                        }

                        return str_repeat('— ', $depth).$record->name;
                    })
                    ->searchable(),
                TextColumn::make('parent.name')
                    ->label('Parent category')
                    ->placeholder('— Top level —')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('children_count')
                    ->label('Subcategories')
                    ->counts('children')
                    ->numeric(),
                TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->numeric(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Parent category')
                    ->relationship('parent', 'name')
                    ->placeholder('All categories'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
