<?php

if (!function_exists('cifraHtmlToPlainText')) {
    function cifraHtmlToPlainText($html)
{
    // Converte <br> e <div> em nova linha
    $html = preg_replace('/<br\s*\/?>/i', "\n", $html);
    $html = preg_replace('/<\/div>/i', "\n", $html);
    $html = preg_replace('/<div[^>]*>/i', '', $html);

    // Converte espaços não separáveis
    $html = str_replace('&nbsp;', ' ', $html);

    // Remove span/strong mantendo conteúdo
    $html = preg_replace('/<[^>]*class="[^"]*cifra-chord[^"]*"[^>]*>(.*?)<\/[^>]+>/', '$1', $html);
    $html = preg_replace('/<strong>(.*?)<\/strong>/', '$1', $html);

    // Remove demais tags
    $plain = strip_tags($html);

    return html_entity_decode($plain);
}
}
