<x-mail::message>
# Relatório Semanal de Estatísticas

**Período:** {{ date('d/m/Y', strtotime($startDate)) }} a {{ date('d/m/Y', strtotime($endDate)) }}

---

## Resumo do Website Principal

| Métrica | Valor |
|:--------|------:|
| Visualizações de página | **{{ number_format($siteStats['pageviews']['value'] ?? 0) }}** @if($previousSiteStats) ({{ ($change = round((($siteStats['pageviews']['value'] ?? 0) - ($previousSiteStats['pageviews']['value'] ?? 0)) / max($previousSiteStats['pageviews']['value'] ?? 1, 1) * 100)) >= 0 ? '+' : '' }}{{ $change }}%) @endif |
| Visitantes únicos | **{{ number_format($siteStats['visitors']['value'] ?? 0) }}** @if($previousSiteStats) ({{ ($change = round((($siteStats['visitors']['value'] ?? 0) - ($previousSiteStats['visitors']['value'] ?? 0)) / max($previousSiteStats['visitors']['value'] ?? 1, 1) * 100)) >= 0 ? '+' : '' }}{{ $change }}%) @endif |
| Taxa de rejeição | **{{ $siteStats['bounces']['value'] ?? 0 }}** |
| Tempo médio (seg) | **{{ round(($siteStats['totaltime']['value'] ?? 0) / max($siteStats['visitors']['value'] ?? 1, 1)) }}** |

@if(!empty($storeStats['pageviews']['value']))
## Loja Online

| Métrica | Valor |
|:--------|------:|
| Visualizações | **{{ number_format($storeStats['pageviews']['value'] ?? 0) }}** |
| Visitantes únicos | **{{ number_format($storeStats['visitors']['value'] ?? 0) }}** |
@endif

## Top 10 Páginas Mais Visitadas

| Página | Visitas |
|:-------|-------:|
@foreach($topPages as $page)
| {{ $page['label'] }} | {{ number_format($page['value'] ?? 0) }} |
@endforeach

@if(!empty($topReferrers))
## Origens de Tráfego

| Origem | Visitas |
|:-------|-------:|
@foreach($topReferrers as $referrer)
| {{ $referrer['x'] ?: 'Direto' }} | {{ number_format($referrer['y'] ?? 0) }} |
@endforeach
@endif

@if(!empty($languages))
## Distribuição por Idioma

| Idioma | Visitas |
|:-------|-------:|
@foreach($languages as $lang)
| {{ strtoupper($lang['x'] ?? '?') }} | {{ number_format($lang['y'] ?? 0) }} |
@endforeach
@endif

---

@if($dashboardUrl)
Para mais detalhes, consulte o [dashboard de analytics]({{ $dashboardUrl }}).
@endif

*Este relatório é gerado automaticamente todas as segundas-feiras.*

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
