<?php

namespace App\Service;

use Exception;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class FileCreator
{
    private $pdfStyleDirectory;
    private $twig;

    public function __construct($pdfStyleDirectory, Environment $twig)
    {
        $this->pdfStyleDirectory = $pdfStyleDirectory;
        $this->twig = $twig;
    }

    /**
     * @throws MpdfException
     */
    public function initPDF($title, $password = 'Pf3zGgig5hy5'): Mpdf
    {
        $mpdf = new Mpdf(['tempDir' => '/tmp']);

        $mpdf->SetTitle($title);
        $stylesheet = file_get_contents($this->getPdfStyleDirectory() . '/bootstrap.min.css');
        $stylesheet2 = file_get_contents($this->getPdfStyleDirectory() . '/custom-pdf.css');
        $mpdf->WriteHTML($stylesheet,HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($stylesheet2,HTMLParserMode::HEADER_CSS);

        $mpdf->SetProtection(array(
            'print'
        ),'', $password);

        return $mpdf;
    }

    /**
     * @throws Exception
     */
    public function writePDF(Mpdf $mpdf, $template, $templateParams = []): Mpdf
    {
        try {
            $mpdf->WriteHTML(
                $this->twig->render($template, $templateParams),
                HTMLParserMode::HTML_BODY
            );
        } catch (MpdfException|LoaderError|RuntimeError|SyntaxError $e) {
            throw new Exception($e);
        }

        return $mpdf;
    }

    /**
     * @throws MpdfException
     */
    public function outputPDF(Mpdf $mpdf, $filename, $destination = Destination::INLINE): Mpdf
    {
        $mpdf->Output($filename, $destination);

        return $mpdf;
    }

    /**
     * @throws MpdfException
     * @throws Exception
     */
    public function createPDF($title, $filename, $template, $templateParams = [],
                              $destination = Destination::INLINE, $password = 'Pf3zGgig5hy5'): Mpdf
    {
        $mpdf = $this->initPDF($title, $password);

        $mpdf = $this->writePDF($mpdf, $template, $templateParams);

        $mpdf->Output($filename, $destination);

        return $mpdf;
    }

    public function getPdfStyleDirectory()
    {
        return $this->pdfStyleDirectory;
    }
}