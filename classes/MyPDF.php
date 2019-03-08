<?php
require(__DIR__ . '/../libs/fpdf17/tfpdf.php');

class MyPDF extends tFPDF
{
    // Current column
    var $col = 0;
    // Ordinate of column start
    var $y0;
    const MAX_COL_NO = 1;
    const MARGIN = 20;
    const COLUMN_WIDTH = 130;

    function Header()
    {/*
        // Page header
        global $title;

        $this->SetFont('Arial','B',15);
        $w = $this->GetStringWidth($title)+6;
        $this->SetX((210-$w)/2);
        $this->SetDrawColor(0,80,180);
        $this->SetFillColor(230,230,0);
        $this->SetTextColor(220,50,50);
        $this->SetLineWidth(1);
        $this->Cell($w,9,$title,1,1,'C',true);
        $this->Ln(10); */
        // Save ordinate
        $this->y0 = $this->GetY();
    }
/*
    function Footer()
    {
        // Page footer
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(128);
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }*/

    function SetCol($col)
    {
        // Set position at a given column
        $this->col = $col;
        $x = self::MARGIN +$col* self::COLUMN_WIDTH;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    function AcceptPageBreak()
    {
        // Method accepting or not automatic page break
        if($this->col < self::MAX_COL_NO)
        {
            // Go to next column
            $this->SetCol($this->col+1);
            // Set ordinate to top
            $this->SetY($this->y0);
            // Keep on page
            return false;
        }
        else
        {
            // Go back to first column
            $this->SetCol(0);
            // Page break
            return true;
        }
    }

    function ChapterTitle($label)
    {
        // Title
        $this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
        $this->SetFont('DejaVu','',20);
        $this->Cell(0,12, $label);
        $this->Ln(4);
        // Save ordinate
        $this->y0 = $this->GetY();
    }

    function ChapterBody($file)
    {
        try {
            if (!($fp = @fopen(__DIR__ . "/../temp/temp_zgloszenia.txt", "r")))
                throw new Exception("aaNiepowodzenie przy otwarciu tymczasowego pliku. Spróbuj później!");
            flock($fp, LOCK_SH); //blokada odczytu
            
            $this->Image(__DIR__ . '/../img/Logo_MK_2019_300.jpg',10,20, 70);
            $this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
            $this->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
            $this->SetFont('DejaVu','',18);

            if (!($last_name = fgets($fp)))
                throw new Exception("bbNiepowodzenie przy odczycie tymczasowego pliku. Spróbuj później!");

            //znak '\n' zapisuje się jako kratka. Usuwamy go ze stringu zapisywanego
            $last_name = substr($last_name, 0, strlen($last_name) - 1); 
            $this->SetY(35);
            $this->Cell(120,10, $last_name);
            $this->Ln();

            $this->SetFont('DejaVu','',10);
            $text = "";
            while (($line = fgets($fp)) !== false) 
            {
                //$line = substr($line, 0, strlen($line) - 1);
                $text .= $line;
            }
            $this->MultiCell(120,7,$text);
        } catch (Exception $e) {
            echo "<p>" . $e->getMessage() . "</p>";
        }

        //$this->MultiCell(120,5,$txt);
        self::AcceptPageBreak();
        // $this->Ln();
        // $this->Ln();
        // $this->Ln();
        // $this->Ln();

        $this->SetFont('DejaVu','',8);
        $text1 = "";
        try {
            if (!($fp1 = @fopen(__DIR__ . "/../../../mk_dane/zgloszenia/tresc.txt", "r")))
                throw new Exception("ccNiepowodzenie przy otwarciu tymczasowego pliku. Spróbuj później!");
            while (($line = fgets($fp1)) !== false) 
            {
                //$line = substr($line, 0, strlen($line) - 1);
                $text1 .= $line;
            }
            $this->MultiCell(120,4,$text1);
            fclose($fp1);
        } catch (Exception $e) {
            echo "<p>" . $e->getMessage() . "</p>";
        }

        $this->Ln();

        $this->Cell(120,9, "________________________________", 0, 0, 'R');
        $this->Ln();
        $this->Cell(120,7, "data, czytelny podpis", 0, 0, 'R');

        $this->Ln();
        $this->SetFont('DejaVu','B',10);
        $this->Cell(120,9, "WYPEŁNIĆ W PRZYPADKU OSOBY NIEPEŁNOLETNIEJ");

        $text2 = "Wyrażam zgodę na udział w zawodach z cyklu Maratony Kresowe mojego niepełnoletniego
dziecka / osoby niepełnoletniej pozostającej pod moją opieką prawną.";
        $this->SetFont('DejaVu','',8);
        $this->Ln();
        $this->MultiCell(120,4,$text2);
        $this->Ln();
        $this->Cell(120,9, "________________________________", 0, 0, 'R');
        $this->Ln();
        $this->Cell(120,7, "numer dowodu, data, czytelny podpis", 0, 0, 'R');


        flock($fp, LOCK_UN); //odblokowanie
        fclose($fp);


        // Mention
    /*    $this->SetFont('','I');
        $this->Cell(0,5,'(end of excerpt)');*/
        // Go back to first column
        $this->SetCol(0);
    }

    function PrintDoc($file, $title)
    {
        // Add chapter
        $this->AddPage('L');
        $this->ChapterTitle($title);
        $this->ChapterBody($file);
    }
}

//http://kresowe.az.pl/mk14/wp-content/uploads/2013/11/mk-logo-bez-napisu-szary2.png
?>