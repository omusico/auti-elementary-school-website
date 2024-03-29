<?php
/**
 * FreeLibrary圖書管理系統
 *
 * LICENSE
 *
 * 本程式遵循GNU/GPL v3規範，詳情請見http://www.gnu.org/licenses/gpl.txt
 *
 * @category   View
 * @package    Script
 * @copyright  2008 ottokang
 * @license    http://www.gnu.org/licenses/gpl.txt   GNU/GPL License 3
 */
?>

<?php if ($this->message): ?>
    <?php echo $this->messageBlock($this->message) ?>
<?php else: ?>
    <table id="dates">
        <tr id="caption" style="line-height:1.2em;">
            <td style="font-size:130%;">
                <?php echo $this->ajaxLink("getCalendarBlock('" . $this->preMonthYear . '-' . $this->preMonth . "', 'right')", '«') ?>
            </td>
            <td colspan="5">
                <?php echo $this->escape($this->calendarCaption) ?>
            </td>
            <td style="font-size:130%;">
                <?php echo $this->ajaxLink("getCalendarBlock('" . $this->nextMonthYear . '-' . $this->nextMonth . "', 'left')", '»') ?>
            </td>
        </tr>
        <tr>
            <th>一</th>
            <th>二</th>
            <th>三</th>
            <th>四</th>
            <th>五</th>
            <th>六</th>
            <th>日</th>
        </tr>
        <?php foreach ($this->calendar['date'] as $row => $week): ?>
            <tr class="days">
                <?php foreach ($week as $weekDay => $day): ?>
                    <td class="<?php echo $day['type']?><?php if ($weekDay % 7 == 5 || $weekDay % 7 == 6): echo ' weekEnd'; endif;?><?php if ($day['date'] == $this->today): echo ' today'; endif;?>"><?php echo (int)substr($day['date'], -2) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach;?>
    </table>

    <div class="more">
        <?php echo $this->hyperLink('calendar/view/index/date/' . $this->thisMonth, '檢視' . $this->thisMonth. '行事曆»') ?>
    </div>

    <hr />

    <div id="events">
        <?php if (count($this->events) != 0): ?>
            <?php foreach ($this->events as &$eventEntry): ?>
                <div class="datePeriod">
                    <?php if ($eventEntry['startDate'] == $eventEntry['endDate']): ?>
                        <?php echo $eventEntry['startDate'] ?>
                    <?php else: ?>
                        <?php echo $eventEntry['startDate'] . '&nbsp;～&nbsp;' . $eventEntry['endDate'] ?>
                    <?php endif; ?>
                </div>
                <div class="eventName" style="color: <?php echo $eventEntry['backgroundColor'] ?>">
                    <?php echo $this->escape($eventEntry['eventName']) ?>
                </div>
            <?php endforeach;?>
        <?php else: ?>
            本月無事件
        <?php endif; ?>
    </div>

    <hr />
    
    <div id="festivalMessage">
    </div>

    <div id="festivalList">
    </div>
<?php endif; ?>