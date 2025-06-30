<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
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
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([

                    Section::make()->schema([
                        Group::make()->schema([
                            TextInput::make('title')->required()->rules('required|max:255')->maxLength(255),
                            ColorPicker::make('color')->rules('required|max:255'),
                            Select::make('category_id')->relationship('category', 'name')->rules('required|exists:categories,id')->required()->label('Category')->columnSpan('full'),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()->sortable()->toggleable(),
                ColorColumn::make('color')
                    ->searchable()->sortable()->toggleable(),
                ImageColumn::make('thumbnail')
                    ->searchable()->sortable()->toggleable(),
                IconColumn::make('published')
                    ->searchable()->sortable()->toggleable(),
                TextColumn::make('category.name')
                    ->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
