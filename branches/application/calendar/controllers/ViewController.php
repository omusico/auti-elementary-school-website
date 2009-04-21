<?php
/**
 * 澳底國小網站程式
 *
 * LICENSE
 *
 * 本程式遵循GNU/GPL v3規範，詳情請見http://www.gnu.org/licenses/gpl.txt
 *
 * @copyright  2008 ottokang
 * @license    http://www.gnu.org/licenses/gpl.txt   GNU/GPL License 3
 */

/**
 * Calendar_ViewController
 *
 * 顯示行事曆
 */
class Calendar_ViewController extends Controller
{
    /**
     * 轉向到顯示兩週行事曆
     */
    public function indexAction()
    {
        $this->_forward('by2week');
    }

    /**
     * 顯示兩週行事曆
     * 參數：date/2009-10-12
     */
    public function by2weekAction()
    {
        if ($date = $this->getParam('date')) {
            if (!Date::isDate($date)) {
                $this->redirect('calendar/view', '日期設定錯誤');
            }
        } else {
            $date = Date::getDate();
        }

        // 設定日期範圍
        $dateObj = new Zend_Date();
        $dateObj->set($date, 'YYYY-MM-dd');
        $dateObj->sub($dateObj->get(Zend_Date::WEEKDAY_8601) - 1, Zend_Date::DAY);
        $satrtDate = $dateObj->get('YYYY-MM-dd');
        $endDate   = Date::add($satrtDate, 13);

        // 設定View變數
        $this->view->calendar = $this->_getDateRangeArray($satrtDate, $endDate);
        $this->view->events   = $this->_getEvents($satrtDate, $endDate, $this->view->calendar);
        $this->view->type     = 'by2Week';
        $this->view->date     = $satrtDate;
        $this->view->period   = '〔' . $satrtDate . '〕～〔' . $endDate . '〕';
        $this->render('index');
    }

    /**
     * 顯示月份行事曆
     * 參數：date/2008-10-14
     */
    public function bymonthAction()
    {
        // 取得日期
        if ($date = $this->getParam('date')) {
            if (!Date::isDate($date)) {
                $this->redirect('calendar/view', '日期設定錯誤');
            }
        } else {
            $date = Date::getDate();
        }
        
        // 設定日期範圍
        $dateObj = new Zend_Date();
        $dateObj->set($date, 'YYYY-MM-dd');
        $year        = $dateObj->get(Zend_Date::YEAR_8601);
        $month       = $dateObj->get(Zend_Date::MONTH);
        $daysOfMonth = $dateObj->get(Zend_Date::MONTH_DAYS);

        $this->view->calendar = $this->_getDateRangeArray("$year-$month-01", "$year-$month-$daysOfMonth");
        $this->view->events   = $this->_getEvents(Date::add($this->view->calendar['preDate'], 1),
                                                  Date::add($this->view->calendar['afterDate'], -1),
                                                  $this->view->calendar);
        $this->view->type     = $this->getRequest()->getActionName();
        $this->view->date     = "$year-$month-01";
        $this->view->period   = $year . '年' . $month . '月';
        $this->render('index');
    }

    private function _getDateRangeArray($startDate, $endDate)
    {
        // 取得跟前一週星期天的距離、日期
        $date = new Zend_Date();
        $date->set($startDate, 'YYYY-MM-dd');
        $preStartDays = $date->get(Zend_Date::WEEKDAY_8601) - 1;
        $preStartDate = Date::add($startDate, -$preStartDays);

        // 取得跟最後一週星期天的距離
        $date->set($endDate, 'YYYY-MM-dd');
        $afterStartDays = 7 - $date->get(Zend_Date::WEEKDAY_8601);
        $afterStartDate = Date::add($endDate, $afterStartDays);

        // 回傳陣列
        $row = 0;
        $calendar['preStartDays']   = $preStartDays;
        $calendar['preDate']        = Date::add($preStartDate, -8);
        $calendar['afterStartDays'] = $afterStartDays;
        $calendar['afterDate']      = Date::add($afterStartDate, 1);
        
        for ($i = 0; $preStartDate <= $afterStartDate; $i++, $preStartDate = Date::add($preStartDate, 1)) {
            if ($i > 0 && $i % 7 == 0) {
                $row++;
                $i = 0;
            }
            $calendar['date'][$row][$i] = $preStartDate;
        }

        return $calendar;
    }
    
    /**
     * 取得事件
     * @param string $startDate 初始日期
     * @param string $endDate   結束日期
     * @param int    $calendar  日期陣列
     * @return array 事件陣列
     */
    private function _getEvents($startDate, $endDate, $calendar)
    {
        $calendarWeeks = count($calendar['date']);
        
        for ($week = 0; $week < $calendarWeeks; $week++) {
            for($day = 0; $day < 7; $day++) {
                for($slot = 0; $slot < 5; $slot++) {
                    $events[$week][$day][$slot] = true;
                }
            }
        }

        $event = new Model_Event();
        $eventRowset = $event->getTable()->where("endDate >= '$startDate' AND startDate <= '$endDate'")->order('startDate')->getRowset();
        
        foreach ($eventRowset as $eventRow) {
            $startDate = $eventRow->startDate;
            $endFlag   = false;
            for ($week = 0; $week < $calendarWeeks && $endFlag == false; $week++) {
                for ($day = 0; $day < 7; $day++) {
                    if ($startDate > $calendar['date'][$week][$day]) {
                        continue;
                    } else {
                        if ($startDate < $calendar['date'][$week][$day]) {
                            $startDate = $calendar['date'][$week][$day];
                            $hasPre = true;
                        } else {
                            $hasPre = false;
                        }
                        
                        // 進入事件處理，尋找空的位置（$slot）
                        for ($slot = 0; $slot < 5; $slot++) {
                            if ($events[$week][$day][$slot] === true) {
                                break;
                            }
                        }
                        
                        // 計算和週末的距離
                        $distanceToSunday = Date::sub($calendar['date'][$week][6], $startDate);
                        $distanceToEnd    = Date::sub($eventRow->endDate, $startDate);
                        
                        if ($distanceToEnd > $distanceToSunday) {
                            $colspan   = $distanceToSunday + 1;
                            $hasNext   = true;
                            $startDate = Date::add($startDate, $distanceToSunday);
                        } else {
                            $colspan   = $distanceToEnd + 1;
                            $hasNext   = false;
                            $startDate = Date::add($startDate, $distanceToEnd);
                            $endFlag   = true;
                        }
                        
                        // 儲存Events
                        $events[$week][$day][$slot]                    = $eventRow->toArray();
                        $events[$week][$day][$slot]['backgroundColor'] = $eventRow->findParentRow('Table_EventCatalog')->backgroundColor;
                        $events[$week][$day][$slot]['colspan']         = $colspan;
                        $events[$week][$day][$slot]['hasPre']          = $hasPre;
                        $events[$week][$day][$slot]['hasNext']         = $hasNext;
                        
                        // 設定之後的陣列內容為null
                        for ($endDay = $day + $colspan, $day++; $day < $endDay ; $day++) {
                            $events[$week][$day][$slot] = null;
                        }
                        
                        break;
                    }
                }
            }
        }

        return $events;
    }
}
?>
