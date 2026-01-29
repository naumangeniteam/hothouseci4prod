<?php $request = service('request'); ?>
<style>
    .row {
        display: flex;
        flex-wrap: wrap;
    }

    .col-md-6,
    .col-6 {
        flex: 0 0 50%;
        box-sizing: border-box;
        padding: 10px;
    }

    .report-al {
        font-size: 8pt;
        padding: 0;
        margin: 0;
    }

    .venue-title {
        margin-top: 20px;
        margin-bottom: 20px;
        font-size: 1.5em;
        display: flex;
        gap: 6px;
        align-items: baseline;
        border: 1px solid black;
        color: #fff;
        background: #000;
        padding: 10px;
        justify-content: center;
    }

    .download_pdf_btn {
        height: fit-content;
    }

    .download_new_btn {
        height: fit-content;
        margin-left: 20px;
    }

    .file_form {
        margin-left: auto;
    }

    .al-locats {
        font-size: 16px;
    }

    .web-al {
        color: #000;
    }

    .web-al:hover {
        color: #000;
    }

    @media print {
        body {
            visibility: hidden;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        #section-to-print {
            visibility: visible;
            position: absolute;
            margin: 0 auto;
            padding: 0;
        }

        .venue-title {
            text-align: center;
        }

        .location-section {
            float: left;
            margin: 30px;
        }
    }

    @page {
        size: auto;
        margin: 10mm;
    }
</style>

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Generate Report List</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Generate Report</h5>
                        <a href="<?= base_url('hhjsitemgmt/adminmanageeventreport/index') ?>" class="btn btn-sm btn-primary pull-right">Back</a>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <a href="<?= base_url('hhjsitemgmt/adminmanageeventreport/download_pdf') ?>?<?= ($_SERVER['QUERY_STRING']) ?>" class="btn btn-primary download_pdf_btn">
                                Download PDF
                            </a>
                            <form action="<?= base_url('hhjsitemgmt/adminmanageeventreport/download_new_pdf') ?>" method="get" class="file_form">
                                <input type="hidden" name="searchValue" value="<?= urlencode($searchValue) ?>">
                                <input type="hidden" name="sort_by" value="<?= urlencode($sortField) ?>">
                                <input type="hidden" name="order" value="<?= urlencode($sortOrder) ?>">

                                <!-- Include additional filters as hidden fields -->
                                <input type="hidden" name="start_date" value="<?= urlencode($request->getPost('start_date')) ?>">
                                <input type="hidden" name="end_date" value="<?= urlencode($request->getPost('end_date')) ?>">
                                <input type="hidden" name="venue_id" value="<?= urlencode($request->getPost('venue_id')) ?>">
                                <input type="hidden" name="location_name" value="<?= urlencode($request->getPost('location_name')) ?>">

                                <div class="d-flex">
                                    <div class="form-group">
                                        <select name="format" id="format" class="form-control">
                                            <option value="txt">TXT</option>
                                            <option value="pdf">PDF</option>

                                            <!-- <option value="docx">DOCX</option>
                                            <option value="rtf">RTF</option> -->
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary download_new_btn">
                                        Download New Report
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="row">

                            <?php foreach ($result as $venueTitle => $locations) :  ?>
                                <div class="col-12">
                                    <h2 class="venue-title text-center"><?= htmlspecialchars($venueTitle) ?></h2>
                                </div>

                                <?php foreach ($locations as $locationName => $events) :  ?>
                                  
                                    <div class="col-md-6 col-6">

                                        <div class="location-section">
                                            <p class="report-al">
                                                <strong class="al-locats"><?= htmlspecialchars($locationName) ?>:</strong>
                                                <?= htmlspecialchars($events[0]['location_address']) ?> .
                                                <?php
                                                $url = $events[0]['website'];
                                                $url_display = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $url);
                                                ?>
                                                <a class="web-al" href="<?= rtrim(htmlspecialchars($url), '/') ?>" target="_blank"><?= rtrim(htmlspecialchars($url_display), '/') ?></a>
                                                . <?= htmlspecialchars($events[0]['phone_number']) ?>.
                                            </p>
                                            <?php
                                            $eventDetails = '';
                                            foreach ($events as $event) {
                                                $startDate = date('M j', strtotime($event['start_date']));
                                                $endDate = !empty($event['end_date']) ? date('M j', strtotime($event['end_date'])) : '';

                                                $dateRange = $startDate;
                                                if ($startDate != $endDate && !empty($endDate)) {
                                                    $dateRange .= ' & ' . $endDate;
                                                }
                                                $eventStartTime = strtotime($event['event_start_time']);
                                                $eventEndTime = !empty($event['event_end_time'])
                                                    ? strtotime($event['event_end_time'])
                                                    : strtotime('+1 hour 30 minutes', $eventStartTime);
                                                  

                                                // Format the times
                                                $formattedStartTime = date('g:ia', $eventStartTime);
                                                $formattedEndTime = date('g:ia', $eventEndTime);
                                                // echo"<pre>"; print_r($formattedEndTime); die;

                                                // if (isset($formattedEndTime) && $formattedEndTime === '1:00pm') {
                                                //     // Recalculate the end time based on the raw start time
                                                //     $eventEndTime = strtotime('+1 hour 30 minutes', $eventStartTime);
                                                //     $formattedEndTime = date('g:ia', $eventEndTime); // Reformat after calculation
                                                // }
                                                if (isset($event['time_permission']) && $event['time_permission'] === 'Yes') {
                                                    // Recalculate the end time based on the raw start time
                                                    $eventEndTime = strtotime('+1 hour 30 minutes', $eventStartTime);
                                                    $formattedEndTime = date('g:ia', $eventEndTime); // Reformat after calculation
                                                }

                                                // $eventDetails .= '<br>' . date('D', strtotime($event['start_date'])) . ' : ' . $dateRange . ' - ' . date('g:ia', strtotime($event['event_start_time'])) . ' & ' . date('g:ia', strtotime($event['event_end_time'])) . ' . ' . htmlspecialchars($event['cover_charge']) . ' <strong>' . htmlspecialchars($event['event_title']) . '</strong>; ';
                                                $eventDetails .= '<br>' . date('D', strtotime($event['start_date'])) . ' : ' . $dateRange . ' - ' . $formattedStartTime . ' . ' . htmlspecialchars($event['cover_charge']) . ' <strong>' . htmlspecialchars($event['event_title']) . '</strong>; ';
                                            }
                                            ?>
                                            <p class="report-al">
                                                <?= $eventDetails ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>