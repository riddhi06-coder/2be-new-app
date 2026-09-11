<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4 landscape; margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Times New Roman", Times, serif; color: #1a2230; }

        .sheet { position: relative; width: 841pt; height: 591pt; overflow: hidden; }

        /* Decorative double frame (top/size based — dompdf mis-paginates on `bottom`) */
        .frame { position: absolute; top: 14pt; left: 14pt; width: 813pt; height: 563pt; border: 3pt solid #0004fe; }
        .frame-inner { position: absolute; top: 20pt; left: 20pt; width: 801pt; height: 551pt; border: 1pt solid #c3cadb; }

        /* Faint centered watermark */
        .watermark { position: absolute; top: 150pt; left: 291pt; width: 260pt; }

        .content { position: absolute; top: 34pt; left: 60pt; width: 721pt; text-align: center; }

        .logo { height: 56pt; }
        .eyebrow { font-family: "Helvetica", Arial, sans-serif; font-size: 9pt; letter-spacing: 4pt;
            color: #6a7688; text-transform: uppercase; font-weight: bold; margin-top: 10pt; }
        .brandbar { width: 46pt; height: 3pt; background: #e62029; margin: 11pt auto 0; }

        .title { font-size: 34pt; font-weight: bold; letter-spacing: 1pt; margin-top: 16pt; color: #1a2230; }
        .subtitle { font-family: "Helvetica", Arial, sans-serif; font-size: 9.5pt; color: #6a7688;
            letter-spacing: 1pt; margin-top: 6pt; }

        .lead { font-size: 12.5pt; color: #5a6576; margin-top: 22pt; }
        .name { font-size: 28pt; font-weight: bold; font-style: italic; color: #0004fe; margin-top: 6pt; }
        .name-rule { width: 300pt; border-bottom: 1pt solid #c3cadb; margin: 8pt auto 0; }

        .body { font-size: 12pt; line-height: 1.6; color: #3d4551; margin-top: 18pt; }
        .doc { font-weight: bold; color: #1a2230; }

        /* Signature + date row */
        .cols { width: 470pt; margin: 34pt auto 0; }
        .cols td { width: 50%; vertical-align: bottom; padding: 0 18pt; }
        .sig-name { font-size: 19pt; font-style: italic; font-weight: bold; color: #1a2230; padding-bottom: 3pt; }
        .sig-line { border-top: 1pt solid #8892a3; padding-top: 5pt; }
        .sig-cap { font-family: "Helvetica", Arial, sans-serif; font-size: 8.5pt; letter-spacing: 1pt;
            text-transform: uppercase; color: #8892a3; }
        .date-val { font-size: 15pt; font-weight: bold; color: #1a2230; padding-bottom: 4pt; }

        /* Seal — verified medallion image */
        .seal { position: absolute; left: 705pt; top: 452pt; width: 90pt; text-align: center; }
        .seal .seal-img { width: 66pt; height: 66pt; }
        .seal .lbl { font-family: "Helvetica", Arial, sans-serif; font-size: 7pt; font-weight: bold;
            letter-spacing: 1.5pt; line-height: 1.2; color: #1a7f43; margin-top: 6pt; }

        /* Footer audit line */
        .audit { position: absolute; left: 60pt; top: 548pt; width: 721pt; text-align: center;
            font-family: "Helvetica", Arial, sans-serif; font-size: 8pt; color: #9aa2b1; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="frame"></div>
        <div class="frame-inner"></div>
        @if($watermark)<img src="{{ $watermark }}" class="watermark" alt="">@endif

        <div class="content">
            @if($logo)<img src="{{ $logo }}" class="logo" alt="2B Environmental">@endif
            <div class="eyebrow">2B Environmental &nbsp;&middot;&nbsp; HR Portal</div>
            <div class="brandbar"></div>

            <div class="title">Certificate of Acknowledgment</div>
            <div class="subtitle">This certificate confirms electronic receipt &amp; understanding</div>

            <div class="lead">This is to certify that</div>
            <div class="name">{{ $signedName }}</div>
            <div class="name-rule"></div>

            <div class="body">
                has read, understood and acknowledged the document<br>
                <span class="doc">&ldquo;{{ $document->title }}&rdquo;</span>
            </div>

            <table class="cols">
                <tr>
                    <td>
                        <div class="sig-name">{{ $signedName }}</div>
                        <div class="sig-line sig-cap">Electronic Signature</div>
                    </td>
                    <td>
                        <div class="date-val">{{ $at->format('M j, Y') }}</div>
                        <div class="sig-line sig-cap">Date Signed &middot; {{ $at->format('g:i A') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="seal">
            @if($seal)<img src="{{ $seal }}" class="seal-img" alt="">@endif
            <div class="lbl">ACKNOWLEDGED</div>
        </div>

        <div class="audit">
            Electronically signed in the 2B Environmental HR Portal &nbsp;&middot;&nbsp;
            IP {{ $ip ?: 'n/a' }} &nbsp;&middot;&nbsp;
            Ref #{{ $document->id }}-{{ $employee->id }} &nbsp;&middot;&nbsp;
            The typed name above constitutes the employee&rsquo;s legally binding electronic signature.
        </div>
    </div>
</body>
</html>
