<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Models\Tenant;
use App\MoonShine\Pages\Tenant\TenantIndexPage;
use App\MoonShine\Pages\Tenant\TenantFormPage;
use App\MoonShine\Pages\Tenant\TenantDetailPage;
use Illuminate\Support\Facades\Artisan;
use MoonShine\Enums\PageType;
use MoonShine\Handlers\ImportHandler;
use MoonShine\Pages\Crud\IndexPage;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Tenant>
 */
class TenantResource extends ModelResource
{
    protected string $model = Tenant::class;

    protected string $title = 'Tenants';

    protected ?PageType $redirectAfterSave = PageType::INDEX;
    protected ?PageType $redirectAfterDelete = PageType::INDEX;

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            TenantIndexPage::make($this->title()),
            TenantFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            TenantDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    public function getActiveActions(): array
    {
        return ['create', 'view', 'delete'];
    }

    public function import(): ?ImportHandler
    {
        return null;
    }

    protected function afterCreated(Model $item): Model
    {
        Artisan::call('tenants:seed --tenants=' . $item->id . ' --force');
        return $item;
    }
    
    /**
     * @param Tenant $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'id'  => ['required',Rule::unique('tenants','id'),'regex:/^[a-z\-]+$/'],
        ];
    }
}
