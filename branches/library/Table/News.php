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
 * Table_News
 *
 * 最新消息資料表
 */
class Table_News extends Table_Abstract
{
    /**
     * @var string 資料表名稱
     * @access protected
     */
    protected $_name = 'news';
    
    /**
     * 定義和Office、Title、User的關聯
     */
    protected $_referenceMap = array(
        'Office' => array(
            'columns'       => 'officeId',
            'refTableClass' => 'Table_Office',
            'refColumns'    => 'officeId'
        ),
        
        'Title' => array(
            'columns'       => 'titleId',
            'refTableClass' => 'Table_Title',
            'refColumns'    => 'titleId'
        ),
        
        'User' => array(
            'columns'       => 'userId',
            'refTableClass' => 'Table_User',
            'refColumns'    => 'userId'
        )
    );

    /**
     * 定義關聯資料表
     */
    protected $_dependentTables = array('Table_NewsAttachment', 'Table_NewsLink');
}
?>
