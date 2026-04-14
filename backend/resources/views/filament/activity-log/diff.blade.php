@php
    use App\Filament\Resources\Configuracoes\Support\ActivityLogFormatter as F;

    $record = $getRecord();

    $attributeChanges = $record->attribute_changes;
    if (is_object($attributeChanges) && method_exists($attributeChanges, 'toArray')) {
        $attributeChanges = $attributeChanges->toArray();
    }
    $attributeChanges = is_array($attributeChanges) ? $attributeChanges : [];

    $old = $attributeChanges['old'] ?? [];
    $new = $attributeChanges['attributes'] ?? [];

    $keys = array_values(array_unique(array_merge(array_keys($old), array_keys($new))));
    sort($keys);

    $properties = $record->properties;
    if (is_object($properties) && method_exists($properties, 'toArray')) {
        $properties = $properties->toArray();
    }
    $properties = is_array($properties) ? $properties : [];
@endphp

<div class="space-y-4">
    @if (count($keys) > 0)
        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <table class="w-full text-sm table-fixed">
                <colgroup>
                    <col class="w-1/4">
                    <col class="w-3/8">
                    <col class="w-3/8">
                </colgroup>
                <thead class="bg-gray-50 dark:bg-gray-800/60">
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                            Campo
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-red-700 dark:text-red-400">
                            Antes
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-green-700 dark:text-green-400">
                            Depois
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($keys as $key)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-4 py-3 align-top font-medium text-gray-900 dark:text-gray-100">
                                {{ F::fieldLabel($key) }}
                                <div class="text-[10px] font-normal text-gray-400 dark:text-gray-500 font-mono mt-0.5">
                                    {{ $key }}
                                </div>
                            </td>
                            <td class="px-4 py-3 align-top bg-red-50/40 dark:bg-red-950/10 text-red-900 dark:text-red-200 text-sm">
                                {!! F::formatValue($old[$key] ?? null) !!}
                            </td>
                            <td class="px-4 py-3 align-top bg-green-50/40 dark:bg-green-950/10 text-green-900 dark:text-green-200 text-sm">
                                {!! F::formatValue($new[$key] ?? null) !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if (! empty($properties))
        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800/60 border-b border-gray-200 dark:border-gray-700">
                <h4 class="text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                    Propriedades
                </h4>
            </div>
            <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($properties as $propKey => $propValue)
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 px-4 py-3 text-sm">
                        <dt class="font-medium text-gray-700 dark:text-gray-300 sm:col-span-1">
                            {{ F::fieldLabel((string) $propKey) }}
                        </dt>
                        <dd class="sm:col-span-3 text-gray-900 dark:text-gray-100 break-words">
                            {!! F::formatValue($propValue) !!}
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    @endif

    @if (count($keys) === 0 && empty($properties))
        <p class="text-sm italic text-gray-500 dark:text-gray-400">Sem detalhes para mostrar.</p>
    @endif
</div>
