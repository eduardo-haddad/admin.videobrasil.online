<?php

namespace App\Traits;

trait DocumentTrait
{
    private function formatNumberToPattern($number, $pattern)
    {
        $formattedString = '';
        $number = str_split($number);
        $pattern = str_split($pattern);
        foreach ($pattern as $char) {
            if ($char === '#') {
                $formattedString .= array_shift($number);
            } else {
                $formattedString .= $char;
            }
        }
        return $formattedString;
    }

    /**
     * Format the given $document number to CPF or CNPJ format.
     *
     * @param integer $document
     * @return string
     */
    public function parseDocument($document)
    {
        if (!empty($document)) {
            if (strlen($document) === 11) {
                $document = $this->formatNumberToPattern($document, '###.###.###-##');
            } elseif (strlen($document) === 14) {
                $document = $this->formatNumberToPattern($document, '##.###.###/####-##');

            }
        }
        return $document;
    }

    /**
     * Format the given $document number to Número do Contribuinte format.
     *
     * @param integer $document
     * @return string
     */
    public function parseNumeroContribuinte($document)
    {
        if (!empty($document) && strlen($document) === 12) {
            $document = $this->formatNumberToPattern(str_replace('-', '', $document), '###.###.####-#');
        }
        return $document;
    }
}
