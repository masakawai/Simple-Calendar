<?php defined('C5_EXECUTE') or die('Access Denied.');

if (!isset($calendar) || !is_object($calendar)) {
    $calendar = null;
}
$c = Page::getCurrentPage();
$period = 3; // Calendar Display Period from now.(month)

if ($c->isEditMode()) {
    $loc = Localization::getInstance();
    $loc->pushActiveContext(Localization::CONTEXT_UI);
    ?><div class="ccm-edit-mode-disabled-item"><?=t('Calendar disabled in edit mode.')?></div><?php
    $loc->popActiveContext();
} elseif ($calendar !== null && $permissions->canViewCalendar()) { ?>
    <div class="ccm-block-calendar-wrapper simple-calendar" data-calendar="<?=$bID?>"></div>
    <p class="calendarCaption"><span class="caName"></span>　<span class="icon">■</span>&nbsp;<span class="text"></span></p>

    <script>
        $(function() {
            $('div[data-calendar=<?=$bID?>]').fullCalendar({
                defaultView: 'month',
                header: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                locale: <?= json_encode(Localization::activeLanguage()); ?>,
                events: '<?=$view->action('get_events')?>',
                displayEventTime: false,
                viewRender: function(view,element) {
                    var start = moment().month();
                    var end   = moment().add(<?=$period?>,'month').month();
                    var view  = moment(view.start).add(7,'days').month();

                    if ( end > view) {
                        $("div[data-calendar=<?=$bID?>] .fc-next-button").show();
                    }
                    else {
                        $("div[data-calendar=<?=$bID?>] .fc-next-button").hide();
                    }

                    if ( start < view) {
                        $("div[data-calendar=<?=$bID?>] .fc-prev-button").show();
                    }
                    else {
                        $("div[data-calendar=<?=$bID?>] .fc-prev-button").hide();
                    }
                },
                eventAfterRender: function(event, element, view) {
                    $('div[data-calendar=<?=$bID?>]+.calendarCaption .icon').css('color', event.backgroundColor);
                    $('div[data-calendar=<?=$bID?>]+.calendarCaption .text').text(event.title);
                    $('div[data-calendar=<?=$bID?>]+.calendarCaption .text').text(event.title);
                }
            });
        });
    </script>
<?php
} ?>
