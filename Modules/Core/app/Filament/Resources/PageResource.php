<?php

declare(strict_types=1);

namespace Modules\Core\App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Core\App\Enums\PageStatus;
use Modules\Core\App\Filament\Resources\PageResource\Pages;
use Modules\Core\App\Models\Page;

final class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Page')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Content')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Page Title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('slug')
                                ->label('URL Slug')
                                ->required()
                                ->unique(Page::class, 'slug', ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText('Leave empty to auto-generate from title'),

                            Forms\Components\Select::make('status')
                                ->options(collect(PageStatus::cases())->mapWithKeys(fn (PageStatus $s) => [$s->value => $s->label()]))
                                ->default(PageStatus::Draft->value)
                                ->required(),

                            Forms\Components\Toggle::make('is_home')
                                ->label('Set as Homepage')
                                ->default(false),

                            Forms\Components\Builder::make('blocks')
                                ->label('Page Content Blocks')
                                ->columnSpanFull()
                                ->blocks(self::getContentBlocks())
                                ->collapsible()
                                ->reorderable(),
                        ])
                        ->columns(2),

                    Forms\Components\Tabs\Tab::make('SEO')
                        ->schema([
                            Forms\Components\TextInput::make('meta_title')
                                ->label('Meta Title')
                                ->maxLength(70)
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->maxLength(160)
                                ->rows(3)
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('og_title')
                                ->label('Open Graph Title')
                                ->maxLength(255),

                            Forms\Components\Textarea::make('og_description')
                                ->label('Open Graph Description')
                                ->maxLength(300)
                                ->rows(3),

                            Forms\Components\FileUpload::make('og_image')
                                ->label('OG Image')
                                ->image()
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('schema_org')
                                ->label('Custom Schema.org JSON-LD')
                                ->rows(8)
                                ->helperText('Optional: override automatic schema generation')
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => PageStatus::Draft->value,
                        'success' => PageStatus::Published->value,
                        'warning' => PageStatus::Archived->value,
                    ]),

                Tables\Columns\IconColumn::make('is_home')
                    ->boolean()
                    ->label('Home'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(PageStatus::cases())->mapWithKeys(fn (PageStatus $s) => [$s->value => $s->label()])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    /** @return array<Block> */
    private static function getContentBlocks(): array
    {
        return [
            Block::make('hero')
                ->label('Hero Section')
                ->icon('heroicon-o-star')
                ->schema([
                    Forms\Components\TextInput::make('heading')->required(),
                    Forms\Components\Textarea::make('subheading'),
                    Forms\Components\TextInput::make('button_label')->label('CTA Button Text'),
                    Forms\Components\TextInput::make('button_url')->label('CTA URL')
                        ->placeholder('/contact or https://…'),
                    Forms\Components\TextInput::make('button2_label')->label('Secondary Button Text'),
                    Forms\Components\TextInput::make('button2_url')->label('Secondary Button URL')
                        ->placeholder('/about or https://…'),
                    Forms\Components\FileUpload::make('background_image')->image(),
                    Forms\Components\Select::make('style')
                        ->options(['default' => 'Default', 'centered' => 'Centered', 'split' => 'Split'])
                        ->default('default'),
                ]),

            Block::make('text')
                ->label('Rich Text')
                ->icon('heroicon-o-bars-3-bottom-left')
                ->schema([
                    Forms\Components\RichEditor::make('content')->required()->columnSpanFull(),
                    Forms\Components\Select::make('alignment')
                        ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                        ->default('left'),
                ]),

            Block::make('image')
                ->label('Image')
                ->icon('heroicon-o-photo')
                ->schema([
                    Forms\Components\FileUpload::make('image')->image()->required(),
                    Forms\Components\TextInput::make('caption'),
                    Forms\Components\TextInput::make('alt_text')->label('Alt Text'),
                    Forms\Components\Select::make('size')
                        ->options(['full' => 'Full Width', 'large' => 'Large', 'medium' => 'Medium'])
                        ->default('full'),
                ]),

            Block::make('gallery')
                ->label('Image Gallery')
                ->icon('heroicon-o-squares-2x2')
                ->schema([
                    Forms\Components\FileUpload::make('images')
                        ->image()
                        ->multiple()
                        ->required(),
                    Forms\Components\Select::make('columns')
                        ->options(['2' => '2 Columns', '3' => '3 Columns', '4' => '4 Columns'])
                        ->default('3'),
                ]),

            Block::make('video')
                ->label('Video Embed')
                ->icon('heroicon-o-play-circle')
                ->schema([
                    Forms\Components\TextInput::make('url')
                        ->label('Video URL (YouTube/Vimeo)')
                        ->url()
                        ->required(),
                    Forms\Components\TextInput::make('caption'),
                ]),

            Block::make('services_grid')
                ->label('Services Grid')
                ->icon('heroicon-o-squares-plus')
                ->schema([
                    Forms\Components\TextInput::make('heading'),
                    Forms\Components\Repeater::make('services')
                        ->schema([
                            Forms\Components\TextInput::make('title')->required(),
                            Forms\Components\Textarea::make('description'),
                            Forms\Components\TextInput::make('icon'),
                            Forms\Components\TextInput::make('url'),
                        ])
                        ->columns(2),
                    Forms\Components\Select::make('columns')
                        ->options(['2' => '2 Columns', '3' => '3 Columns', '4' => '4 Columns'])
                        ->default('3'),
                ]),

            Block::make('testimonials')
                ->label('Testimonials')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->schema([
                    Forms\Components\TextInput::make('heading'),
                    Forms\Components\Repeater::make('items')
                        ->schema([
                            Forms\Components\Textarea::make('quote')->required(),
                            Forms\Components\TextInput::make('author_name')->required(),
                            Forms\Components\TextInput::make('author_title'),
                            Forms\Components\FileUpload::make('author_photo')->image(),
                        ]),
                ]),

            Block::make('faq')
                ->label('FAQ Section')
                ->icon('heroicon-o-question-mark-circle')
                ->schema([
                    Forms\Components\TextInput::make('heading'),
                    Forms\Components\Repeater::make('items')
                        ->schema([
                            Forms\Components\TextInput::make('question')->required(),
                            Forms\Components\Textarea::make('answer')->required(),
                        ]),
                ]),

            Block::make('contact_form')
                ->label('Contact Form')
                ->icon('heroicon-o-envelope')
                ->schema([
                    Forms\Components\TextInput::make('heading'),
                    Forms\Components\TextInput::make('form_key')
                        ->default('contact')
                        ->required(),
                    Forms\Components\Toggle::make('show_map')->default(false),
                ]),

            Block::make('cta_banner')
                ->label('CTA Banner')
                ->icon('heroicon-o-megaphone')
                ->schema([
                    Forms\Components\TextInput::make('heading')->required(),
                    Forms\Components\Textarea::make('subheading'),
                    Forms\Components\TextInput::make('button_text'),
                    Forms\Components\TextInput::make('button_url')
                        ->placeholder('/contact or https://…'),
                    Forms\Components\ColorPicker::make('background_color')->default('#1a1a2e'),
                ]),
        ];
    }
}
