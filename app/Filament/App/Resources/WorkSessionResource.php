<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\WorkSessionResource\Pages;
use App\Filament\App\Resources\WorkSessionResource\Widgets\WorkHourThisMonth;
use App\Models\WorkSession;
use Filament\Actions\Action as ModalAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\GlobalSearch\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WorkSessionResource extends Resource
{
    protected static ?string $model = WorkSession::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Work';
    protected static ?string $navigationLabel = 'Sessions';

    protected function getHeaderActions(): array
    {
        return [
            ModalAction::make('start-new-session')
                ->modalContent(view('filament.app.actions.start-new-session'))
                ->modalActions([
                    Action::make('start')
                        ->button()
                        ->size('md')
                        ->label('Start')
                        ->emitTo('timer', 'timerStarted'),
                    Action::make('stop')
                        ->button()
                        ->size('md')
                        ->label('Stop')
                        ->color('secondary')
                        ->emitTo('timer', 'timerStopped'),
                    Action::make('discard')
                        ->button()
                        ->size('md')
                        ->label('Discard')
                        ->color('danger')
                        ->emitTo('timer', 'discardSession'),
                ])
                ->modalWidth('xl')
                ->after(function () {
                    $this->redirect('/dashboard');
                }),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->maxLength(26),
                Forms\Components\TextInput::make('team_id')
                    ->required()
                    ->maxLength(26),
                Forms\Components\TextInput::make('duration')
                    ->required(),
                Forms\Components\DateTimePicker::make('start')
                    ->required(),
                Forms\Components\DateTimePicker::make('end')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('task_id')
                    ->maxLength(26),
                Forms\Components\TextInput::make('project_id')
                    ->maxLength(26),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team.name'),
                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(fn ($state) => gmdate('H:i:s', $state)),
                Tables\Columns\TextColumn::make('start')
                    ->label('Date')
                    ->date()
                    ->sortable(true, fn (Builder $query) => $query->orderBy('start', 'desc')),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('task.title'),
                Tables\Columns\TextColumn::make('project.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListWorkSessions::route('/'),
            'create' => Pages\CreateWorkSession::route('/create'),
            'edit' => Pages\EditWorkSession::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            WorkHourThisMonth::class,
        ];
    }
}