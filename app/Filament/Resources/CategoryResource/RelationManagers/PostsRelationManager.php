<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Section::make()->schema([
                        Group::make()->schema([
                            TextInput::make('title')->required()->rules('required|max:255')->maxLength(255),
                            ColorPicker::make('color')->rules('required|max:255'),
                            MarkdownEditor::make('content')->required()->rules('required')->columnSpan('full'),
                        ]),
                    ])->columnSpan(2),
                    Section::make()->schema([
                        FileUpload::make('thumbnail')->disk('public')->directory('thumbnails')->rules('image')->columnSpan('full'),
                        TagsInput::make('tags')->columnSpan('full'),
                        Select::make('users')->relationship('users', 'name')->rules('required|exists:users,id')->required()->label('Author')->multiple(),
                        Checkbox::make('published'),
                    ])->columnSpan(1),
                ])->columns(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable()->sortable()->toggleable(),
                ColorColumn::make('color')
                    ->searchable()->sortable()->toggleable(),
                ImageColumn::make('thumbnail')
                    ->searchable()->sortable()->toggleable(),
                IconColumn::make('published')
                    ->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}