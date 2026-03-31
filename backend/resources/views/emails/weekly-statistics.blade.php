@php
    $fmt = fn($val) => number_format($val, 0, ',', '.');
    $pct = fn($curr, $prev) => $prev ? (int) round(($curr - $prev) / max($prev, 1) * 100) : ($curr > 0 ? 100 : null);

    $pageviews = $siteStats['pageviews']['value'] ?? 0;
    $visitors = $siteStats['visitors']['value'] ?? 0;
    $bounces = $siteStats['bounces']['value'] ?? 0;
    $totaltime = $siteStats['totaltime']['value'] ?? 0;
    $avgTime = $visitors > 0 ? round($totaltime / $visitors) : 0;
    $bounceRate = $pageviews > 0 ? round($bounces / $pageviews * 100) : 0;

    $prevPageviews = $previousSiteStats['pageviews']['value'] ?? 0;
    $prevVisitors = $previousSiteStats['visitors']['value'] ?? 0;
    $prevBounces = $previousSiteStats['bounces']['value'] ?? 0;
    $prevTotaltime = $previousSiteStats['totaltime']['value'] ?? 0;
    $prevAvgTime = $prevVisitors > 0 ? round($prevTotaltime / $prevVisitors) : 0;
    $prevBounceRate = $prevPageviews > 0 ? round($prevBounces / $prevPageviews * 100) : 0;

    $pctPageviews = $previousSiteStats ? $pct($pageviews, $prevPageviews) : null;
    $pctVisitors = $previousSiteStats ? $pct($visitors, $prevVisitors) : null;
    $pctBounce = $previousSiteStats ? $pct($bounceRate, $prevBounceRate) : null;
    $pctTime = $previousSiteStats ? $pct($avgTime, $prevAvgTime) : null;

    $storePageviews = $storeStats['pageviews']['value'] ?? 0;
    $storeVisitors = $storeStats['visitors']['value'] ?? 0;
    $prevStorePageviews = $previousStoreStats['pageviews']['value'] ?? 0;
    $prevStoreVisitors = $previousStoreStats['visitors']['value'] ?? 0;

    $fmtStart = date('d/m', strtotime($startDate));
    $fmtEnd = date('d/m/Y', strtotime($endDate));

    $maxReferrer = collect($topReferrers)->max('y') ?: 1;
    $maxBrowser = collect($browsers)->max('y') ?: 1;
    $maxDevice = collect($devices)->max('y') ?: 1;

    // Country names in Portuguese
    $countryNames = ['PT' => 'Portugal', 'BR' => 'Brasil', 'US' => 'EUA', 'GB' => 'Reino Unido', 'FR' => 'França', 'DE' => 'Alemanha', 'ES' => 'Espanha', 'IT' => 'Itália', 'NL' => 'Países Baixos', 'SG' => 'Singapura', 'CA' => 'Canadá', 'AU' => 'Austrália', 'CH' => 'Suíça', 'BE' => 'Bélgica', 'IE' => 'Irlanda', 'AT' => 'Áustria', 'SE' => 'Suécia', 'NO' => 'Noruega', 'DK' => 'Dinamarca', 'PL' => 'Polónia', 'CZ' => 'Chéquia', 'FI' => 'Finlândia', 'IN' => 'Índia', 'JP' => 'Japão', 'CN' => 'China', 'MX' => 'México', 'AR' => 'Argentina', 'CO' => 'Colômbia', 'CL' => 'Chile', 'AO' => 'Angola', 'MZ' => 'Moçambique', 'CV' => 'Cabo Verde', 'LU' => 'Luxemburgo'];

    // Peak hour — aggregate by hour-of-day (PT timezone = UTC+0/+1)
    $peakHour = null;
    if (!empty($hourlyTraffic['sessions'] ?? [])) {
        $hourBuckets = array_fill(0, 24, 0);
        foreach ($hourlyTraffic['sessions'] as $h) {
            $utcHour = (int) date('G', strtotime($h['x'] ?? ''));
            $ptHour = ($utcHour + 1) % 24; // WEST (summer time, UTC+1)
            $hourBuckets[$ptHour] += $h['y'] ?? 0;
        }
        // Find peak hour by sessions (real visitors, not pageviews which can be bots)
        $maxSessions = 0;
        for ($i = 0; $i < 24; $i++) {
            if ($hourBuckets[$i] > $maxSessions) {
                $maxSessions = $hourBuckets[$i];
                $peakHour = $i;
            }
        }
    }

    // Days of week in Portuguese
    $diasSemana = ['Mon' => 'Seg', 'Tue' => 'Ter', 'Wed' => 'Qua', 'Thu' => 'Qui', 'Fri' => 'Sex', 'Sat' => 'Sáb', 'Sun' => 'Dom'];
@endphp
<!DOCTYPE html>
<html lang="pt" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Relatório Semanal — {{ $fmtStart }} a {{ $fmtEnd }}</title>
    <!--[if mso]><noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript><![endif]-->
</head>
<body style="margin:0; padding:0; background-color:#f0f2f5; font-family:Arial, Helvetica, sans-serif; -webkit-font-smoothing:antialiased;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f0f2f5;">
        <tr><td align="center" style="padding:24px 8px;">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; max-width:600px; width:100%;">

                {{-- 1. HEADER --}}
                <tr><td style="padding:24px 24px 16px; text-align:center;">
                    @if($logoUrl)<img src="{{ $logoUrl }}" width="280" alt="Nazaré Qualifica" style="display:block; margin:0 auto; max-width:280px; height:auto;">@endif
                </td></tr>
                <tr><td style="height:4px; background-color:#2B5797; font-size:0; line-height:0;">&nbsp;</td></tr>

                {{-- 2. TITLE --}}
                <tr><td style="padding:28px 24px 8px;">
                    <h1 style="margin:0; font-size:24px; font-weight:bold; color:#2B5797; font-family:Arial, Helvetica, sans-serif;">Relatório Semanal</h1>
                    <p style="margin:6px 0 0; font-size:14px; color:#666666;">{{ $fmtStart }} a {{ $fmtEnd }}</p>
                </td></tr>
                <tr><td style="padding:0 24px;"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="height:2px; background-color:#E8833A; font-size:0; line-height:0;">&nbsp;</td></tr></table></td></tr>

                {{-- 3. KPI CARDS --}}
                <tr><td style="padding:20px 16px 8px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
                        @foreach([
                            ['val' => $pageviews, 'label' => 'Visualizações', 'pct' => $pctPageviews, 'color' => '#E8833A', 'up_good' => true],
                            ['val' => $visitors, 'label' => 'Visitantes', 'pct' => $pctVisitors, 'color' => '#2B5797', 'up_good' => true],
                            ['val' => $bounceRate . '%', 'label' => 'Tx. Rejeição', 'pct' => $pctBounce, 'color' => '#2B5797', 'up_good' => false],
                            ['val' => $avgTime . 's', 'label' => 'Tempo Médio', 'pct' => $pctTime, 'color' => '#2B5797', 'up_good' => true],
                        ] as $kpi)
                        <td width="25%" valign="top" style="padding:4px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#E8F0FE;">
                                <tr><td style="padding:14px 8px; text-align:center;">
                                    <div style="font-size:26px; font-weight:bold; color:{{ $kpi['color'] }}; line-height:1.1;">{{ is_numeric($kpi['val']) ? $fmt($kpi['val']) : $kpi['val'] }}</div>
                                    <div style="font-size:11px; color:#666666; margin-top:4px;">{{ $kpi['label'] }}</div>
                                    @if($kpi['pct'] !== null && $kpi['pct'] !== 0)
                                        @php $good = $kpi['up_good'] ? $kpi['pct'] > 0 : $kpi['pct'] < 0; @endphp
                                        <div style="font-size:11px; color:{{ $good ? '#28a745' : '#dc3545' }}; margin-top:3px;">
                                            {{ $kpi['pct'] > 0 ? '▲ +' : '▼ ' }}{{ $kpi['pct'] }}%
                                        </div>
                                    @endif
                                </td></tr>
                            </table>
                        </td>
                        @endforeach
                    </tr></table>
                </td></tr>

                {{-- 3b. YTD --}}
                @php
                    $ytdPageviews = $ytdSiteStats['pageviews']['value'] ?? 0;
                    $ytdVisitors = $ytdSiteStats['visitors']['value'] ?? 0;
                    $prevYtdVisitors = $prevYtdSiteStats['pageviews']['value'] ?? 0;
                    $ytdStorePv = $ytdStoreStats['pageviews']['value'] ?? 0;
                    $ytdStoreVis = $ytdStoreStats['visitors']['value'] ?? 0;
                    $currentYear = date('Y');
                    $prevYear = $currentYear - 1;
                @endphp
                @if($ytdPageviews > 0)
                <tr><td style="padding:16px 24px 8px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#E8F0FE; border-left:4px solid #2B5797;">
                        <tr><td style="padding:16px;">
                            <h3 style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#2B5797;">Acumulado {{ $currentYear }} <span style="font-weight:normal; font-size:12px; color:#666;">(1 Jan — {{ date('d/m', strtotime($endDate)) }})</span></h3>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr><td style="font-size:13px; color:#333; padding:3px 0;"><strong>Site:</strong> {{ $fmt($ytdVisitors) }} visitantes · {{ $fmt($ytdPageviews) }} visualizações
                                    @if($prevYtdVisitors > 0)
                                        @php $pv = $pct($ytdVisitors, $prevYtdSiteStats['visitors']['value'] ?? 0); @endphp
                                        @if($pv !== null)<span style="font-size:11px; color:{{ $pv >= 0 ? '#28a745' : '#dc3545' }};">vs {{ $prevYear }}: {{ $pv >= 0 ? '▲ +' : '▼ ' }}{{ $pv }}%</span>@endif
                                    @else
                                        <span style="font-size:11px; color:#999;">s/ dados {{ $prevYear }}</span>
                                    @endif
                                </td></tr>
                                @if($ytdStoreVis > 0)
                                <tr><td style="font-size:13px; color:#333; padding:3px 0;"><strong>Loja:</strong> {{ $fmt($ytdStoreVis) }} visitantes · {{ $fmt($ytdStorePv) }} visualizações</td></tr>
                                @endif
                            </table>
                        </td></tr>
                    </table>
                </td></tr>
                @endif

                {{-- 4. TRÁFEGO DIÁRIO --}}
                @if(!empty($dailyTraffic['pageviews'] ?? []))
                <tr><td style="padding:24px 24px 8px;">
                    <h2 style="margin:0 0 4px; font-size:18px; font-weight:bold; color:#2B5797; border-bottom:2px solid #E8833A; padding-bottom:8px;">Tráfego Diário</h2>
                </td></tr>
                <tr><td style="padding:0 24px 16px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="background:#2B5797; color:#fff; padding:8px 12px; font-size:13px; font-weight:bold;">Dia</td>
                            <td style="background:#2B5797; color:#fff; padding:8px 12px; font-size:13px; font-weight:bold; text-align:right;">Visitantes</td>
                            <td style="background:#2B5797; color:#fff; padding:8px 12px; font-size:13px; font-weight:bold; text-align:right;">Páginas</td>
                        </tr>
                        @foreach($dailyTraffic['pageviews'] as $i => $day)
                        @php
                            $dayDate = $day['x'] ?? '';
                            $dayPv = $day['y'] ?? 0;
                            $daySessions = $dailyTraffic['sessions'][$i]['y'] ?? 0;
                            $dayLabel = $diasSemana[date('D', strtotime($dayDate))] ?? '';
                            $dayFmt = date('d/m', strtotime($dayDate));
                        @endphp
                        <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#F5F7FA' }};">
                            <td style="padding:6px 12px; font-size:13px; color:#333; border-bottom:1px solid #eee;">{{ $dayLabel }}, {{ $dayFmt }}</td>
                            <td style="padding:6px 12px; font-size:13px; color:#333; font-weight:bold; text-align:right; border-bottom:1px solid #eee;">{{ $fmt($daySessions) }}</td>
                            <td style="padding:6px 12px; font-size:13px; color:#333; font-weight:bold; text-align:right; border-bottom:1px solid #eee;">{{ $fmt($dayPv) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td></tr>
                @endif

                {{-- 5. TOP PÁGINAS --}}
                @if(!empty($topPages))
                <tr><td style="padding:16px 24px 8px;">
                    <h2 style="margin:0 0 4px; font-size:18px; font-weight:bold; color:#2B5797; border-bottom:2px solid #E8833A; padding-bottom:8px;">Top Páginas Mais Visitadas</h2>
                </td></tr>
                <tr><td style="padding:0 24px 16px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="background:#2B5797; color:#fff; padding:10px 12px; font-size:13px; font-weight:bold;">#</td>
                            <td style="background:#2B5797; color:#fff; padding:10px 12px; font-size:13px; font-weight:bold;">Página</td>
                            <td style="background:#2B5797; color:#fff; padding:10px 12px; font-size:13px; font-weight:bold; text-align:right;">Visitas</td>
                        </tr>
                        @foreach($topPages as $i => $page)
                        <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#F5F7FA' }};">
                            <td style="padding:8px 12px; font-size:13px; color:#999; border-bottom:1px solid #eee; width:30px;">{{ $i + 1 }}</td>
                            <td style="padding:8px 12px; font-size:13px; color:#333; border-bottom:1px solid #eee;">{{ $page['label'] }}</td>
                            <td style="padding:8px 12px; font-size:13px; color:#333; font-weight:bold; text-align:right; border-bottom:1px solid #eee;">{{ $fmt($page['value']) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td></tr>
                @endif

                {{-- 5b. ENTIDADES --}}
                @if(!empty($entities))
                <tr><td style="padding:8px 24px 4px;">
                    <h3 style="margin:0; font-size:15px; font-weight:bold; color:#2B5797;">Distribuição por Entidade</h3>
                </td></tr>
                <tr><td style="padding:8px 24px 16px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                        @php $maxEntity = collect($entities)->max('views') ?: 1; @endphp
                        @foreach($entities as $entity)
                        <tr>
                            <td style="padding:6px 12px; font-size:13px; color:#333; width:40%;">{{ $entity['name'] }}</td>
                            <td style="padding:6px 4px; width:40%;">
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
                                    <td style="background:{{ $entity['name'] === 'Praia do Norte' ? '#0066cc' : ($entity['name'] === 'Carsurf' ? '#00cc66' : '#ffa500') }}; height:16px; width:{{ round($entity['views'] / $maxEntity * 100) }}%; font-size:0;">&nbsp;</td>
                                    <td style="height:16px; font-size:0;">&nbsp;</td>
                                </tr></table>
                            </td>
                            <td style="padding:6px 12px; font-size:13px; color:#333; font-weight:bold; text-align:right; width:20%;">{{ $fmt($entity['views']) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td></tr>
                @endif

                {{-- 6. ORIGENS DE TRÁFEGO --}}
                @if(!empty($topReferrers))
                <tr><td style="padding:16px 24px 8px;">
                    <h2 style="margin:0 0 4px; font-size:18px; font-weight:bold; color:#2B5797; border-bottom:2px solid #E8833A; padding-bottom:8px;">Origens de Tráfego</h2>
                </td></tr>
                <tr><td style="padding:0 24px 16px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="background:#2B5797; color:#fff; padding:10px 12px; font-size:13px; font-weight:bold;">Origem</td>
                            <td style="background:#2B5797; color:#fff; padding:10px 12px; font-size:13px; font-weight:bold; width:40%;"></td>
                            <td style="background:#2B5797; color:#fff; padding:10px 12px; font-size:13px; font-weight:bold; text-align:right;">Visitas</td>
                        </tr>
                        @foreach($topReferrers as $i => $ref)
                        <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#F5F7FA' }};">
                            <td style="padding:8px 12px; font-size:13px; color:#333; border-bottom:1px solid #eee;">{{ $ref['x'] ?: 'Acesso direto' }}</td>
                            <td style="padding:8px 4px; border-bottom:1px solid #eee;">
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
                                    <td style="background:#4472C4; height:12px; width:{{ round(($ref['y'] ?? 0) / $maxReferrer * 100) }}%; font-size:0;">&nbsp;</td>
                                    <td style="height:12px; font-size:0;">&nbsp;</td>
                                </tr></table>
                            </td>
                            <td style="padding:8px 12px; font-size:13px; color:#333; font-weight:bold; text-align:right; border-bottom:1px solid #eee;">{{ $fmt($ref['y'] ?? 0) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td></tr>
                @endif

                {{-- 7. LOJA ONLINE --}}
                @if($storePageviews > 0)
                <tr><td style="padding:16px 24px 8px;">
                    <h2 style="margin:0 0 4px; font-size:18px; font-weight:bold; color:#2B5797; border-bottom:2px solid #E8833A; padding-bottom:8px;">Loja Online</h2>
                </td></tr>
                <tr><td style="padding:8px 16px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
                        <td width="50%" valign="top" style="padding:4px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#E8F0FE;">
                                <tr><td style="padding:12px 8px; text-align:center;">
                                    <div style="font-size:24px; font-weight:bold; color:#2B5797;">{{ $fmt($storePageviews) }}</div>
                                    <div style="font-size:11px; color:#666; margin-top:4px;">Visualizações</div>
                                    @if($previousStoreStats && $prevStorePageviews > 0)
                                        @php $pctSP = $pct($storePageviews, $prevStorePageviews); @endphp
                                        @if($pctSP !== null)<div style="font-size:11px; color:{{ $pctSP >= 0 ? '#28a745' : '#dc3545' }}; margin-top:3px;">{{ $pctSP >= 0 ? '▲ +' : '▼ ' }}{{ $pctSP }}%</div>@endif
                                    @endif
                                </td></tr>
                            </table>
                        </td>
                        <td width="50%" valign="top" style="padding:4px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#E8F0FE;">
                                <tr><td style="padding:12px 8px; text-align:center;">
                                    <div style="font-size:24px; font-weight:bold; color:#2B5797;">{{ $fmt($storeVisitors) }}</div>
                                    <div style="font-size:11px; color:#666; margin-top:4px;">Visitantes</div>
                                    @if($previousStoreStats && $prevStoreVisitors > 0)
                                        @php $pctSV = $pct($storeVisitors, $prevStoreVisitors); @endphp
                                        @if($pctSV !== null)<div style="font-size:11px; color:{{ $pctSV >= 0 ? '#28a745' : '#dc3545' }}; margin-top:3px;">{{ $pctSV >= 0 ? '▲ +' : '▼ ' }}{{ $pctSV }}%</div>@endif
                                    @endif
                                </td></tr>
                            </table>
                        </td>
                    </tr></table>
                </td></tr>
                @if(!empty($storeTopPages))
                <tr><td style="padding:8px 24px 16px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="background:#2B5797; color:#fff; padding:10px 12px; font-size:13px; font-weight:bold;">Produto / Página</td>
                            <td style="background:#2B5797; color:#fff; padding:10px 12px; font-size:13px; font-weight:bold; text-align:right;">Visitas</td>
                        </tr>
                        @foreach($storeTopPages as $i => $page)
                        <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#F5F7FA' }};">
                            <td style="padding:8px 12px; font-size:13px; color:#333; border-bottom:1px solid #eee;">{{ $page['label'] }}</td>
                            <td style="padding:8px 12px; font-size:13px; color:#333; font-weight:bold; text-align:right; border-bottom:1px solid #eee;">{{ $fmt($page['value']) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td></tr>
                @endif
                @if(!empty($storeReferrers))
                <tr><td style="padding:0 24px 16px;">
                    <h3 style="margin:0 0 8px; font-size:14px; color:#666;">Origens de tráfego da loja</h3>
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                        @foreach($storeReferrers as $i => $ref)
                        <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#F5F7FA' }};">
                            <td style="padding:6px 12px; font-size:13px; color:#333; border-bottom:1px solid #eee;">{{ $ref['x'] ?: 'Acesso direto' }}</td>
                            <td style="padding:6px 12px; font-size:13px; color:#333; font-weight:bold; text-align:right; border-bottom:1px solid #eee;">{{ $fmt($ref['y'] ?? 0) }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td></tr>
                @endif
                @endif

                {{-- 8. PERFIL DOS VISITANTES --}}
                @if(!empty($devices) || !empty($browsers))
                <tr><td style="padding:16px 24px 8px;">
                    <h2 style="margin:0 0 4px; font-size:18px; font-weight:bold; color:#2B5797; border-bottom:2px solid #E8833A; padding-bottom:8px;">Perfil dos Visitantes</h2>
                </td></tr>
                <tr><td style="padding:8px 16px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
                        @if(!empty($devices))
                        <td width="50%" valign="top" style="padding:4px 8px;">
                            <h4 style="margin:0 0 8px; font-size:13px; font-weight:bold; color:#666;">Dispositivos</h4>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                @foreach($devices as $device)
                                @php $deviceLabel = match(strtolower($device['x'] ?? '')) {
                                    'desktop' => 'Computador', 'mobile' => 'Telemóvel', 'tablet' => 'Tablet', 'laptop' => 'Portátil', default => $device['x'] ?? 'Outro',
                                }; @endphp
                                <tr>
                                    <td style="padding:4px 0; font-size:12px; color:#333; width:35%;">{{ $deviceLabel }}</td>
                                    <td style="padding:4px 4px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
                                            <td style="background:#2B5797; height:14px; width:{{ round(($device['y'] ?? 0) / $maxDevice * 100) }}%; font-size:0;">&nbsp;</td>
                                            <td style="height:14px; font-size:0;">&nbsp;</td>
                                        </tr></table>
                                    </td>
                                    <td style="padding:4px 0; font-size:12px; color:#333; font-weight:bold; text-align:right; width:15%;">{{ $fmt($device['y'] ?? 0) }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                        @endif
                        @if(!empty($browsers))
                        <td width="50%" valign="top" style="padding:4px 8px;">
                            <h4 style="margin:0 0 8px; font-size:13px; font-weight:bold; color:#666;">Browsers</h4>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                @foreach($browsers as $browser)
                                <tr>
                                    <td style="padding:4px 0; font-size:12px; color:#333; width:35%;">{{ $browser['x'] ?? '?' }}</td>
                                    <td style="padding:4px 4px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
                                            <td style="background:#4472C4; height:14px; width:{{ round(($browser['y'] ?? 0) / $maxBrowser * 100) }}%; font-size:0;">&nbsp;</td>
                                            <td style="height:14px; font-size:0;">&nbsp;</td>
                                        </tr></table>
                                    </td>
                                    <td style="padding:4px 0; font-size:12px; color:#333; font-weight:bold; text-align:right; width:15%;">{{ $fmt($browser['y'] ?? 0) }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                        @endif
                    </tr></table>
                </td></tr>
                @endif

                {{-- Países --}}
                @if(!empty($countries))
                <tr><td style="padding:8px 24px 8px;">
                    <h4 style="margin:0 0 8px; font-size:13px; font-weight:bold; color:#666;">Países</h4>
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0"><tr>
                        @foreach($countries as $c)
                        <td style="padding:4px 14px 4px 0; font-size:12px; color:#333;">
                            <strong>{{ $countryNames[$c['x']] ?? $c['x'] }}</strong>
                            <span style="color:#666;">{{ $fmt($c['y'] ?? 0) }}</span>
                        </td>
                        @endforeach
                    </tr></table>
                </td></tr>
                @endif

                {{-- Idiomas --}}
                @if(!empty($languages))
                <tr><td style="padding:4px 24px 8px;">
                    <h4 style="margin:0 0 8px; font-size:13px; font-weight:bold; color:#666;">Idiomas</h4>
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0"><tr>
                        @foreach($languages as $lang)
                        <td style="padding:4px 12px 4px 0; font-size:12px; color:#333;">
                            <strong>{{ strtoupper($lang['x'] ?? '?') }}</strong> <span style="color:#666;">{{ $fmt($lang['y'] ?? 0) }}</span>
                        </td>
                        @endforeach
                    </tr></table>
                </td></tr>
                @endif

                {{-- OS + Hora de pico --}}
                @if(!empty($operatingSystems))
                <tr><td style="padding:4px 24px 8px;">
                    <h4 style="margin:0 0 8px; font-size:13px; font-weight:bold; color:#666;">Sistemas Operativos</h4>
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0"><tr>
                        @foreach($operatingSystems as $os)
                        <td style="padding:4px 12px 4px 0; font-size:12px; color:#333;">
                            <strong>{{ $os['x'] ?? '?' }}</strong> <span style="color:#666;">{{ $fmt($os['y'] ?? 0) }}</span>
                        </td>
                        @endforeach
                    </tr></table>
                </td></tr>
                @endif


                {{-- 9. CALLOUT --}}
                @if($dashboardUrl)
                <tr><td style="padding:8px 24px 16px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#E8F0FE; border-left:4px solid #4472C4;">
                        <tr><td style="padding:14px 16px;">
                            <p style="margin:0; font-size:13px; color:#333;">Para mais detalhes e dados em tempo real, consulte o <a href="{{ $dashboardUrl }}" style="color:#2B5797; font-weight:bold; text-decoration:underline;">dashboard de analytics</a>.</p>
                        </td></tr>
                    </table>
                </td></tr>
                @endif

                {{-- 10. FOOTER --}}
                <tr><td style="height:2px; background:#2B5797; font-size:0; line-height:0;">&nbsp;</td></tr>
                <tr><td style="padding:16px 24px; background:#f8f9fa; text-align:center;">
                    <p style="margin:0; font-size:11px; color:#999;">Este relatório é gerado automaticamente todas as segundas-feiras.</p>
                    <p style="margin:8px 0 0; font-size:11px; color:#999;"><strong style="color:#666;">Nazaré Qualifica</strong> — Empresa Municipal</p>
                </td></tr>

            </table>
        </td></tr>
    </table>
</body>
</html>
