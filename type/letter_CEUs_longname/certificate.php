<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * A4_embedded certificate type
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
	die('Direct access to this script is forbidden.');    ///  It must be included from view.php in mod/tracker
}

// Date formatting - can be customized if necessary
$certificatedate = '';
$certificatedate = certificate_get_date($certificate, $certrecord, $course);

//Grade formatting
$grade = '';
//Print the course grade

$coursegrade = certificate_get_grade($certificate, $course);
if ($certificate->printgrade == 1 && property_exists($certrecord, 'reportgrade')) {
    if ($certrecord->reportgrade == !null) {
        $reportgrade = $certrecord->reportgrade;
        $grade = $strcoursegrade . ':  ' . $reportgrade;
    }

}else
    if ($certificate->printgrade > 0) {
        if ($certificate->printgrade == 1) {
            if ($certificate->gradefmt == 1) {
                $grade = ' ' . $coursegrade->percentage;
            }
            if ($certificate->gradefmt == 2) {
                $grade = '  ' . $coursegrade->points;
            }
            if ($certificate->gradefmt == 3) {
                $grade = ' ' . $coursegrade->letter;

            }
        } else {
            //Print the mod grade
            $modinfo = certificate_get_mod_grade($course, $certificate->printgrade, $USER->id);
            if (property_exists($certrecord, 'reportgrade')) {
                if ($certrecord->reportgrade == !null) {
                    $modgrade = $certrecord->reportgrade;
                    $grade = $modinfo->name . ' ' . $strgrade . ': ' . $modgrade;
                }
            } else
                if ($certificate->printgrade > 1) {
                    if ($certificate->gradefmt == 1) {
                        $grade = $modinfo->name . ' ' . $modinfo->percentage;
                    }
                    if ($certificate->gradefmt == 2) {
                        $grade = $modinfo->name . ' ' . $modinfo->points;
                    }
                    if ($certificate->gradefmt == 3) {
                        $grade = $modinfo->name . ' ' . $modinfo->letter;
                    }
                }
        }
    }
//Print the outcome
$outcome = '';
if ($certificate->printoutcome) {
    $outcomeinfo = certificate_get_outcome($course, $certificate->printoutcome);
}
if($certificate->printoutcome > 0) {
	$outcome = $outcomeinfo->name.': '.$outcomeinfo->grade;
}

// Print the code number
$code = '';
if($certificate->printnumber) {
	$code = $certrecord->code;
}

//Print the student name
$studentname = '';
//$studentname = $certrecord->studentname;
$studentname = fullname($USER);
$classname = '';
$classname = $course->fullname;

// Print the custom class name
if($certificate->customtext)
{
	$classname = $certificate->customtext;
}
else {
	$classname = $course->fullname;
}
$lenClassname = strlen($classname);
$lenCN = intval($lenClassname);
if($lenCN<=65)
{
	$y1 = 125+265;
	$y2 = $y1 + 20;
	$y3 = $y2 + 20;
	$y4 = $y3 + 20;
	$y5 = $y4 + 20;

}
else {
	$y1 = 125+265+20;
	$y2 = $y1 + 20;
	$y3 = $y2 + 20;
	$y4 = $y3 + 20;
	$y5 = $y4 + 20;
}

//Print the credit hours by Dongyoung
// if($certificate->printhours) {
// $credithours =  $strcredithours.': '.$certificate->printhours;
// } else $credithours = '';

if($certificate->printhours) {
	if($certificate->printhours==1)
	{
		$credithours =  'for a total of '.$certificate->printhours.' program hour';
	}
	else {
		$credithours =  'for a total of '.$certificate->printhours.' program hours';
	}

} else $credithours = '';

if($certificate->eduhours) {
	if($credithours == '')
	{
		if($certificate->eduhours==1)
		{
			$eduhours =  $certificate->eduhours.' hour of continuing education';
		}
		else {
			$eduhours =  $certificate->eduhours.' hours of continuing education';
		}
	}
	else {
		if($certificate->eduhours==1)
		{
			$eduhours =  ' or '.$certificate->eduhours.' hour of continuing education';
		}
		else {
			$eduhours =  ' or '.$certificate->eduhours.' hours of continuing education';
		}
	}
} else $eduhours = '';


// Print Location
if($certificate->location =='')
{
	$location = '';
}
else {
	$location = $certificate->location;
}

// Print Trainers
if($certificate->trainer == '')
{
	$trainersname = '';
}
else {
	$trainersname = 'Presented by '.$certificate->trainer;
}

$customcertificatedate = '';

if($certificate->customdate > 0) {
    $customdate = $certificate->customdate;
} else {
    $customdate = certificate_get_date($certificate, $certrecord, $course);
}

if($certificate->customdate > 0)    {
	if ($certificate->datefmt == 1)    {
		$customcertificatedate = str_replace(' 0', ' ', strftime('%B %d, %Y.', $customdate));
	}   if ($certificate->datefmt == 2) {
		$customcertificatedate = date('F jS, Y.', $customdate);
	}   if ($certificate->datefmt == 3) {
		$customcertificatedate = str_replace(' 0', '', strftime('%d %B %Y.', $customdate));
	}   if ($certificate->datefmt == 4) {
		$customcertificatedate = strftime('%B %Y.', $customdate);
	}   if ($certificate->datefmt == 5) {
		$timeformat = get_string('strftimedate');
		$customcertificatedate = userdate($customdate, $timeformat);
	}
}

$customcertificatedate2 = '';
$customdate2 = $certificate->customdate2;
if($certificate->customdate2 > 0)    {
	if ($certificate->datefmt == 1)    {
		$customcertificatedate2 = str_replace(' 0', ' ', strftime('%B %d, %Y.', $customdate2));
	}   if ($certificate->datefmt == 2) {
		$customcertificatedate2 = date('F jS, Y.', $customdate2);
	}   if ($certificate->datefmt == 3) {
		$customcertificatedate2 = str_replace(' 0', '', strftime('%d %B %Y.', $customdate2));
	}   if ($certificate->datefmt == 4) {
		$customcertificatedate2 = strftime('%B %Y.', $customdate2);
	}   if ($certificate->datefmt == 5) {
		$timeformat = get_string('strftimedate');
		$customcertificatedate2 = userdate($customdate2, $timeformat);
	}
}



// Print Custom date
$certificateprintdate = "";
if($certificate->customdate == 0) {
	if(empty($certificatedate)) {
		$customdate = "";
	} else {
		$customdate = " on ".$certificatedate;
                $certificateprintdate = $certificatedate;
	}
} else {
	if($certificate->customdate2 == 0) {
		$customdate = " on ".$customcertificatedate;
                $certificateprintdate = $customcertificatedate;
	} else {
		$customdate = " from ".$customcertificatedate. " to ".$customcertificatedate2;
                $certificateprintdate = $customcertificatedate;
	}
}

$certificateprintcode = (certificate_get_code($certificate, $certrecord));

// This is to update the certificateprintdate column, so that it can be used in the configurable report
$DB->execute("UPDATE mdl_certificate_issues SET mdl_certificate_issues.certificateprintdate='$certificateprintdate' WHERE mdl_certificate_issues.code='$certificateprintcode'");

$pdf = new TCPDF($certificate->orientation, 'pt', 'Letter', true, 'UTF-8', false);
// $pdf->SetProtection(array('print'));
$pdf->SetTitle($certificate->name);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetAutoPageBreak(false, 0);

//Define variables

//Landscape
if ($certificate->orientation == 'L') {
	$x = 28;
	$y = 125;
	$sealx = 590;
	$sealy = 425;
	$sigx = 130;
	$sigy = 440;
	$custx = 133;
	$custy = 440;
	$wmarkx = 100;
	$wmarky = 90;
	$wmarkw = 600;
	$wmarkh = 420;
	$brdrx = 0;
	$brdry = 0;
	$brdrw = 792;
	$brdrh = 612;
	$codey = 505;
} else {
	//Portrait
	$x = 28;
	$y = 170;
	$sealx = 440;
	$sealy = 590;
	$sigx = 85;
	$sigy = 580;
	$custx = 88;
	$custy = 580;
	$wmarkx = 78;
	$wmarky = 130;
	$wmarkw = 450;
	$wmarkh = 480;
	$brdrx = 10;
	$brdry = 10;
	$brdrw = 594;
	$brdrh = 771;
	$codey = 660;
}

// Add Underlines By Dongyoung
$udline = str_pad(' ',9,"_");

// Add images and lines
certificate_print_image($pdf, $certificate, CERT_IMAGE_BORDER, $brdrx, $brdry, $brdrw, $brdrh);
certificate_draw_frame($pdf, $certificate);
// Set alpha to semi-transparency
$pdf->SetAlpha(0.1);

certificate_print_image($pdf, $certificate, CERT_IMAGE_WATERMARK, $wmarkx, $wmarky, $wmarkw, $wmarkh);
$pdf->SetAlpha(1);
certificate_print_image($pdf, $certificate, CERT_IMAGE_SEAL, $sealx, $sealy, '', '');
certificate_print_image($pdf, $certificate, CERT_IMAGE_SIGNATURE, $sigx, $sigy, '', '');

// Add text
$pdf->SetTextColor(0,0,120);
certificate_print_text($pdf, $x, $y, 'C', 'freesans', 'B', 30, get_string('title', 'certificate'));
$pdf->SetTextColor(0,0,0);
certificate_print_text($pdf, $x, $y + 55, 'C', 'freeserif', '', 20, get_string('certify', 'certificate'));
certificate_print_text($pdf, $x, $y + 105, 'C', 'freeserif', '', 30, fullname($USER) . ', License #' . $udline);
certificate_print_text($pdf, $x, $y + 155, 'C', 'freeserif', '', 20, get_string('statement', 'certificate'));
certificate_print_text($pdf, $x, $y + 205, 'C', 'freeserif', 'B', 19, $classname);
certificate_print_text($pdf, $x, $y + 100, 'C', 'freeserif', '', 12, $credithours . $eduhours . $customdate);
certificate_print_text($pdf, $x, $y2, 'C', 'freeserif', '', 12, $trainersname);
certificate_print_text($pdf, $x, $y3, 'C', 'freeserif', '', 12, $location . '.');
certificate_print_text($pdf, $x, $y + 283, 'C', 'freeserif', '', 12, $grade);

certificate_print_text($pdf, $x, $y + 311, 'C', 'freeserif', '', 12, $outcome);
certificate_print_text($pdf, $x, $codey, 'C', 'freeserif', '', 10, get_string('verificationcode', 'certificate') . $certificateprintcode);
certificate_print_text($pdf, $x, $codey + 25, 'C', 'freeserif', '', 8.5, get_string('bottomline', 'certificate'));
?>
