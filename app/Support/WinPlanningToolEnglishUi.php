<?php

namespace App\Support;

class WinPlanningToolEnglishUi
{
    /**
     * @var array<string, string>
     */
    private const STRING_REPLACEMENTS = [
        '¡Felicitaciones!' => 'Congratulations!',
        'En base a promedios regionales generales, estás debajo del presupuesto por' => 'Based on general regional averages, you are under budget by',
        '¿Cómo te gustaría distribuir esos fondos?' => 'How would you like to distribute those funds?',
        'Asignar más a servicios existentes' => 'Assign more to existing services',
        'Asignar a nuevos servicios' => 'Assign to new services',
    ];

    public static function normalize(string $html): string
    {
        return str_replace(
            array_keys(self::STRING_REPLACEMENTS),
            array_values(self::STRING_REPLACEMENTS),
            $html
        );
    }

    public static function appendInvestmentPlannerOverrides(string $html): string
    {
        $html = self::normalize($html);

        $script = <<<'HTML'
<script>
(function () {
  if (typeof buildSurplusPanel !== 'function') {
    return;
  }

  buildSurplusPanel = function (total, spentTotal, activeRows) {
    const recommended = activeRows.reduce((sum, row) => sum + Math.max(row.planned, row.spent), 0);
    const surplus = total - recommended;
    if (!(surplus > 0)) {
      return '';
    }

    return `<div class="note" style="margin-top:14px;line-height:1.65;">
      <strong>Congratulations!</strong> Based on general regional averages, you are under budget by ${fmtMoney(Math.round(surplus))}. How would you like to distribute those funds?
      <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;">
        <button class="btnGhost" type="button" id="surplusExistingBtn">Assign more to existing services</button>
        <button class="btnGhost" type="button" id="surplusNewBtn">Assign to new services</button>
      </div>
    </div>`;
  };
})();
</script>
HTML;

        return preg_replace('/<\/body>/', $script . '</body>', $html, 1) ?? $html;
    }

    public static function finalizeTimeline(string $html): string
    {
        return self::normalize($html);
    }
}
