<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Billing';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(6)
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'id')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('summary')
                    ->maxLength(255)
                    ->columnSpan(4),
                Forms\Components\TextInput::make('subtotal_in_cents')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('vat_in_cents')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('total_in_cents')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\DatePicker::make('start')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\DatePicker::make('end')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\DatePicker::make('issued_on')
                    ->columnSpan(2),
                Forms\Components\DatePicker::make('due_on')
                    ->columnSpan(2),
                Forms\Components\DatePicker::make('paid_on')
                    ->columnSpan(2),
                Forms\Components\DatePicker::make('cancelled_on')
                    ->columnSpan(2),
                Forms\Components\DatePicker::make('reminded_on')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('team_id'),
                Tables\Columns\TextColumn::make('client_id'),
                Tables\Columns\TextColumn::make('summary'),
                Tables\Columns\TextColumn::make('total_in_cents'),
                Tables\Columns\TextColumn::make('subtotal_in_cents'),
                Tables\Columns\TextColumn::make('vat_in_cents'),
                Tables\Columns\TextColumn::make('start')
                    ->date(),
                Tables\Columns\TextColumn::make('end')
                    ->date(),
                Tables\Columns\TextColumn::make('issued_on')
                    ->date(),
                Tables\Columns\TextColumn::make('due_on')
                    ->date(),
                Tables\Columns\TextColumn::make('paid_on')
                    ->date(),
                Tables\Columns\TextColumn::make('cancelled_on')
                    ->date(),
                Tables\Columns\TextColumn::make('reminded_on')
                    ->date(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
