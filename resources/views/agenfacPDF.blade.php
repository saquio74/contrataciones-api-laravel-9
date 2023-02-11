<!DOCTYPE html>
<html>

<head>
    <title>Laravel 9 Generate PDF From View</title>
    <link href="css/app.css" type="text/css" rel="stylesheet" />
</head>

<body>
    <header>
        <div class="header">
            <img src="img/logoMalvinas.png" alt="" srcset="" />
            <h1 class="title">

                Municipalidad de Malvinas Argentinas
            </h1>
        </div>
    </header>
    <div class="body">
        <h1>Liquidacion mensual</h1>
        <h1>
            M.T
            {{ "{$getLiquidados->first()->periodo} {$getLiquidados->first()->anio}" }}
        </h1>
        <h2>{{ "{$getLiquidados->first()->hospitalInfo->hospital}" }}</h2>

        <div class="liquidacion">
            @foreach ($getLiquidados as $liquidacion)
                @if ($loop->first)
                    <h2>
                        {{ "{$liquidacion->servicio}" }}
                    </h2>
                    <h2>
                        {{ "{$liquidacion->sector}" }}
                    </h2>
                    <table>
                        <tr>
                            <td>Legajo</td>
                            <td>Inciso</td>
                            <td>Apellido y nombre</td>
                            <td>Tot. Hs.</td>
                            <td>Importe</td>
                            <td>%B.</td>
                            <td>Bon.</td>
                            <td>Total</td>
                        </tr>
                @endif
                @if (!$loop->first && $liquidacion->servicio != $getLiquidados[$loop->index - 1]->servicio)
                    <h2>
                        {{ "{$liquidacion->servicio}" }}
                    </h2>
                @endif
                @if (!$loop->first && $liquidacion->sector != $getLiquidados[$loop->index - 1]->sector)
                    <h2>
                        {{ "{$liquidacion->sector}" }}
                    </h2>
                    <table>
                        <tr>
                            <td>Legajo</td>
                            <td>Inciso</td>
                            <td>Apellido y nombre</td>
                            <td>Tot. Hs.</td>
                            <td>Importe</td>
                            <td>%B.</td>
                            <td>Bon.</td>
                            <td>Total</td>
                        </tr>
                @endif
                <tr>
                    <td>

                        {{ $liquidacion->legajo }}
                    </td>
                    <td>

                        {{ $liquidacion->inciso }}
                    </td>
                    <td>

                        {{ strtoupper($liquidacion->nombre) }}
                    </td>
                    <td>
                        {{ $liquidacion->horas }}
                    </td>
                    <td class="alinear-derecha">
                        ${{ number_format($liquidacion->subtot, 2, ',', '.') }}
                    </td>
                    <td>
                        {{ $liquidacion->bonificacion }}
                    </td>
                    <td class="alinear-derecha">
                        ${{ number_format($liquidacion->bonvalor, 2, ',', '.') }}
                    </td>
                    <td class="alinear-derecha">
                        ${{ number_format($liquidacion->total, 2, ',', '.') }}
                    </td>

                </tr>
                @if (
                    !$loop->first &&
                        $liquidacion->sector !=
                            $getLiquidados[$loop->index + 1 >= $loop->count ? $loop->count - 1 : $loop->index + 1]->sector)
                    </table>
                    </table>
                    <h3>Sector</h3>
                    Cantidad de agentes: {{ $getLiquidados->where('sector', $liquidacion->sector)->count() }}
                    <br>
                    Subtotal del sector:
                    $
                    {{ number_format($getLiquidados->where('sector', $liquidacion->sector)->reduce(fn($v1, $v2) => $v1 + $v2->subtot), 2, ',', '.') }}
                    <br>
                    Bonificacion total del sector:
                    $
                    {{ number_format($getLiquidados->where('sector', $liquidacion->sector)->reduce(fn($v1, $v2) => $v1 + $v2->bonvalor), 2, ',', '.') }}
                    <br>
                    Total del sector:
                    $
                    {{ number_format($getLiquidados->where('sector', $liquidacion->sector)->reduce(fn($v1, $v2) => $v1 + $v2->total), 2, ',', '.') }}
                    <br>
                    <br>
                @endif
                @if (
                    !$loop->first &&
                        $liquidacion->servicio !=
                            $getLiquidados[$loop->index + 1 >= $loop->count ? $loop->count - 1 : $loop->index + 1]->servicio)
                    </table>
                    </table>
                    <h3>Servicio</h3>
                    Cantidad de agentes: {{ $getLiquidados->where('servicio', $liquidacion->servicio)->count() }}
                    <br>
                    Subtotal del servicio:
                    $
                    {{ number_format($getLiquidados->where('servicio', $liquidacion->servicio)->reduce(fn($v1, $v2) => $v1 + $v2->subtot), 2, ',', '.') }}
                    <br>
                    Bonificacion total del servicio:
                    $
                    {{ number_format($getLiquidados->where('servicio', $liquidacion->servicio)->reduce(fn($v1, $v2) => $v1 + $v2->bonvalor), 2, ',', '.') }}
                    <br>
                    Total del servicio:
                    $
                    {{ number_format($getLiquidados->where('servicio', $liquidacion->servicio)->reduce(fn($v1, $v2) => $v1 + $v2->total), 2, ',', '.') }}
                    <br>
                    <br>
                @endif
                @if ($loop->last)
                    </table>
                    <h3>Sector</h3>
                    Cantidad de agentes: {{ $getLiquidados->where('sector', $liquidacion->sector)->count() }}
                    <br>
                    Subtotal del sector:
                    $
                    {{ number_format($getLiquidados->where('sector', $liquidacion->sector)->reduce(fn($v1, $v2) => $v1 + $v2->subtot), 2, ',', '.') }}
                    <br>
                    Bonificacion total del sector:
                    $
                    {{ number_format($getLiquidados->where('sector', $liquidacion->sector)->reduce(fn($v1, $v2) => $v1 + $v2->bonvalor), 2, ',', '.') }}
                    <br>
                    Total del sector:
                    $
                    {{ number_format($getLiquidados->where('sector', $liquidacion->sector)->reduce(fn($v1, $v2) => $v1 + $v2->total), 2, ',', '.') }}
                    <br>
                    <br>
                    <br>
                    <h3>Servicio</h3>
                    Cantidad de agentes: {{ $getLiquidados->where('servicio', $liquidacion->servicio)->count() }}
                    <br>
                    Subtotal del servicio:
                    $
                    {{ number_format($getLiquidados->where('servicio', $liquidacion->servicio)->reduce(fn($v1, $v2) => $v1 + $v2->subtot), 2, ',', '.') }}
                    <br>
                    Bonificacion total del servicio:
                    $
                    {{ number_format($getLiquidados->where('servicio', $liquidacion->servicio)->reduce(fn($v1, $v2) => $v1 + $v2->bonvalor), 2, ',', '.') }}
                    <br>
                    Total del servicio:
                    $
                    {{ number_format($getLiquidados->where('servicio', $liquidacion->servicio)->reduce(fn($v1, $v2) => $v1 + $v2->total), 2, ',', '.') }}
                    <br>
                    <br>
                    <h3>Hospital</h3>
                    Cantidad de agentes: {{ $getLiquidados->count() }}
                    <br>
                    Subtotal del hospital:
                    $
                    {{ number_format($getLiquidados->reduce(fn($v1, $v2) => $v1 + $v2->subtot), 2, ',', '.') }}
                    <br>
                    Bonificacion total del hospital:
                    $
                    {{ number_format($getLiquidados->reduce(fn($v1, $v2) => $v1 + $v2->bonvalor), 2, ',', '.') }}
                    <br>
                    Total del hospital:
                    $
                    {{ number_format($getLiquidados->reduce(fn($v1, $v2) => $v1 + $v2->total), 2, ',', '.') }}
                @endif
            @endforeach

        </div>

    </div>
</body>

</html>
