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
?>
<?php $userInfo = Zend_Auth::getInstance()->getIdentity() ?>
<ul>
    <?php if ($this->layout()->getView()->loginMessage): ?>
        <li>
            <?php echo $this->layout()->getView()->messageBlock($this->layout()->getView()->loginMessage) ?>
        </li>
        <li>&nbsp;|</li>
    <?php endif; ?>
    <?php if ($userInfo): ?>
        <?php if ($userInfo->privilegeName == '管理者'): ?>
            <li><?php echo $this->hyperLink('admin', '進入管理介面')?>&nbsp;|</li>
        <?php endif; ?>
        
        <?php if ($userInfo->officeName): ?>
            <li><?php echo $userInfo->officeName ?>&nbsp;|</li>
        <?php endif; ?>
        
        <?php if ($userInfo->titleName): ?>
            <li><?php echo $userInfo->titleName ?>&nbsp;|</li>
        <?php endif; ?>
        
        <li><strong><?php echo $userInfo->userName ?></strong>&nbsp;|</li>
        <li>身份：<?php echo $userInfo->privilegeName ?>&nbsp;|</li>
        <li><?php echo $this->hyperLink('logout', '登出')?></li>
    <?php else: ?>
        <li><?php echo $this->hyperLink('login', '登入')?></li>
    <?php endif; ?>
</ul>

        